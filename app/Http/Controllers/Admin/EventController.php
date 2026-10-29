<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Event\StoreEventValidation;
use App\Http\Requests\Admin\Event\UpdateEventValidation;
use App\Models\Event;
use App\Models\EventBooking;
use App\Models\EventRoutine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;

class EventController extends BaseController
{
    protected $base_route = 'admin.event';
    protected $view_path  = 'admin.event';
    protected $panel      = 'Event';
    protected $folder     = 'events';
    protected $folder_path;

    public function __construct(Event $model)
    {
        $this->model       = $model;
        $this->folder_path = 'images' . DIRECTORY_SEPARATOR . $this->folder;
        // $this->generateAllMiddlewareByPermission();
    }

    /* ---------------------------------------------------------- INDEX -- */
    public function index()
    {
        $data['rows'] = $this->model->withCount('bookings')->latest()->paginate(10);
        return view(parent::loadCommonDataToView($this->view_path . '.index'), compact('data'));
    }

    /* --------------------------------------------------------- CREATE -- */
    public function create()
    {
        return view(parent::loadCommonDataToView($this->view_path . '.create'));
    }

    /* ---------------------------------------------------------- STORE -- */
    public function store(StoreEventValidation $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->safe()->except(['routines', 'main_photo', 'questions']);
            $data['has_group_pricing'] = $request->has('has_group_pricing') ? 1 : 0;
            if (!$data['has_group_pricing']) {
                $data['group_size'] = null;
                $data['group_price'] = null;
                $data['non_member_group_price'] = null;
            }

            // Handle photo upload
            if ($request->hasFile('main_photo')) {
                $this->processImage($request->file('main_photo'));
                $data['photo'] = $this->file_name;
            }

            $event = $this->model->create($data);

            // Save routines
            if ($request->has('routines') && is_array($request->routines)) {
                foreach ($request->routines as $routine) {
                    $isAllDay = isset($routine['is_all_day']) && $routine['is_all_day'] ? 1 : 0;
                    $isGroup = isset($routine['has_group_pricing']) && $routine['has_group_pricing'] ? 1 : 0;
                    $routineData = [
                        'date'                   => $routine['date'] ?? null,
                        'is_all_day'             => $isAllDay,
                        'start_time'             => $isAllDay ? null : ($routine['start_time'] ?? null),
                        'end_time'               => $isAllDay ? null : ($routine['end_time'] ?? null),
                        'about'                  => $routine['about'] ?? null,
                        'price'                  => $routine['price'] ?? 0,
                        'non_member_price'       => isset($routine['non_member_price']) && $routine['non_member_price'] !== '' ? $routine['non_member_price'] : null,
                        'has_group_pricing'      => $isGroup,
                        'group_size'             => $isGroup ? ($routine['group_size'] ?? null) : null,
                        'group_price'            => $isGroup ? ($routine['group_price'] ?? null) : null,
                        'non_member_group_price' => $isGroup ? ($routine['non_member_group_price'] ?? null) : null,
                    ];
                    $event->routines()->create($routineData);
                }
            }

            // Save custom questions
            if ($request->has('questions') && is_array($request->questions)) {
                foreach ($request->questions as $order => $qData) {
                    if (!empty($qData['title'])) {
                        $event->questions()->create([
                            'title'       => $qData['title'],
                            'type'        => $qData['type'] ?? 'text',
                            'options'     => $qData['options'] ?? null,
                            'is_required' => !empty($qData['is_required']) ? 1 : 0,
                            'order'       => isset($qData['order']) ? (int)$qData['order'] : $order,
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        $request->session()->flash('success_message', $this->panel . ' "' . $event->title . '" added successfully');
        return redirect()->route($this->base_route . '.index');
    }

    /* ------------------------------------------------------------ SHOW -- */
    public function show($id)
    {
        $event = $this->model->with(['routines', 'bookings.participants', 'bookings.routine'])->find($id);
        parent::rowExist($event);
        
        $data['row'] = $event;
        
        $approvedBookings = $event->bookings->where('status', \App\Models\EventBooking::STATUS_APPROVED);
        
        $totalSeats = $event->total_seats;
        $bookedSeats = $event->bookedSeatsCount(); // uses pending + approved
        $availableSeats = $event->availableSeats();
        
        $totalApprovedParticipants = $approvedBookings->sum('total_participants');
        
        // Count total participants marked as present
        $presentParticipants = 0;
        foreach ($approvedBookings as $booking) {
            if ($booking->is_present) {
                $presentParticipants += $booking->total_participants;
            }
        }
        
        $absentParticipants = $totalApprovedParticipants - $presentParticipants;
        
        $presentPercentage = $totalApprovedParticipants > 0 
            ? round(($presentParticipants / $totalApprovedParticipants) * 100, 1) 
            : 0;
            
        $absentPercentage = $totalApprovedParticipants > 0 
            ? round(($absentParticipants / $totalApprovedParticipants) * 100, 1) 
            : 0;
            
        $data['stats'] = [
            'total_seats' => $totalSeats,
            'available_seats' => $availableSeats,
            'booked_seats' => $bookedSeats,
            'total_approved_participants' => $totalApprovedParticipants,
            'present_participants' => $presentParticipants,
            'absent_participants' => $absentParticipants,
            'present_percentage' => $presentPercentage,
            'absent_percentage' => $absentPercentage,
        ];
        
        return view(parent::loadCommonDataToView($this->view_path . '.show'), compact('data'));
    }

    /* ------------------------------------------------------------ EDIT -- */
    public function edit($id)
    {
        $data['row'] = $this->model->with(['routines', 'questions'])->find($id);
        parent::rowExist($data['row']);
        return view(parent::loadCommonDataToView($this->view_path . '.edit'), compact('data'));
    }

    /* ---------------------------------------------------------- UPDATE -- */
    public function update(UpdateEventValidation $request, $id)
    {
        $row = $this->model->find($id);
        parent::rowExist($row);

        DB::beginTransaction();
        try {
            $data = $request->safe()->except(['routines', 'main_photo', 'questions']);
            $data['has_group_pricing'] = $request->has('has_group_pricing') ? 1 : 0;
            if (!$data['has_group_pricing']) {
                $data['group_size'] = null;
                $data['group_price'] = null;
                $data['non_member_group_price'] = null;
            }

            if ($request->hasFile('main_photo')) {
                if ($row->photo) {
                    @$this->removeFile($this->folder_path . DIRECTORY_SEPARATOR . $row->photo);
                }
                $this->processImage($request->file('main_photo'));
                $data['photo'] = $this->file_name;
            } else {
                $data['photo'] = $row->photo;
            }

            $row->update($data);

            // Sync routines: keep existing IDs to preserve booking links
            $submittedRoutines = $request->input('routines', []);
            $submittedIds = collect($submittedRoutines)->pluck('id')->filter()->all();
            
            // Delete removed routines
            $row->routines()->whereNotIn('id', $submittedIds)->delete();
            
            // Update or Create
            if (is_array($submittedRoutines)) {
                foreach ($submittedRoutines as $routine) {
                    $isAllDay = isset($routine['is_all_day']) && $routine['is_all_day'] ? 1 : 0;
                    $isGroup = isset($routine['has_group_pricing']) && $routine['has_group_pricing'] ? 1 : 0;
                    $routineData = [
                        'date'                   => $routine['date'] ?? null,
                        'is_all_day'             => $isAllDay,
                        'start_time'             => $isAllDay ? null : ($routine['start_time'] ?? null),
                        'end_time'               => $isAllDay ? null : ($routine['end_time'] ?? null),
                        'about'                  => $routine['about'] ?? null,
                        'price'                  => $routine['price'] ?? 0,
                        'non_member_price'       => isset($routine['non_member_price']) && $routine['non_member_price'] !== '' ? $routine['non_member_price'] : null,
                        'has_group_pricing'      => $isGroup,
                        'group_size'             => $isGroup ? ($routine['group_size'] ?? null) : null,
                        'group_price'            => $isGroup ? ($routine['group_price'] ?? null) : null,
                        'non_member_group_price' => $isGroup ? ($routine['non_member_group_price'] ?? null) : null,
                    ];

                    if (isset($routine['id']) && $routine['id']) {
                        $row->routines()->where('id', $routine['id'])->update($routineData);
                    } else {
                        $row->routines()->create($routineData);
                    }
                }
            }

            // Sync custom questions: keep existing IDs
            $submittedQuestions = $request->input('questions', []);
            $submittedQIds = collect($submittedQuestions)->pluck('id')->filter()->all();

            // Delete removed questions
            $row->questions()->whereNotIn('id', $submittedQIds)->delete();

            if (is_array($submittedQuestions)) {
                foreach ($submittedQuestions as $order => $qData) {
                    if (empty($qData['title'])) continue;

                    $questionAttributes = [
                        'title'       => $qData['title'],
                        'type'        => $qData['type'] ?? 'text',
                        'options'     => $qData['options'] ?? null,
                        'is_required' => !empty($qData['is_required']) ? 1 : 0,
                        'order'       => isset($qData['order']) ? (int)$qData['order'] : $order,
                    ];

                    if (isset($qData['id']) && $qData['id']) {
                        $row->questions()->where('id', $qData['id'])->update($questionAttributes);
                    } else {
                        $row->questions()->create($questionAttributes);
                    }
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        $request->session()->flash('success_message', $this->panel . ' "' . $row->title . '" updated successfully');
        return redirect()->route($this->base_route . '.index');
    }

    /* --------------------------------------------------------- DESTROY -- */
    public function destroy($id)
    {
        $row = $this->model->find($id);
        parent::rowExist($row);

        if ($row->photo) {
            @$this->removeFile($this->folder_path . DIRECTORY_SEPARATOR . $row->photo);
        }

        $row->delete();

        return redirect()->route($this->base_route . '.index')
            ->with('success_message', $this->panel . ' deleted successfully');
    }

    public function approveBooking(Request $request, $bookingId)
    {
        $booking = EventBooking::with(['event', 'participants', 'routine'])->find($bookingId);
        if (!$booking) {
            return redirect()->back()->with('error_message', 'Booking not found');
        }

        // Generate unique booking token if not set
        if (!$booking->booking_token) {
            $booking->booking_token = EventBooking::generateUniqueToken();
        }

        DB::beginTransaction();
        try {
            // Generate QR code for this booking
            $qrData = json_encode([
                'booking_id'    => $booking->id,
                'token'         => $booking->booking_token,
                'event'         => $booking->event->title,
            ]);

            $qrFolder = public_path('images/event_bookings/qr');
            if (!file_exists($qrFolder)) {
                @mkdir($qrFolder, 0755, true);
            }

            $options = new \chillerlan\QRCode\QROptions([
                'outputInterface' => \chillerlan\QRCode\Output\QRGdImagePNG::class,
                'scale'           => 5,
                'imageTransparent'=> false,
            ]);
            $qrcode = new \chillerlan\QRCode\QRCode($options);
            $qrDataUri = $qrcode->render($qrData);
            $base64Part = explode(',', $qrDataUri)[1];
            $pngData = base64_decode($base64Part);

            $qrFileName = 'qr_' . $booking->id . '_' . time() . '.png';
            file_put_contents($qrFolder . DIRECTORY_SEPARATOR . $qrFileName, $pngData);

            // Generate QR Code for each participant
            foreach ($booking->participants as $participant) {
                if (empty($participant->qr_code)) {
                    $participant->update([
                        'qr_code' => $participant->generateQrCodeBase64()
                    ]);
                }
            }

            $booking->update([
                'status'      => EventBooking::STATUS_APPROVED,
                'qr_code'     => $qrFileName,
                'approved_by' => auth()->id(),
            ]);

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        $request->session()->flash('success_message', 'Booking approved successfully');
        return redirect()->back();
    }

    /* --------------------------------------------------- UPDATE BOOKING -- */
    public function updateBooking(Request $request, $bookingId)
    {
        $booking = EventBooking::with('participants')->findOrFail($bookingId);

        $request->validate([
            'contact_email'               => ['required', 'email'],
            'contact_phone'               => ['nullable', 'string', 'max:20'],
            'event_routine_id'            => ['nullable', 'exists:event_routines,id'],
            'cooperative_name'            => ['nullable', 'string', 'max:255'],
            'participants'                => ['required', 'array'],
            'participants.*.id'           => ['required', 'exists:event_booking_participants,id'],
            'participants.*.name'         => ['nullable', 'string', 'max:255'],
            'participants.*.first_name'   => ['nullable', 'string', 'max:255'],
            'participants.*.middle_name'  => ['nullable', 'string', 'max:255'],
            'participants.*.last_name'    => ['nullable', 'string', 'max:255'],
            'participants.*.phone'        => ['nullable', 'string', 'max:20'],
            'participants.*.email'        => ['nullable', 'email', 'max:255'],
            'participants.*.dob_bs'       => ['nullable', 'string', 'max:20'],
            'participants.*.designation'  => ['nullable', 'string', 'max:255'],
            'participants.*.gender'       => ['nullable', 'string', 'in:Male,Female,Other'],
        ]);

        DB::beginTransaction();
        try {
            // Update booking details
            $booking->update([
                'contact_email'    => $request->contact_email,
                'contact_phone'    => $request->contact_phone,
                'event_routine_id' => $request->event_routine_id,
                'cooperative_name' => $request->cooperative_name,
            ]);

            // Update participant details
            foreach ($request->participants as $pData) {
                $participant = $booking->participants->find($pData['id']);
                if ($participant) {
                    $fullName = !empty($pData['name']) 
                        ? trim($pData['name']) 
                        : trim(($pData['first_name'] ?? '') . ' ' . ($pData['middle_name'] ?? '') . ' ' . ($pData['last_name'] ?? ''));

                    if (empty($fullName)) {
                        $fullName = $participant->name;
                    }

                    $participant->update([
                        'name'        => $fullName,
                        'first_name'  => $pData['first_name'] ?? $participant->first_name,
                        'middle_name' => $pData['middle_name'] ?? $participant->middle_name,
                        'last_name'   => $pData['last_name'] ?? $participant->last_name,
                        'phone'       => $pData['phone'] ?? $participant->phone,
                        'email'       => $pData['email'] ?? $participant->email,
                        'gender'      => $pData['gender'] ?? $participant->gender,
                        'dob_bs'      => $pData['dob_bs'] ?? $participant->dob_bs,
                        'designation' => $pData['designation'] ?? $participant->designation,
                    ]);

                    try {
                        $participant->update(['qr_code' => $participant->generateQrCodeBase64()]);
                    } catch (\Throwable $e) {}
                }
            }

            // Recalculate amount if routine/price changed
            $totalParticipants = $booking->participants->count();
            $newAmount = ($booking->selected_routines && count($booking->selected_routines) > 0)
                ? $booking->event->calculateTotalPrice($totalParticipants, $booking->selected_routines, (bool)$booking->is_member)
                : $booking->event->calculatePrice($totalParticipants, $request->event_routine_id, 'single', (bool)$booking->is_member);
            $booking->update(['total_amount' => $newAmount]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error_message', 'Failed to update booking: ' . $e->getMessage());
        }

        return redirect()->back()->with('success_message', 'Booking details updated successfully');
    }

    /* -------------------------------------------------- REJECT BOOKING -- */
    public function rejectBooking($bookingId)
    {
        $booking = EventBooking::find($bookingId);
        if (!$booking) {
            return redirect()->back()->with('error_message', 'Booking not found');
        }
        $booking->update(['status' => EventBooking::STATUS_REJECTED]);
        return redirect()->back()->with('success_message', 'Booking rejected');
    }

    /* -------------------------------------------- SEND EVENT DETAILS EMAIL -- */
    public function sendEventDetailsMail(Request $request, $bookingId)
    {
        $booking = EventBooking::with(['event', 'participants', 'routine'])->find($bookingId);
        if (!$booking || $booking->status !== EventBooking::STATUS_APPROVED) {
            return redirect()->back()->with('error_message', 'Booking not found or not yet approved');
        }

        Mail::to($booking->contact_email)->send(new \App\Mail\EventBookingMail($booking));

        return redirect()->back()->with('success_message', 'Event details email sent successfully to ' . $booking->contact_email);
    }

    /* ------------------------------ SEND BULK MESSAGE TO APPROVED BOOKINGS -- */
    public function sendBulkMessage(Request $request, $eventId)
    {
        $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $event = $this->model->find($eventId);
        parent::rowExist($event);

        $bookings = EventBooking::where('event_id', $eventId)
            ->where('status', EventBooking::STATUS_APPROVED)
            ->get();

        if ($bookings->isEmpty()) {
            return redirect()->back()->with('error_message', 'No approved bookings found for this event to send messages.');
        }

        $sentCount = 0;
        foreach ($bookings as $booking) {
            try {
                Mail::to($booking->contact_email)->send(new \App\Mail\EventCustomMessageMail($booking, $request->subject, $request->message));
                $sentCount++;
            } catch (\Exception $e) {
                // Keep sending
            }
        }

        return redirect()->back()->with('success_message', "Message sent successfully to {$sentCount} approved booking(s).");
    }

    /* -------------------------------------------- MARK PRESENT (API/Web) -- */
    public function markPresent(Request $request, $bookingId)
    {
        $booking = EventBooking::with(['participants', 'routine'])->find($bookingId);
        if (!$booking) {
            return redirect()->back()->with('error_message', 'Booking not found');
        }

        $routine = $booking->routine;
        if ($routine) {
            $now = \Carbon\Carbon::now();
            if ($routine->date && $now->format('Y-m-d') !== $routine->date) {
                return redirect()->back()->with('error_message', 'Entry denied: Scheduled for ' . $routine->date . ', not today.');
            }
            if (!$routine->is_all_day && $routine->start_time && $routine->end_time) {
                $startTime = \Carbon\Carbon::parse($routine->start_time)->subMinutes(60)->format('H:i:s');
                $endTime = $routine->end_time;
                $currentTime = $now->format('H:i:s');
                
                if ($currentTime < $startTime) {
                    return redirect()->back()->with('error_message', 'Entry denied: Session has not started yet (Starts at ' . $routine->start_time . ').');
                }
                if ($currentTime > $endTime) {
                    return redirect()->back()->with('error_message', 'Entry denied: Session has already ended (Ended at ' . $routine->end_time . ').');
                }
            }
        }

        DB::beginTransaction();
        try {
            $booking->update(['is_present' => 1]);
            $booking->participants()->update(['is_present' => 1]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error_message', 'Failed to update attendance status.');
        }

        return redirect()->back()->with('success_message', 'All participants marked as present');
    }

    public function markParticipantPresent(Request $request, $participantId)
    {
        $participant = \App\Models\EventBookingParticipant::findOrFail($participantId);
        
        $routine = $participant->booking->routine;
        if ($routine) {
            $now = \Carbon\Carbon::now();
            
            // Check date if specified
            if ($routine->date) {
                if ($now->format('Y-m-d') !== $routine->date) {
                    return redirect()->back()->with('error_message', 'Entry denied: This participant is scheduled for ' . $routine->date . ', not today.');
                }
            }
            
            // Check time if not all day
            if (!$routine->is_all_day && $routine->start_time && $routine->end_time) {
                // allow 60 mins before start, and up to end time
                $startTime = \Carbon\Carbon::parse($routine->start_time)->subMinutes(60)->format('H:i:s');
                $endTime = $routine->end_time;
                $currentTime = $now->format('H:i:s');
                
                if ($currentTime < $startTime) {
                    return redirect()->back()->with('error_message', 'Entry denied: Session has not started yet (Starts at ' . $routine->start_time . ').');
                }
                
                if ($currentTime > $endTime) {
                    return redirect()->back()->with('error_message', 'Entry denied: Session has already ended (Ended at ' . $routine->end_time . ').');
                }
            }
        }
        
        DB::beginTransaction();
        try {
            $targetDate = $request->input('date', now()->format('Y-m-d'));
            $attDates = $participant->attendance_dates ?: [];
            if (!in_array($targetDate, $attDates)) {
                $attDates[] = $targetDate;
            }

            $participant->update([
                'is_present' => 1,
                'attendance_dates' => array_values(array_unique($attDates))
            ]);

            // If all participants of the booking are present, mark booking as present
            $booking = $participant->booking;
            if ($booking && !$booking->participants()->where('is_present', 0)->exists()) {
                $booking->update(['is_present' => 1]);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error_message', 'Failed to update participant attendance status.');
        }

        return redirect()->back()->with('success_message', 'Participant marked as present successfully');
    }

    public function updateParticipant(Request $request, $id)
    {
        $participant = \App\Models\EventBookingParticipant::findOrFail($id);

        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'gender'         => ['required', 'string', 'in:Male,Female,Other'],
            'designation'    => ['nullable', 'string', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:20'],
            'email'          => ['nullable', 'email', 'max:255'],
            'dob_bs'         => ['nullable', 'string', 'max:50'],
            'answers'        => ['nullable', 'array'],
            'custom_answers' => ['nullable', 'array'],
        ]);

        $participant->update([
            'name'        => $request->name,
            'gender'      => $request->gender,
            'designation' => $request->designation,
            'phone'       => $request->phone,
            'email'       => $request->email,
            'dob_bs'      => $request->dob_bs,
        ]);

        // Save custom answers if provided
        $providedAnswers = $request->input('custom_answers', $request->input('answers'));
        if (is_array($providedAnswers)) {
            $customAnswersMap = $participant->custom_answers ?: [];
            foreach ($providedAnswers as $qId => $ansVal) {
                $serialized = is_array($ansVal) ? json_encode(array_values($ansVal)) : (string)$ansVal;
                $customAnswersMap[$qId] = is_array($ansVal) ? array_values($ansVal) : (string)$ansVal;
                \App\Models\EventParticipantAnswer::updateOrCreate(
                    [
                        'event_booking_participant_id' => $participant->id,
                        'event_question_id'           => $qId,
                    ],
                    [
                        'answer'                      => $serialized,
                    ]
                );
            }
            $participant->update(['custom_answers' => $customAnswersMap]);
        }

        // Auto-regenerate QR code image data if participant details change
        try {
            $participant->update(['qr_code' => $participant->generateQrCodeBase64()]);
        } catch (\Throwable $e) {
            \Log::error('QR regeneration failed on updateParticipant: ' . $e->getMessage());
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Participant details updated successfully.',
                'participant' => $participant
            ]);
        }

        return redirect()->back()->with('success_message', 'Participant details updated successfully.');
    }

    /* -------------------------------------------- ALL BOOKINGS LIST -- */
    public function bookings(Request $request, $eventId)
    {
        $data['event']    = $this->model->with('questions')->find($eventId);
        parent::rowExist($data['event']);
        
        // Auto-cleanup expired drafts and convert them to rejected
        EventBooking::where('event_id', $eventId)
            ->where('status', EventBooking::STATUS_DRAFT)
            ->where('created_at', '<', now()->subMinutes(15))
            ->update([
                'status' => EventBooking::STATUS_REJECTED,
                'rejection_note' => 'Reservation expired due to timeout (Filling failed)'
            ]);

        $query = EventBooking::with(['participants.answers.question', 'routine', 'user'])
            ->where('event_id', $eventId);

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status') && $request->payment_status !== '') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('cooperative_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('contact_phone', 'like', "%{$search}%")
                  ->orWhere('contact_email', 'like', "%{$search}%")
                  ->orWhere('booking_token', 'like', "%{$search}%");
            });
        }

        $data['bookings'] = $query->latest()->get();
        
        $allBookings = EventBooking::where('event_id', $eventId)->get();
        $approvedBookings = $allBookings->where('status', EventBooking::STATUS_APPROVED);
        $presentCount = $approvedBookings->where('is_present', 1)->count();
        $totalApproved = $approvedBookings->count();

        $data['stats'] = [
            'total_bookings' => $allBookings->count(),
            'approved_bookings' => $totalApproved,
            'pending_bookings' => $allBookings->where('status', EventBooking::STATUS_PENDING)->count(),
            'rejected_bookings' => $allBookings->where('status', EventBooking::STATUS_REJECTED)->count(),
            'draft_bookings' => $allBookings->where('status', EventBooking::STATUS_DRAFT)->count(),
            'present_bookings' => $presentCount,
            'total_participants_approved' => $approvedBookings->sum('total_participants'),
            'attendance_rate' => $totalApproved > 0 ? round(($presentCount / $totalApproved) * 100, 1) : 0,
        ];

        $users = \App\Models\User::whereDoesntHave('roles')->orderBy('name')->get();

        return view(parent::loadCommonDataToView($this->view_path . '.bookings'), compact('data', 'users'));
    }

    public function participants(Request $request, $eventId)
    {
        $data['event']    = $this->model->with('questions')->find($eventId);
        parent::rowExist($data['event']);
        $data['questions'] = $data['event']->questions;
        
        // Build a list of all unique calendar dates the event spans (for per-day attendance tabs)
        $eventDates = [];
        $event = $data['event'];
        if ($event->start_date) {
            $start = \Carbon\Carbon::parse($event->start_date);
            $end   = $event->end_date ? \Carbon\Carbon::parse($event->end_date) : $start->copy();
            for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                $eventDates[] = $d->format('Y-m-d');
            }
        }
        $data['event_dates'] = $eventDates;
        $totalEventDays = count($eventDates);

        $query = \App\Models\EventBookingParticipant::with(['booking', 'booking.routine', 'booking.user', 'booking.cooperative', 'answers.question'])
            ->whereHas('booking', function($q) use ($eventId) {
                $q->where('event_id', $eventId)->where('status', \App\Models\EventBooking::STATUS_APPROVED);
            });

        // Question-wise filter
        if ($request->filled('question_id')) {
            $filterQId = $request->question_id;
            $filterAns = $request->answer_value;
            $query->whereHas('answers', function($subQ) use ($filterQId, $filterAns) {
                $subQ->where('event_question_id', $filterQId);
                if ($filterAns !== null && $filterAns !== '') {
                    $subQ->where('answer', 'like', "%{$filterAns}%");
                }
            });
        }
            
        $allParticipants = $query->latest()->get();
        
        // Count overall stats based on whether participants attended all event days
        $allDaysPresentCount = 0;
        $absentCount = 0;

        foreach ($allParticipants as $p) {
            $attDates = $p->attendance_dates ?: [];
            if ($totalEventDays <= 1) {
                if ((int)$p->is_present === 1) {
                    $allDaysPresentCount++;
                } else {
                    $absentCount++;
                }
            } else {
                $presentCount = count(array_intersect($eventDates, $attDates));
                if ($presentCount >= $totalEventDays) {
                    $allDaysPresentCount++;
                } else {
                    $absentCount++;
                }
            }
        }

        $status = $request->get('status');
        if ($status === '1') {
            // "Present Only" / "All Days Present" -> filter participants who attended all event days
            $data['participants'] = $allParticipants->filter(function($p) use ($eventDates, $totalEventDays) {
                if ($totalEventDays <= 1) {
                    return (int)$p->is_present === 1;
                }
                $attDates = $p->attendance_dates ?: [];
                return count(array_intersect($eventDates, $attDates)) >= $totalEventDays;
            })->values();
        } elseif ($status === '0') {
            // "Absent Only" -> filter participants who missed 1 or more event days
            $data['participants'] = $allParticipants->filter(function($p) use ($eventDates, $totalEventDays) {
                if ($totalEventDays <= 1) {
                    return (int)$p->is_present === 0;
                }
                $attDates = $p->attendance_dates ?: [];
                return count(array_intersect($eventDates, $attDates)) < $totalEventDays;
            })->values();
        } else {
            $data['participants'] = $allParticipants;
        }
        
        // Pass statistics to view
        $data['stats'] = [
            'total'   => $allParticipants->count(),
            'present' => $allDaysPresentCount,
            'absent'  => $absentCount,
            'attendance_rate' => $allParticipants->count() > 0 
                ? round(($allDaysPresentCount / $allParticipants->count()) * 100, 1) 
                : 0,
        ];

        return view(parent::loadCommonDataToView($this->view_path . '.participants'), compact('data'));
    }

    public function showBooking($id)
    {
        $booking = EventBooking::with(['participants.answers.question', 'routine', 'user', 'event.questions', 'approvedBy'])->findOrFail($id);
        
        $this->panel = 'Event Bookings';
        $this->base_route = 'admin.event';

        return view(parent::loadCommonDataToView($this->view_path . '.show_booking'), compact('booking'));
    }

    public function printCards($id)
    {
        $booking = EventBooking::with(['participants', 'event', 'user'])->findOrFail($id);
        
        // Auto-heal missing QR codes for all participants
        foreach ($booking->participants as $participant) {
            if (empty($participant->qr_code)) {
                try {
                    $participant->update(['qr_code' => $participant->generateQrCodeBase64()]);
                } catch (\Exception $e) {
                    \Log::error('Dynamic QR generation failed on printCards: ' . $e->getMessage());
                }
            }
        }

        $siteSetting = \App\Models\SiteSetting::first();
        
        return view('admin.event.print_cards', compact('booking', 'siteSetting'));
    }

    public function printBulkCards($eventId)
    {
        $event = $this->model->findOrFail($eventId);
        $bookings = EventBooking::with(['participants'])
            ->where('event_id', $eventId)
            ->where('status', EventBooking::STATUS_APPROVED)
            ->get();

        // Auto-heal missing QR codes for all participants of approved bookings
        foreach ($bookings as $booking) {
            foreach ($booking->participants as $participant) {
                if (empty($participant->qr_code)) {
                    try {
                        $participant->update(['qr_code' => $participant->generateQrCodeBase64()]);
                    } catch (\Exception $e) {
                        \Log::error('Dynamic QR generation failed on printBulkCards: ' . $e->getMessage());
                    }
                }
            }
        }

        $siteSetting = \App\Models\SiteSetting::first();
        
        return view('admin.event.print_bulk_cards', compact('event', 'bookings', 'siteSetting'));
    }

    /* -------------------------------------------- STORE ADMIN BOOKING -- */
    public function storeBooking(Request $request, $eventId)
    {
        $request->validate([
            'contact_email'             => ['required', 'email'],
            'contact_phone'             => ['nullable', 'string', 'max:20'],
            'event_routine_id'          => ['nullable', 'exists:event_routines,id'],
            'is_member'                 => ['nullable'],
            'cooperative_name'          => ['nullable', 'string', 'max:255'],
            'participants'              => ['required', 'array', 'min:1'],
            'participants.*.name'       => ['nullable', 'string', 'max:255'],
            'participants.*.first_name' => ['nullable', 'string', 'max:255'],
            'participants.*.middle_name'=> ['nullable', 'string', 'max:255'],
            'participants.*.last_name'  => ['nullable', 'string', 'max:255'],
            'participants.*.phone'      => ['nullable', 'string', 'max:20'],
            'participants.*.email'      => ['nullable', 'email'],
            'participants.*.gender'     => ['required', 'string', 'in:Male,Female,Other'],
            'participants.*.dob_bs'     => ['nullable', 'string', 'max:20'],
            'participants.*.designation'=> ['nullable', 'string', 'max:255'],
        ]);

        $event = $this->model->find($eventId);
        parent::rowExist($event);

        $totalParticipants = count($request->participants);
        if ($event->availableSeats() < $totalParticipants) {
            return redirect()->back()->with('error_message', 'Not enough available seats left for this event.');
        }

        $isMember          = $request->has('is_member') ? ((string)$request->is_member === '1' || $request->is_member === true) : true;
        $totalAmount       = $event->calculatePrice($totalParticipants, $request->event_routine_id, 'single', $isMember);
        $token             = EventBooking::generateUniqueToken();

        DB::beginTransaction();
        try {
            $booking = EventBooking::create([
                'event_id'          => $event->id,
                'event_routine_id'  => $request->event_routine_id,
                'total_participants'=> $totalParticipants,
                'total_amount'      => $totalAmount,
                'is_member'         => $isMember,
                'cooperative_name'  => $request->cooperative_name,
                'status'            => EventBooking::STATUS_PENDING, // Pending
                'booking_token'     => $token,
                'contact_email'     => $request->contact_email,
                'contact_phone'     => $request->contact_phone,
            ]);

            foreach ($request->participants as $p) {
                $fullName = !empty($p['name'])
                    ? trim($p['name'])
                    : trim(($p['first_name'] ?? '') . ' ' . ($p['middle_name'] ?? '') . ' ' . ($p['last_name'] ?? ''));
                if (empty($fullName)) {
                    $fullName = 'Participant';
                }

                $newPart = \App\Models\EventBookingParticipant::create([
                    'event_booking_id' => $booking->id,
                    'name'             => $fullName,
                    'first_name'       => $p['first_name'] ?? null,
                    'middle_name'      => $p['middle_name'] ?? null,
                    'last_name'        => $p['last_name'] ?? null,
                    'phone'            => $p['phone'] ?? null,
                    'email'            => $p['email'] ?? null,
                    'gender'           => $p['gender'] ?? null,
                    'dob_bs'           => $p['dob_bs'] ?? null,
                    'designation'      => $p['designation'] ?? null,
                ]);

                try {
                    $newPart->update(['qr_code' => $newPart->generateQrCodeBase64()]);
                } catch (\Throwable $e) {}
            }

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->back()->with('success_message', 'Booking added successfully and is pending approval.');
    }

    public function certificateSettings($eventId)
    {
        $data['event'] = $this->model->findOrFail($eventId);
        return view(parent::loadCommonDataToView($this->view_path . '.certificate'), compact('data'));
    }

    public function updateCertificateSettings(Request $request, $eventId)
    {
        $event = $this->model->findOrFail($eventId);

        if ($request->hasFile('certificate_bg_image')) {
            $request->validate(['certificate_bg_image' => 'image|mimes:jpeg,png|max:12288']);
            $file     = $request->file('certificate_bg_image');
            $filename = time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/events'), $filename);
            if ($event->certificate_bg_image && $event->certificate_bg_image !== 'default_certificate_bg.jpg' && file_exists(public_path('images/events/' . $event->certificate_bg_image))) {
                @unlink(public_path('images/events/' . $event->certificate_bg_image));
            }
            $event->certificate_bg_image = $filename;
        }

        $settings = json_decode($request->input('certificate_settings', '{}'), true);
        
        // If reset to default bg was triggered
        if (!empty($settings['use_default_bg'])) {
            if ($event->certificate_bg_image && $event->certificate_bg_image !== 'default_certificate_bg.jpg' && file_exists(public_path('images/events/' . $event->certificate_bg_image))) {
                @unlink(public_path('images/events/' . $event->certificate_bg_image));
            }
            $event->certificate_bg_image = 'default_certificate_bg.jpg';
            unset($settings['use_default_bg']);
        }
        
        $event->certificate_settings  = $settings;
        $event->certificate_orientation = $request->input('certificate_orientation', 'horizontal');
        $event->save();

        return redirect()->back()->with('success_message', 'Certificate settings saved successfully.');
    }

    public function sendCertificates(Request $request)
    {
        $participantIds = $request->input('participants', []);
        $eventId        = $request->input('event_id');

        if (empty($participantIds)) {
            if ($request->ajax()) return response()->json(['success' => false, 'message' => 'No participants selected.']);
            return redirect()->back()->with('error_message', 'No participants selected.');
        }

        $event = $this->model->findOrFail($eventId);
        if (!$event->certificate_bg_image || empty($event->certificate_settings)) {
            if ($request->ajax()) return response()->json(['success' => false, 'message' => 'Certificate template is not configured properly.']);
            return redirect()->back()->with('error_message', 'Certificate template is not configured properly.');
        }

        $localPath = public_path('images/events/' . $event->certificate_bg_image);
        $bgSource  = file_exists($localPath) ? $localPath : asset('images/events/' . $event->certificate_bg_image);

        $participants = \App\Models\EventBookingParticipant::with('booking', 'booking.cooperative')
            ->whereIn('id', $participantIds)->get();

        $allSettings = $event->certificate_settings ?? [];
        if (empty($allSettings['with_designation'])) {
            $withDesignationSettings    = $allSettings;
            $withoutDesignationSettings = array_filter($allSettings, fn($f) => $f['key'] !== 'participant_designation');
        } else {
            $withDesignationSettings    = $allSettings['with_designation'] ?? [];
            $withoutDesignationSettings = $allSettings['without_designation'] ?? [];
        }

        try {
            $sentCount = 0;
            $chunks = $participants->chunk(20);
            foreach ($chunks as $chunk) {
                \App\Jobs\SendCertificateEmailJob::dispatch(
                    $event, 
                    $chunk, 
                    $bgSource, 
                    $withDesignationSettings, 
                    $withoutDesignationSettings, 
                    $allSettings
                );
                $sentCount += $chunk->count();
            }
            if ($request->ajax()) return response()->json(['success' => true, 'message' => "$sentCount certificates queued for sending."]);
            return redirect()->back()->with('success_message', 'Certificates queued for sending successfully!');
        } catch (\Exception $e) {
            if ($request->ajax()) return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            return redirect()->back()->with('error_message', 'Error queuing certificates: ' . $e->getMessage());
        }
    }

    public function bulkDownloadCertificates(Request $request)
    {
        $participantIds = $request->input('participants', []);
        $eventId        = $request->input('event_id');

        if (empty($participantIds)) {
            return redirect()->back()->with('error_message', 'No participants selected.');
        }

        $event = $this->model->findOrFail($eventId);
        if (!$event->certificate_bg_image || empty($event->certificate_settings)) {
            return redirect()->back()->with('error_message', 'Certificate template is not configured properly.');
        }

        $localPath = public_path('images/events/' . $event->certificate_bg_image);
        $bgSource  = file_exists($localPath) ? $localPath : asset('images/events/' . $event->certificate_bg_image);

        $participants = \App\Models\EventBookingParticipant::with('booking', 'booking.cooperative')
            ->whereIn('id', $participantIds)->get();

        $allSettings = $event->certificate_settings ?? [];
        if (empty($allSettings['with_designation'])) {
            $withDesignationSettings    = $allSettings;
            $withoutDesignationSettings = array_filter($allSettings, fn($f) => $f['key'] !== 'participant_designation');
        } else {
            $withDesignationSettings    = $allSettings['with_designation'] ?? [];
            $withoutDesignationSettings = $allSettings['without_designation'] ?? [];
        }

        $zip = new \ZipArchive();
        $zipFileName = 'certificates_event_' . $eventId . '_' . time() . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($participants as $p) {
                $savePath = self::generateCertificateImage($event, $p, $bgSource, $withDesignationSettings, $withoutDesignationSettings, $allSettings);
                $zip->addFile($savePath, 'certificate_' . preg_replace('/[^A-Za-z0-9]/', '_', $p->name) . '_' . $p->id . '.jpg');
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function downloadCertificate($eventId, $participantId)
    {
        $event = $this->model->findOrFail($eventId);
        if (!$event->certificate_bg_image || empty($event->certificate_settings)) {
            return redirect()->back()->with('error_message', 'Certificate template is not configured properly.');
        }

        $localPath = public_path('images/events/' . $event->certificate_bg_image);
        $bgSource  = file_exists($localPath) ? $localPath : asset('images/events/' . $event->certificate_bg_image);

        $p = \App\Models\EventBookingParticipant::with('booking', 'booking.cooperative')->findOrFail($participantId);

        $allSettings = $event->certificate_settings ?? [];
        if (empty($allSettings['with_designation'])) {
            $withDesignationSettings    = $allSettings;
            $withoutDesignationSettings = array_filter($allSettings, fn($f) => $f['key'] !== 'participant_designation');
        } else {
            $withDesignationSettings    = $allSettings['with_designation'] ?? [];
            $withoutDesignationSettings = $allSettings['without_designation'] ?? [];
        }

        $savePath = self::generateCertificateImage($event, $p, $bgSource, $withDesignationSettings, $withoutDesignationSettings, $allSettings);

        return response()->download($savePath)->deleteFileAfterSend(true);
    }

    public static function generateCertificateImage($event, $p, $bgSource, $withDesignationSettings, $withoutDesignationSettings, $allSettings)
    {
        $orientation = $event->certificate_orientation ?? 'horizontal';
        // A4 at 300 DPI for HD print quality (3508 x 2480)
        $canvasW = $orientation === 'horizontal' ? 3508 : 2480;
        $canvasH = $orientation === 'horizontal' ? 2480 : 3508;

        $canvas = \Image::canvas($canvasW, $canvasH, '#ffffff');
        $bg = \Image::make($bgSource);

        $bgSizing = $allSettings['bg_sizing'] ?? 'stretch';

        if ($bgSizing === 'cover') {
            $bg->fit($canvasW, $canvasH);
            $canvas->insert($bg, 'center');
        } elseif ($bgSizing === 'contain') {
            $bg->resize($canvasW, $canvasH, function ($constraint) {
                $constraint->aspectRatio();
            });
            $canvas->insert($bg, 'center');
        } else {
            // Default: stretch to fill exactly
            $bg->resize($canvasW, $canvasH);
            $canvas->insert($bg, 'center');
        }

        $img = $canvas;
        $width = $canvasW;
        $height = $canvasH;

        $eventDateFormatted = 'N/A';
        if ($event->start_date) {
            if ($event->end_date && $event->start_date->format('Y-m-d') !== $event->end_date->format('Y-m-d')) {
                if ($event->start_date->format('Y') === $event->end_date->format('Y')) {
                    // Same year: "Aug 18 to Aug 19, 2026"
                    $eventDateFormatted = $event->start_date->format('M d') . ' to ' . $event->end_date->format('M d') . ', ' . $event->end_date->format('Y');
                } else {
                    // Different years: "Dec 30, 2025 to Jan 2, 2026"
                    $eventDateFormatted = $event->start_date->format('M d, Y') . ' to ' . $event->end_date->format('M d, Y');
                }
            } else {
                $eventDateFormatted = $event->start_date->format('M d, Y');
            }
        }

        $prefixGender = '';
        if ($p->gender === 'Male') {
            $prefixGender = 'Mr. ';
        } elseif ($p->gender === 'Female') {
            $prefixGender = 'Ms. ';
        }

        $dataMap = [
            'participant_name'        => $prefixGender . $p->name,
            'participant_designation' => $p->designation ?: '',
            'cooperative_name'        => $p->booking->is_member && $p->booking->cooperative
                                            ? $p->booking->cooperative->cooperative_name
                                            : ($p->booking->cooperative_name ?: 'N/A'),
            'cooperative_address'     => $p->booking->is_member && $p->booking->cooperative
                                            ? $p->booking->cooperative->address
                                            : 'N/A',
            'event_name'              => $event->title,
            'event_location'          => $event->getLocationName() ?: 'N/A',
            'event_start_date'        => $eventDateFormatted,
        ];

        $isWithDesignation = !empty($p->designation);
        $settingsToUse     = $isWithDesignation ? $withDesignationSettings : $withoutDesignationSettings;

        foreach ($settingsToUse as $setting) {
            if (!isset($dataMap[$setting['key']])) continue;

            $text = $dataMap[$setting['key']];
            if (trim((string)$text) === '') continue;

            // Clamp coordinates to valid range (0-99%) instead of skipping,
            // so fields always render on the certificate even if slightly off-edge.
            $xPct = max(0, min(99, (float)$setting['x']));
            $yPct = max(0, min(99, (float)$setting['y']));

            $scaleFactor = $width / 800;
            $cssPixels   = ($setting['size'] ?? 24) * $scaleFactor;
            $size        = $cssPixels; // Intervention Image natively uses pixels for GD size

            $x = ($xPct / 100) * $width;
            $y = ($yPct / 100) * $height;
            
            // CSS line-height offset: HTML div top vs GD TTF top ascender alignment.
            // HTML div top padding + line height places text ~70% of font size lower than raw bounding box top to match canvas design 1:1.
            $y += ($size * 0.70);

            $color      = $setting['color'] ?? '#000000';
            $globalFont = $allSettings['global_font'] ?? 'Roboto';
            $fontFamily = ($setting['font'] ?? 'global') === 'global' ? $globalFont : ($setting['font'] ?? $globalFont);
            $isBold     = !empty($setting['bold']);

            $textTransform = $setting['text_transform'] ?? 'none';
            if ($textTransform === 'uppercase')  $text = mb_strtoupper($text, 'UTF-8');
            elseif ($textTransform === 'capitalize') $text = mb_convert_case($text, MB_CASE_TITLE, 'UTF-8');
            elseif ($textTransform === 'lowercase')  $text = mb_strtolower($text, 'UTF-8');

            if (!empty($setting['prefix'])) {
                $text = $setting['prefix'] . $text;
            }
            if (!empty($setting['suffix'])) {
                $text = $text . $setting['suffix'];
            }

            $align = $setting['align'] ?? 'left';

            $fontFileMap = [
                'Roboto'          => $isBold ? 'Roboto-Bold.ttf'          : 'Roboto-Regular.ttf',
                'PlayfairDisplay' => $isBold ? 'PlayfairDisplay-Bold.ttf'  : 'PlayfairDisplay-Regular.ttf',
                'GreatVibes'      => 'GreatVibes-Regular.ttf',
                'Montserrat'      => $isBold ? 'Montserrat-Bold.ttf'       : 'Montserrat-Regular.ttf',
                'OpenSans'        => $isBold ? 'OpenSans-Bold.ttf'         : 'OpenSans-Regular.ttf',
                'Oswald'          => $isBold ? 'Oswald-Bold.ttf'           : 'Oswald-Regular.ttf',
                'DancingScript'   => $isBold ? 'DancingScript-Bold.ttf'    : 'DancingScript-Regular.ttf',
            ];
            $fontFile        = $fontFileMap[$fontFamily] ?? 'Roboto-Regular.ttf';
            $currentFontPath = realpath(public_path('fonts/' . $fontFile))
                            ?: realpath(public_path('fonts/Roboto-Regular.ttf'));

            $iconPref = $setting['icon'] ?? 'none';
            $hasIcon = ($iconPref === 'calendar' || $iconPref === 'location');
            $faFontPath = realpath(public_path('backend/assets/plugins/fontawesome-free/webfonts/fa-solid-900.ttf'));

            // Calculate icon width (matching canvas: icon takes roughly 1.2x font size of space)
            $iconWidth = 0;
            if ($hasIcon && $faFontPath && file_exists($faFontPath)) {
                $iconWidth = $size * 1.2;
            } else {
                $hasIcon = false;
            }

            // Measure the actual text width
            $bbox = imagettfbbox($size, 0, $currentFontPath, $text);
            $textWidth = abs($bbox[2] - $bbox[0]);
            $totalWidth = $textWidth + $iconWidth;

            // Auto-scale down if text overflows for left-aligned fields
            $maxAllowedWidth = ($width * 0.96);
            if ($align === 'left') {
                $maxAllowedWidth = $maxAllowedWidth - $x;
            }
            if ($totalWidth > $maxAllowedWidth && $maxAllowedWidth > 50) {
                $scale = $maxAllowedWidth / $totalWidth;
                $size = $size * $scale;
                if ($hasIcon) {
                    $iconWidth = $size * 1.2;
                }
                $bbox = imagettfbbox($size, 0, $currentFontPath, $text);
                $textWidth = abs($bbox[2] - $bbox[0]);
                $totalWidth = $textWidth + $iconWidth;
            }

            // DYNAMIC BOUNDARY CLAMPING: Prevent text from going off the certificate!
            $padding = $width * 0.02; // 2% padding from edges
            
            if ($align === 'center') {
                $leftEdge = $x - ($totalWidth / 2);
                $rightEdge = $x + ($totalWidth / 2);
                
                if ($leftEdge < $padding) {
                    $x = $padding + ($totalWidth / 2);
                } elseif ($rightEdge > ($width - $padding)) {
                    $x = ($width - $padding) - ($totalWidth / 2);
                }
            } elseif ($align === 'left') {
                if (($x + $totalWidth) > ($width - $padding)) {
                    $x = ($width - $padding) - $totalWidth;
                }
            } elseif ($align === 'right') {
                if (($x - $totalWidth) < $padding) {
                    $x = $padding + $totalWidth;
                }
            }

            // Draw text (no icon)
            if (!$hasIcon) {
                $img->text($text, $x, $y, function ($font) use ($currentFontPath, $size, $color, $align) {
                    $font->file($currentFontPath);
                    $font->size($size);
                    $font->color($color);
                    $font->align($align);
                    $font->valign('top');
                });
                continue;
            }

            // Draw with icon — icon color inherits text color (matches canvas)
            $drawX = $x;
            if ($align === 'center') {
                $drawX = $x - ($totalWidth / 2);
            } elseif ($align === 'right') {
                $drawX = $x - $totalWidth;
            }

            $iconChar = $iconPref === 'calendar' ? "\u{f133}" : "\u{f3c5}";
            $img->text($iconChar, $drawX, $y, function ($f) use ($faFontPath, $size, $color) {
                $f->file($faFontPath);
                $f->size($size);
                $f->color($color);
                $f->align('left');
                $f->valign('top');
            });
            $drawX += $iconWidth;

            $img->text($text, $drawX, $y, function ($font) use ($currentFontPath, $size, $color) {
                $font->file($currentFontPath);
                $font->size($size);
                $font->color($color);
                $font->align('left');
                $font->valign('top');
            });
        }

        $fileName = 'certificate_' . $p->id . '_' . time() . '.jpg';
        $savePath = storage_path('app/public/' . $fileName);
        $img->save($savePath, 100);

        return $savePath;
    }
}
