<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventBooking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EventBookingApiController extends Controller
{
    /**
     * Scan QR and mark attendance.
     * POST /api/event-bookings/scan
     * Requires: booking_id, token, admin auth (sanctum)
     */
    public function scan(Request $request): JsonResponse
    {



        // Admin must be authenticated via sanctum
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $admin = Auth::guard('sanctum')->user();
        if (!$admin->hasAnyRole(['super-dev', 'super-admin'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Admin access required.'], 403);
        }

        $request->validate([
            'booking_id'     => ['nullable', 'integer'],
            'token'          => ['nullable', 'string'],
            'participant_id' => ['nullable', 'integer'],
            'qr_data'        => ['nullable', 'string'],
            'event_id'       => ['nullable', 'integer'],
        ]);



        $participantId = $request->participant_id;
        $bookingId     = $request->booking_id;
        $token         = $request->token;

        // If raw qr_data is passed, try decoding JSON payload
        $qrDataString = $request->qr_data;

        if ($qrDataString) {
            if (strpos($qrDataString, '}{') !== false) {
                // Handle the concatenated double JSON string
                $fixedJson = '[' . str_replace('}{', '},{', $qrDataString) . ']';
                $decodedArray = json_decode($fixedJson, true);
                if (is_array($decodedArray)) {
                    foreach ($decodedArray as $dec) {
                        if (isset($dec['participant_id'])) {
                            $participantId = $dec['participant_id'];
                            $bookingId     = $dec['booking_id'] ?? $bookingId;
                            $token         = $dec['token'] ?? $token;
                        } elseif (!isset($participantId) && isset($dec['booking_id'])) {
                            $bookingId     = $dec['booking_id'];
                            $token         = $dec['token'] ?? $token;
                        }
                    }
                }
            } else {
                $decoded = json_decode($qrDataString, true);
                if (is_array($decoded)) {
                    $participantId = $decoded['participant_id'] ?? $participantId;
                    $bookingId     = $decoded['booking_id'] ?? $bookingId;
                    $token         = $decoded['token'] ?? $token;
                } else {
                    $token = $qrDataString; // fallback if plain token string was passed
                }
            }
        }

        $booking = null;
        $participant = null;

        if ($participantId) {
            $participant = \App\Models\EventBookingParticipant::with('booking.event')
                ->where('id', $participantId)
                ->where('token', $token)
                ->first();

            if (!$participant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid QR code. Participant not found.',
                ], 422);
            }
            $booking = $participant->booking;
        } else {
            $booking = EventBooking::with(['event', 'participants', 'routine'])
                ->where('id', $bookingId)
                ->where('booking_token', $token)
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid QR code. Booking not found.',
                ], 422);
            }
        }

        if ($request->filled('event_id') && $booking->event_id != $request->event_id) {
            return response()->json([
                'success' => false,
                'message' => 'This ticket is for a different event: ' . ($booking->event?->title ?? 'Unknown Event'),
            ], 422);
        }

        if ($booking->status !== EventBooking::STATUS_APPROVED) {
            return response()->json([
                'success' => false,
                'message' => 'Booking is not approved.',
            ], 422);
        }


        // If booking has > 1 participant, DO NOT auto-present on scan.
        // Return the booking so the scanner app can manually check them in.
        // If it's a general booking scan with multiple participants, return group selection.
        // if ($booking->participants()->count() > 1 && !$participant) {
        //     return response()->json([
        //         'success' => true,
        //         'type' => 'group',
        //         'message' => 'Group booking scanned. Please select participants to mark present.',
        //         'booking' => $this->formatBooking($booking),
        //     ]);
        // }



        // Auto-present the participant
        $targetParticipant = $participant ?: $booking->participants->first();

        $today = \Carbon\Carbon::now()->format('Y-m-d');
        $attDates = $targetParticipant->attendance_dates ?: [];
        $isPresentToday = in_array($today, $attDates);

        if ($isPresentToday) {
            return response()->json([
                'success' => false,
                'message' => 'Participant is already marked as present for today.',
                'booking' => $this->formatBooking($booking),
                'participant' => [
                    'id'         => $targetParticipant->id,
                    'name'       => $targetParticipant->name,
                    'is_present' => true,
                ]
            ], 422);
        }

        \DB::beginTransaction();
        try {
            $attDates[] = $today;
            $targetParticipant->update([
                'is_present' => 1,
                'attendance_dates' => $attDates
            ]);
            $booking->update(['is_present' => 1]);
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Database error recording attendance.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded successfully.',
            'booking' => $this->formatBooking($booking),
            'participant' => [
                'id'         => $targetParticipant->id,
                'name'       => $targetParticipant->name,
                'is_present' => true,
            ]
        ]);
    }

    /**
     * Mark a specific participant as present (by clicking in mobile app).
     * POST /api/event-booking/present/{token}
     */
    public function markParticipantPresent(Request $request, $token): JsonResponse
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $admin = Auth::guard('sanctum')->user();
        if (!$admin->hasAnyRole(['super-dev', 'super-admin'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Admin access required.'], 403);
        }

        $participant = \App\Models\EventBookingParticipant::with('booking')->where('token', $token)->first();

        if (!$participant) {
            return response()->json(['success' => false, 'message' => 'Participant not found.'], 404);
        }

        $booking = $participant->booking;
        if (!$booking || $booking->status !== \App\Models\EventBooking::STATUS_APPROVED) {
            return response()->json(['success' => false, 'message' => 'Booking is not approved.'], 422);
        }

        $today = \Carbon\Carbon::now()->format('Y-m-d');
        $attDates = $participant->attendance_dates ?: [];
        $isPresentToday = in_array($today, $attDates);

        if ($isPresentToday) {
            return response()->json([
                'success' => true,
                'message' => 'Participant is already marked as present for today.',
                'participant' => [
                    'id'         => $participant->id,
                    'name'       => $participant->name,
                    'is_present' => true,
                ],
                'booking' => $this->formatBooking($booking),
            ]);
        }

        \DB::beginTransaction();
        try {
            $attDates[] = $today;
            $participant->update([
                'is_present' => 1,
                'attendance_dates' => $attDates
            ]);

            // Auto-mark booking as present if all participants are checked in
            if (!$booking->participants()->where('is_present', 0)->exists()) {
                $booking->update(['is_present' => 1]);
            }
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Database error recording attendance.'], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Participant marked as present successfully.',
            'participant' => [
                'id'         => $participant->id,
                'name'       => $participant->name,
                'is_present' => true,
            ],
            'booking' => $this->formatBooking($booking),
        ]);
    }

    /**
     * List all active events for mobile scanner.
     * GET /api/events
     */
    public function events(Request $request): JsonResponse
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $admin = Auth::guard('sanctum')->user();
        if (!$admin->hasAnyRole(['super-dev', 'super-admin'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Admin access required.'], 403);
        }

        $query = \App\Models\Event::active()->with(['bookings' => function ($q) {
            $q->where('status', \App\Models\EventBooking::STATUS_APPROVED)->with('participants');
        }]);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $events = $query->orderBy('created_at', 'desc')->get()->map(function ($event) {
            $approvedParticipants = $event->bookings->flatMap->participants;
            $totalParticipants = $approvedParticipants->count();
            $present = $approvedParticipants->where('is_present', 1)->count();
            $absent = $totalParticipants - $present;

            return [
                'id' => $event->id,
                'title' => $event->title,
                'start_date' => $event->start_date?->toDateString(),
                'start_date_bs' => $event->start_date_bs,
                'capacity' => $event->total_seats,
                'available_seats' => $event->availableSeats(),
                'total_participants' => $totalParticipants,
                'present' => $present,
                'absent' => $absent
            ];
        });

        return response()->json(['success' => true, 'events' => $events]);
    }

    /**
     * Get booking info by token (for mobile scan preview).
     * GET /api/event-bookings/{token}
     * Requires admin sanctum auth.
     */
    public function show(Request $request, string $token): JsonResponse
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $admin = Auth::guard('sanctum')->user();
        if (!$admin->hasAnyRole(['super-dev', 'super-admin'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Admin access required.'], 403);
        }

        $booking = EventBooking::with(['event', 'participants', 'routine'])
            ->where('booking_token', $token)
            ->first();

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        return response()->json([
            'success' => true,
            'booking' => $this->formatBooking($booking),
        ]);
    }

    /**
     * Admin login for mobile app.
     * POST /api/admin/login
     */
    public function adminLogin(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }

        $user  = Auth::user();
        if (!$user->hasAnyRole(['super-dev', 'super-admin'])) {
            Auth::logout();
            return response()->json(['success' => false, 'message' => 'Unauthorized. Admin access required.'], 403);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    /**
     * List all bookings for an event (admin API).
     * GET /api/events/{eventId}/bookings
     */
    public function eventBookings(Request $request, int $eventId): JsonResponse
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $admin = Auth::guard('sanctum')->user();
        if (!$admin->hasAnyRole(['super-dev', 'super-admin'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Admin access required.'], 403);
        }

        $bookings = EventBooking::with(['participants', 'routine'])
            ->where('event_id', $eventId)
            ->get()
            ->map(fn($b) => $this->formatBooking($b));

        return response()->json(['success' => true, 'bookings' => $bookings]);
    }

    /* ----------------------------------------------------------------- */
    /**
     * Get site settings details (public info).
     * GET /api/site-info
     */
    public function siteInfo(): JsonResponse
    {
        $siteSetting = \App\Models\SiteSetting::first();
        if (!$siteSetting) {
            return response()->json(['success' => false, 'message' => 'Site settings not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'title'            => $siteSetting->title,
                'sub_title'        => $siteSetting->sub_title,
                'email'            => $siteSetting->email,
                'phone'            => $siteSetting->phone,
                'location'         => $siteSetting->location,
                'company_alias'    => $siteSetting->company_alias,
                'pan_no'           => $siteSetting->pan_no,
                'reg_no'           => $siteSetting->reg_no,
                'copyright_text'   => $siteSetting->copyright_text,
                'site_logo'        => $siteSetting->site_logo ? \App\Facades\ViewHelper::getImagePath('siteSetting', $siteSetting->site_logo) : null,
                'company_logo'     => $siteSetting->company_logo ? \App\Facades\ViewHelper::getImagePath('siteSetting', $siteSetting->company_logo) : null,
                'site_favicon'     => $siteSetting->site_favicon ? \App\Facades\ViewHelper::getImagePath('siteSetting', $siteSetting->site_favicon) : null,
                'powered_by'       => $siteSetting->powered_by,
                'powered_by_link'  => $siteSetting->powered_by_link,
            ]
        ]);
    }

    /* ----------------------------------------------------------------- */
    private function formatBooking(EventBooking $booking): array
    {
        return [
            'id'                 => $booking->id,
            'booking_token'      => $booking->booking_token,
            'event'              => $booking->event?->title,
            'routine_time'       => ($booking->routine?->start_time ?? '') . ' – ' . ($booking->routine?->end_time ?? ''),
            'total_participants' => $booking->total_participants,
            'total_amount'       => $booking->total_amount,
            'status'             => $booking->status_label,
            'is_present'         => (bool) $booking->is_present,
            'contact_email'      => $booking->contact_email,
            'contact_phone'      => $booking->contact_phone,
            'participants'       => $booking->participants->map(fn($p) => [
                'id'               => $p->id,
                'name'             => $p->name,
                'dob_bs'           => $p->dob_bs,
                'phone'            => $p->phone,
                'email'            => $p->email,
                'gender'           => $p->gender,
                'is_present'       => (bool) $p->is_present,
                'present_api_link' => url('/api/event-booking/present/' . $p->token),
            ]),
        ];
    }
}
