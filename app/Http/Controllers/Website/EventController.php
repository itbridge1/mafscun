<?php

namespace App\Http\Controllers\Website;

use App\Models\Event;
use App\Models\EventBooking;
use App\Models\EventBookingParticipant;
use App\Models\EventParticipantAnswer;
use App\Models\EventQuestion;
use App\Models\EventRoutine;
use App\Models\PaymentQr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventController extends BaseController
{
    /* ---------------------------------------------------- EVENT LIST -- */
    public function index(Request $request)
    {
        $query = Event::active()
            ->where('title', 'not like', '%E2E Test%')
            ->orderByRaw("
                CASE 
                    WHEN start_date <= CURDATE() AND end_date >= CURDATE() THEN 1
                    WHEN start_date > CURDATE() THEN 2
                    ELSE 3
                END ASC
            ")
            ->orderByRaw("
                CASE 
                    WHEN start_date <= CURDATE() AND end_date >= CURDATE() THEN end_date
                END ASC
            ")
            ->orderByRaw("
                CASE 
                    WHEN start_date > CURDATE() THEN start_date
                END ASC
            ")
            ->orderByRaw("
                CASE 
                    WHEN end_date < CURDATE() THEN end_date
                END DESC
            ")
            ->latest();

        if ($request->filled('event_name')) {
            $query->where('title', 'like', '%' . $request->event_name . '%');
        }

        if ($request->filled('location')) {
            $query->where('remarks', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('month')) {
            // Filter by BS month stored as string e.g. "2082-03-15" -> month part
            $month = str_pad($request->month, 2, '0', STR_PAD_LEFT);
            $query->where(function ($q) use ($month, $request) {
                // Match BS date string month e.g. "2082-03-*"
                $q->where('start_date_bs', 'like', '%-' . $month . '-%')
                  ->orWhereMonth('start_date', $request->month);
            });
        }

        $_events = $query->paginate(12);
        return view($this->loadCommonDataToView('website.events.index'), compact('_events'));
    }

    /* -------------------------------------------------- EVENT DETAIL -- */
    public function show($slug)
    {
        $_event = Event::with('routines')->where('slug', $slug)->active()->first();
        $this->resourceExist($_event);

        $_other_events = Event::active()->where('title', 'not like', '%E2E Test%')->where('slug', '!=', $slug)->latest()->limit(4)->get();
        $_payment_qrs  = PaymentQr::active()->get();

        // Extract location name from google_map embed URL or iframe
        $locationName = null;
        if ($_event->google_map) {
            $mapData = $_event->google_map;

            // If stored as a full <iframe> tag, pull out the src URL first
            if (str_contains($mapData, '<iframe')) {
                preg_match('/src=["\']([^"\']+)["\']/', $mapData, $srcMatches);
                $mapData = $srcMatches[1] ?? $mapData;
            }

            // URL-decode so %2s patterns become readable
            $mapData = urldecode($mapData);

            // Find ALL !2s segments — Google embeds store the place name as one of them
            // e.g. !2sELPIZO : The perfect Hair Salon for Men/Women & Training Center
            // We skip short values, hex IDs (0x...), and common single-word codes (en, np, etc.)
            if (preg_match_all('/!2s([^!&"\'<\s][^!&"\'<]*)/', $mapData, $allMatches)) {
                foreach ($allMatches[1] as $segment) {
                    $segment = trim($segment);
                    // Skip hex place IDs
                    if (preg_match('/^0x[0-9a-f]+/i', $segment)) continue;
                    // Skip short codes (language/country like "en", "np")
                    if (strlen($segment) <= 3) continue;
                    // Skip numeric-only values
                    if (is_numeric($segment)) continue;
                    // This looks like a real name
                    $locationName = $segment;
                    break;
                }
            }

            // Fallback: try the q= parameter (used in standard maps URLs)
            if (!$locationName && preg_match('/[?&]q=([^&"\'<]+)/', $mapData, $qMatches)) {
                $locationName = urldecode(str_replace('+', ' ', $qMatches[1]));
            }
        }

        // Final fallback: use the remarks field
        if (!$locationName && $_event->remarks) {
            $locationName = $_event->remarks;
        }

        $activeDraft = null;
        if (auth()->check()) {
            $activeDraft = \App\Models\EventBooking::where('event_id', $_event->id)
                ->where('user_id', auth()->id())
                ->where('status', \App\Models\EventBooking::STATUS_DRAFT)
                ->where('created_at', '>=', now()->subMinutes(15))
                ->latest()
                ->first();
        }

        return view($this->loadCommonDataToView('website.events.show'), compact('_event', '_other_events', '_payment_qrs', 'locationName', 'activeDraft'));
    }

    /* ------------------------------------------ BOOK STEP 1: Select -- */
    public function bookStep1($slug)
    {
        $_event = Event::with('routines')->where('slug', $slug)->active()->first();
        $this->resourceExist($_event);
        
        // If user is logged in, check if they already have an ACTIVE draft for this event
        if (auth()->check()) {
            $activeDraft = EventBooking::where('event_id', $_event->id)
                ->where('user_id', auth()->id())
                ->where('status', EventBooking::STATUS_DRAFT)
                ->where('created_at', '>=', now()->subMinutes(15))
                ->latest()
                ->first();
                
            if ($activeDraft && !request()->has('edit')) {
                // Instantly redirect them to resume their active draft session!
                return redirect()->route('events.booking.continue', $activeDraft->id)
                    ->with('info', 'We found your incomplete booking session! You can continue from where you left off.');
            }
        }

        // Clear old session wizard data if starting fresh
        if (!request()->has('edit')) {
            session()->forget([
                'booking_routine_id',
                'booking_total_participants',
                'booking_contact_email',
                'booking_contact_phone',
                'booking_participants',
                'booking_is_member',
                'booking_cooperative_id',
                'booking_cooperative_name',
                'draft_booking_id'
            ]);
        }
        
        if (!$_event->isRegistrationOpen() || $_event->availableSeats() <= 0) {
            return redirect()->route('events.show', $slug)->with('error', 'Registration for this event is closed or fully booked.');
        }

        $_cooperatives = \App\Models\CooperativeInformation::active()->orderBy('rank')->get();
        return view($this->loadCommonDataToView('website.events.book_step1'), compact('_event', '_cooperatives'));
    }

    /* ---------------------------------- POST STEP 1: Save Selection -- */
    public function postStep1(Request $request, $slug)
    {
        $request->validate([
            'event_routines'      => ['required', 'array'],
            'total_participants'  => ['required', 'integer', 'min:1', 'max:20'],
            'is_member'           => ['required', 'boolean'],
            'cooperative_id'      => ['required_if:is_member,1', 'nullable', 'exists:cooperative_informations,id'],
            'cooperative_name'    => ['required_if:is_member,0', 'nullable', 'string', 'max:255'],
        ]);

        $_event = Event::where('slug', $slug)->active()->first();
        $this->resourceExist($_event);

        DB::beginTransaction();
        try {
            $lockedEvent = Event::where('id', $_event->id)->lockForUpdate()->first();
            
            if (!$lockedEvent->isRegistrationOpen() || $lockedEvent->availableSeats() < $request->total_participants) {
                DB::rollBack();
                return redirect()->route('events.show', $slug)->with('error', 'Registration for this event is closed or not enough seats available.');
            }

        if (auth()->check()) {
            $contact_phone = auth()->user()->phone;
            $contact_email = auth()->user()->email ?: ($contact_phone . '@no-email.com');
        } else {
            $contact_phone = session('booking_contact_phone');
            $contact_email = session('booking_contact_email');
        }

        $routines = [];
        if (is_array($request->event_routines)) {
            foreach ($request->event_routines as $id => $type) {
                $routines[] = [
                    'id'   => (int) $id === 0 ? null : (int) $id,
                    'type' => $type
                ];
            }
        }

        $isMember = (bool) $request->is_member;
        $totalAmount = $lockedEvent->calculateTotalPrice($request->total_participants, $routines, $isMember);
        $token = EventBooking::generateUniqueToken();

        // Delete old drafts for this user to avoid bloat
        if (auth()->check()) {
            EventBooking::where('event_id', $_event->id)
                ->where('user_id', auth()->id())
                ->where('status', EventBooking::STATUS_DRAFT)
                ->delete();
        } else {
            // Note: Guest might not have an email yet in step 1, but if they do in session, delete old drafts
            if ($contact_email) {
                EventBooking::where('event_id', $_event->id)
                    ->where('contact_email', $contact_email)
                    ->where('status', EventBooking::STATUS_DRAFT)
                    ->delete();
            }
        }

        $booking = EventBooking::create([
            'event_id'          => $_event->id,
            'event_routine_id'  => null,
            'ticket_type'       => 'multiple',
            'selected_routines' => $routines,
            'user_id'           => auth()->id(),
            'is_member'         => (bool) $request->is_member,
            'cooperative_id'    => $request->cooperative_id,
            'cooperative_name'  => $request->cooperative_name,
            'total_participants'=> (int) $request->total_participants,
            'total_amount'      => $totalAmount,
            'status'            => EventBooking::STATUS_DRAFT,
            'booking_token'     => $token,
            'contact_email'     => $contact_email,
            'contact_phone'     => $contact_phone,
        ]);

        DB::commit();

        session([
            'booking_routines'           => $routines,
            'booking_total_participants' => (int) $request->total_participants,
            'booking_contact_phone'      => $contact_phone,
            'booking_contact_email'      => $contact_email,
            'booking_is_member'          => (bool) $request->is_member,
            'booking_cooperative_id'     => $request->cooperative_id,
            'booking_cooperative_name'   => $request->cooperative_name,
            'draft_booking_id'           => $booking->id,
        ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while reserving your seats. Please try again.');
        }

        return redirect()->route('events.book.step2', $slug);
    }

    /* ------------------------------ BOOK STEP 2: Participant details -- */
    public function bookStep2(Request $request, $slug)
    {
        if (!session()->has('booking_total_participants')) {
            return redirect()->route('events.book.step1', $slug);
        }

        $_event   = Event::with(['routines', 'questions'])->where('slug', $slug)->active()->first();
        $this->resourceExist($_event);
        
        $total = session('booking_total_participants');
        $draftId = session('draft_booking_id');
        
        if (!$_event->isRegistrationOpen() || $_event->availableSeats($draftId) < $total) {
            return redirect()->route('events.show', $slug)->with('error', 'Booking closed! You were a bit late, another user just took the last available seats.');
        }
        
        $routinesList = session('booking_routines', []);
        $routines = [];
        foreach ($routinesList as $rt) {
            if ($rt['id']) {
                $rModel = EventRoutine::find($rt['id']);
                if ($rModel) {
                    $rModel->ticket_type = $rt['type'];
                    $routines[] = $rModel;
                }
            } else {
                $routines[] = ['id' => null, 'ticket_type' => $rt['type'], 'display_schedule' => 'General (No Specific Routine)'];
            }
        }

        $total = session('booking_total_participants');

        return view($this->loadCommonDataToView('website.events.book_step2'), compact('_event', 'routines', 'total'));
    }

    /* ------------------------------ POST STEP 2: Save Participants -- */
    public function postStep2(Request $request, $slug)
    {
        $_event  = Event::with('questions')->where('slug', $slug)->active()->first();
        $this->resourceExist($_event);

        $rules = [
            'participants'              => ['required', 'array', 'min:1'],
            'participants.*.first_name' => ['required', 'string', 'max:100'],
            'participants.*.middle_name'=> ['nullable', 'string', 'max:100'],
            'participants.*.last_name'  => ['required', 'string', 'max:100'],
            'participants.*.dob_bs'     => ['required', 'string', 'max:20'],
            'participants.*.phone'      => ['required', 'string', 'max:20'],
            'participants.*.email'      => ['required', 'email'],
            'participants.*.gender'     => ['required', 'string', 'in:Male,Female,Other'],
            'participants.*.designation'=> ['nullable', 'string', 'max:255'],
        ];

        // Dynamic validation for participant custom questions
        $customAttributes = [];
        $submittedParticipants = $request->input('participants', []);
        $totalSubmitted = is_array($submittedParticipants) ? count($submittedParticipants) : 0;

        foreach ($_event->questions as $question) {
            for ($i = 0; $i < $totalSubmitted; $i++) {
                $qField = "participants.{$i}.answers.{$question->id}";
                $customAttributes[$qField] = "Participant " . ($i + 1) . " - " . $question->title;
                $qRules = [];
                if ($question->is_required) {
                    $qRules[] = 'required';
                } else {
                    $qRules[] = 'nullable';
                }

                if ($question->type === 'number') {
                    $qRules[] = 'numeric';
                } elseif ($question->type === 'checkbox') {
                    $qRules[] = 'array';
                }

                if (!empty($qRules)) {
                    $rules[$qField] = $qRules;
                }
            }
        }

        if (auth()->check()) {
            if (!auth()->user()->phone) {
                $rules['contact_phone'] = ['required', 'string', 'max:20'];
            }
            if (!auth()->user()->email) {
                $rules['contact_email'] = ['required', 'email', 'max:255'];
            }
        } else {
            $rules['contact_phone'] = ['required', 'string', 'max:20'];
            $rules['contact_email'] = ['required', 'email', 'max:255'];
        }

        $request->validate($rules, [], $customAttributes);

        $total = session('booking_total_participants');
        $draftId = session('draft_booking_id');
        if (!$_event->isRegistrationOpen() || $_event->availableSeats($draftId) < $total) {
            return redirect()->route('events.show', $slug)->with('error', 'Booking closed! You were a bit late, another user just took the last available seats.');
        }

        if (auth()->check()) {
            $contact_phone = auth()->user()->phone ?: $request->contact_phone;
            $contact_email = auth()->user()->email ?: $request->contact_email;

            // Automatically sync missing details back to user profile for better UX
            $syncData = [];
            if (!auth()->user()->phone && $contact_phone) {
                $syncData['phone'] = $contact_phone;
            }
            if (!auth()->user()->email && $contact_email) {
                $syncData['email'] = $contact_email;
            }
            if (!empty($syncData)) {
                auth()->user()->update($syncData);
            }
        } else {
            $contact_phone = $request->contact_phone;
            $contact_email = $request->contact_email;
        }

        $participants = $request->participants;

        $draftBookingId = session('draft_booking_id');
        if (!$draftBookingId) {
            return redirect()->route('events.book.step1', $slug)->with('error', 'Your booking session is invalid. Please start over.');
        }

        $draftBooking = EventBooking::find($draftBookingId);
        if (!$draftBooking || $draftBooking->status != EventBooking::STATUS_DRAFT) {
            return redirect()->route('events.book.step1', $slug)->with('error', 'Your booking session is invalid. Please start over.');
        }

        // Check if draft has expired (15 minutes)
        if ($draftBooking->created_at < now()->subMinutes(15)) {
            $lockedEvent = Event::where('id', $_event->id)->lockForUpdate()->first();
            $total = session('booking_total_participants');
            if (!$lockedEvent->isRegistrationOpen() || $lockedEvent->availableSeats() < $total) {
                $draftBooking->update([
                    'status' => EventBooking::STATUS_REJECTED,
                    'rejection_note' => 'Reservation expired due to timeout and seats were taken by others.'
                ]);
                session()->forget('draft_booking_id');
                return redirect()->route('events.show', $slug)->with('error', 'Booking closed! Your reservation timed out and another user just took the last available seats.');
            } else {
                $draftBooking->update(['created_at' => now()]);
            }
        }

        DB::beginTransaction();
        try {
            $draftBooking->update([
                'contact_email' => $contact_email,
                'contact_phone' => $contact_phone,
            ]);

            // Clear old participants if user went back and forth
            EventBookingParticipant::where('event_booking_id', $draftBooking->id)->delete();

            foreach ($participants as $p) {
                $participantModel = EventBookingParticipant::create([
                    'event_booking_id' => $draftBooking->id,
                    'first_name'       => $p['first_name'],
                    'middle_name'      => $p['middle_name'] ?? null,
                    'last_name'        => $p['last_name'],
                    'dob_bs'           => $p['dob_bs'] ?? null,
                    'phone'            => $p['phone'] ?? null,
                    'email'            => $p['email'] ?? null,
                    'gender'           => $p['gender'] ?? null,
                    'designation'      => $p['designation'] ?? null,
                    'custom_answers'   => null,
                ]);

                $pAnswers = $p['answers'] ?? [];
                $customAnswersMap = [];

                foreach ($_event->questions as $q) {
                    if (isset($pAnswers[$q->id]) && $pAnswers[$q->id] !== null && $pAnswers[$q->id] !== '') {
                        $ansVal = $pAnswers[$q->id];
                        $serialized = is_array($ansVal) ? json_encode(array_values($ansVal)) : (string)$ansVal;
                        $customAnswersMap[$q->id] = is_array($ansVal) ? array_values($ansVal) : (string)$ansVal;

                        EventParticipantAnswer::create([
                            'event_booking_participant_id' => $participantModel->id,
                            'event_question_id'           => $q->id,
                            'answer'                      => $serialized,
                        ]);
                    }
                }

                $participantModel->update(['custom_answers' => $customAnswersMap]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while saving your details. Please try again.');
        }

        session([
            'booking_participants'  => $participants,
            'booking_contact_phone' => $contact_phone,
            'booking_contact_email' => $contact_email,
        ]);

        return redirect()->route('events.book.step3', $slug);
    }
    /* ------------------------------------------- BOOK STEP 3: Payment -- */
    public function bookStep3(Request $request, $slug)
    {
        $draftBookingId = session('draft_booking_id');
        if (!$draftBookingId || !session()->has('booking_participants')) {
            return redirect()->route('events.book.step2', $slug);
        }

        $_event  = Event::where('slug', $slug)->active()->first();
        $this->resourceExist($_event);

        $draftBooking = EventBooking::find($draftBookingId);
        if (!$draftBooking || $draftBooking->status != EventBooking::STATUS_DRAFT) {
            return redirect()->route('events.book.step1', $slug)->with('error', 'Your booking session is invalid. Please start over.');
        }

        $total = session('booking_total_participants');
        
        // Check if draft has expired (15 minutes)
        if ($draftBooking->created_at < now()->subMinutes(15)) {
            // It expired, we must check available seats again
            $lockedEvent = Event::where('id', $_event->id)->first();
            
            // Note: Since this draft is older than 15 minutes, it is no longer counted in availableSeats()
            if (!$lockedEvent->isRegistrationOpen() || $lockedEvent->availableSeats() < $total) {
                $draftBooking->update([
                    'status' => EventBooking::STATUS_REJECTED,
                    'rejection_note' => 'Reservation expired due to timeout and seats were taken by others.'
                ]);
                session()->forget('draft_booking_id');
                return redirect()->route('events.show', $slug)->with('error', 'Booking closed! Your reservation timed out and another user just took the last available seats.');
            } else {
                // We still have seats, let's refresh the timer
                $draftBooking->update(['created_at' => now()]);
            }
        }

        $routinesList = session('booking_routines', []);
        $routines = [];
        foreach ($routinesList as $rt) {
            if ($rt['id']) {
                $rModel = EventRoutine::find($rt['id']);
                if ($rModel) {
                    $rModel->ticket_type = $rt['type'];
                    $routines[] = $rModel;
                }
            } else {
                $routines[] = ['id' => null, 'ticket_type' => $rt['type'], 'display_schedule' => 'General (No Specific Routine)'];
            }
        }
        $participants  = session('booking_participants');
        $contact_email = session('booking_contact_email');
        $contact_phone = session('booking_contact_phone');
        $_payment_qrs  = PaymentQr::active()->get();

        return view($this->loadCommonDataToView('website.events.book_step3'), compact('_event', 'routines', 'participants', 'contact_email', 'contact_phone', '_payment_qrs', 'draftBooking'));
    }

    /* ----------------------------------------- BOOK STORE: Save -- */
    public function bookStore(Request $request, $slug)
    {
        if (!session()->has('booking_participants')) {
            return redirect()->route('events.book.step1', $slug);
        }

        $request->validate([
            'payment_voucher' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        $_event  = Event::where('slug', $slug)->active()->first();
        $this->resourceExist($_event);

        if (auth()->check()) {
            $contact_phone = auth()->user()->phone ?: session('booking_contact_phone');
            $contact_email = auth()->user()->email ?: session('booking_contact_email');
        } else {
            $contact_phone = session('booking_contact_phone');
            $contact_email = session('booking_contact_email');
        }
        $participants  = session('booking_participants');
        $routinesList  = session('booking_routines', []);        
        $draftBookingId = session('draft_booking_id');
        if (!$draftBookingId) {
            return redirect()->route('events.book.step1', $slug)->with('error', 'Your booking session is invalid. Please start over.');
        }

        $draftBooking = EventBooking::find($draftBookingId);
        if (!$draftBooking || $draftBooking->status != EventBooking::STATUS_DRAFT) {
            return redirect()->route('events.book.step1', $slug)->with('error', 'Your booking session is invalid. Please start over.');
        }

        $totalParticipants = session('booking_total_participants');
        
        // Save voucher file
        $fileName = null;
        if ($request->hasFile('payment_voucher')) {
            $file = $request->file('payment_voucher');
            $folderPath = public_path('images/vouchers');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($folderPath, $fileName);
        }

        DB::beginTransaction();
        try {
            // Check if draft has expired (15 minutes)
            if ($draftBooking->created_at < now()->subMinutes(15)) {
                // Draft expired. We must acquire a lock and check available seats again
                $lockedEvent = Event::where('id', $_event->id)->lockForUpdate()->first();
                if (!$lockedEvent->isRegistrationOpen() || $lockedEvent->availableSeats() < $totalParticipants) {
                    DB::rollBack();
                    if ($fileName && file_exists(public_path('images/vouchers/' . $fileName))) {
                        @unlink(public_path('images/vouchers/' . $fileName));
                    }
                    $draftBooking->update([
                        'status' => EventBooking::STATUS_REJECTED,
                        'rejection_note' => 'Reservation expired due to timeout and seats were taken by others.'
                    ]);
                    session()->forget('draft_booking_id');
                    return redirect()->route('events.show', $slug)->with('error', 'Booking closed! Your reservation timed out and another user just took the last available seats.');
                }
            }

            // Update draft to pending
            $draftBooking->update([
                'status'          => EventBooking::STATUS_PENDING,
                'payment_voucher' => $fileName,
                'created_at'      => now(), // Reset timestamp for exact booking time
            ]);

            DB::commit();
            
            // Clear session wizard data
            session()->forget([
                'booking_routine_id',
                'booking_total_participants',
                'booking_contact_email',
                'booking_contact_phone',
                'booking_participants',
                'booking_is_member',
                'booking_cooperative_id',
                'booking_cooperative_name',
                'draft_booking_id'
            ]);

            return redirect()->route('events.booking.success', $draftBooking->booking_token)->with('success', 'Booking submitted successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($fileName && file_exists(public_path('images/vouchers/' . $fileName))) {
                @unlink(public_path('images/vouchers/' . $fileName));
            }
            return redirect()->back()->with('error', 'An error occurred while finalizing your booking. Please try again.');
        }
    }

    /* ----------------------------------------- CONTINUE BOOKING -- */
    public function continueBooking($id)
    {
        $booking = EventBooking::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($booking->status != EventBooking::STATUS_DRAFT) {
            return redirect()->route('user.dashboard')->with('error', 'This booking is no longer a draft.');
        }

        if ($booking->created_at < now()->subMinutes(15)) {
            $booking->update([
                'status' => EventBooking::STATUS_REJECTED,
                'rejection_note' => 'Reservation expired due to timeout (Filling failed)'
            ]);
            return redirect()->route('user.dashboard')->with('error', 'Your booking session expired.');
        }

        // Restore session states needed for steps
        session([
            'draft_booking_id' => $booking->id,
            'booking_total_participants' => $booking->total_participants,
            'booking_routines' => $booking->selected_routines ?? [],
            'booking_cooperative_id' => $booking->cooperative_id,
            'booking_is_member' => $booking->is_member,
            'booking_contact_phone' => $booking->contact_phone,
            'booking_contact_email' => $booking->contact_email,
        ]);

        // If participants exist in DB, it means they already completed Step 2
        if ($booking->participants && $booking->participants->count() > 0) {
            $participantsData = $booking->participants->map(function($p) {
                return [
                    'first_name' => $p->first_name,
                    'middle_name' => $p->middle_name,
                    'last_name' => $p->last_name,
                    'email' => $p->email,
                    'phone' => $p->phone,
                    'dob_bs' => $p->dob_bs,
                    'designation' => $p->designation,
                    'gender' => $p->gender,
                    'answers' => $p->custom_answers ?? [],
                ];
            })->toArray();
            session(['booking_participants' => $participantsData]);
            return redirect()->route('events.book.step3', $booking->event->slug);
        }

        // Otherwise they need to complete Step 2
        return redirect()->route('events.book.step2', $booking->event->slug);
    }

    /* ----------------------------------------- BOOKING SUCCESS PAGE -- */
    public function bookingSuccess($token)
    {
        $_booking = EventBooking::with(['event', 'participants', 'routine', 'approvedBy'])
            ->where('booking_token', $token)
            ->firstOrFail();

        $_payment_qrs = PaymentQr::active()->get();

        return view($this->loadCommonDataToView('website.events.booking_success'), compact('_booking', '_payment_qrs'));
    }

    /* -------------------------------------- UPLOAD PAYMENT VOUCHER -- */
    public function uploadVoucher(Request $request, $token)
    {
        $request->validate([
            'payment_voucher' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        $booking = EventBooking::where('booking_token', $token)->firstOrFail();

        if ($request->hasFile('payment_voucher')) {
            $file = $request->file('payment_voucher');
            $folderPath = public_path('images/vouchers');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($folderPath, $fileName);

            // Delete old voucher if it exists
            if ($booking->payment_voucher && file_exists($folderPath . '/' . $booking->payment_voucher)) {
                @unlink($folderPath . '/' . $booking->payment_voucher);
            }

            $booking->update([
                'payment_voucher' => $fileName,
            ]);
        }

        return redirect()->back()->with('success', 'Payment voucher uploaded successfully! The admin will verify it soon.');
    }
    public function publicCards($token)
    {
        $booking = EventBooking::with('participants', 'event')->where('booking_token', $token)->firstOrFail();
        
        $participantQrs = [];
        try {
            $options = new \chillerlan\QRCode\QROptions([
                'outputInterface' => \chillerlan\QRCode\Output\QRGdImagePNG::class,
                'scale'           => 5,
                'imageTransparent'=> false,
            ]);
            $qrcode = new \chillerlan\QRCode\QRCode($options);

            foreach ($booking->participants as $participant) {
                $participantQrs[$participant->id] = $participant->generateQrCodeBase64();
            }
        } catch (\Exception $e) {
            \Log::error('Participant QR generation for publicCards failed: ' . $e->getMessage());
        }

        $siteSetting = \App\Models\SiteSetting::first();
        return view($this->loadCommonDataToView('website.events.public_cards'), compact('booking', 'siteSetting', 'participantQrs'));
    }

    public function downloadPublicCards($token)
    {
        $booking = EventBooking::with('participants', 'event')->where('booking_token', $token)->firstOrFail();
        
        $participantQrs = [];
        try {
            foreach ($booking->participants as $participant) {
                $participantQrs[$participant->id] = $participant->generateQrCodeBase64();
            }
        } catch (\Exception $e) {
            \Log::error('Participant QR generation for downloadPublicCards failed: ' . $e->getMessage());
        }

        $siteSetting = \App\Models\SiteSetting::first();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('emails.eventBookingPdf', compact('booking', 'siteSetting', 'participantQrs'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Event_Entry_Cards_' . $booking->booking_token . '.pdf');
    }
}
