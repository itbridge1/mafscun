<?php

namespace App\Http\Controllers\Website;

use App\Models\EventBooking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DashboardController extends BaseController
{
    public function index()
    {
        $user = auth()->user();

        // Auto-cleanup user's expired drafts and convert them to rejected
        EventBooking::where('user_id', $user->id)
            ->where('status', EventBooking::STATUS_DRAFT)
            ->where('created_at', '<', now()->subMinutes(15))
            ->update([
                'status' => EventBooking::STATUS_REJECTED,
                'rejection_note' => 'Reservation expired due to timeout (Filling failed)'
            ]);

        // Fetch bookings matching user's phone or email
        $bookings = EventBooking::where(function ($q) use ($user) {
            if ($user->email) {
                $q->orWhere('contact_email', $user->email);
            }
            if ($user->phone) {
                $q->orWhere('contact_phone', $user->phone);
            }
        })
        ->with(['event', 'routine', 'participants'])
        ->latest()
        ->get();

        return view($this->loadCommonDataToView('website.dashboard.index'), compact('user', 'bookings'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view($this->loadCommonDataToView('website.dashboard.profile'), compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'first_name'  => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name'   => ['required', 'string', 'max:100'],
            'email'    => ['nullable', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone'    => ['nullable', 'string', 'max:20', 'unique:users,phone,' . $user->id],
            'address'  => ['nullable', 'string', 'max:255'],
            'dob_bs'   => ['nullable', 'string', 'max:10', 'regex:/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/'],
            'photo'    => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $data = [
            'first_name'  => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name'   => $request->last_name,
            'name'    => null, // Set null as name is replaced by parts
            'phone'   => $request->phone,
            'address' => $request->address,
            'dob_bs'  => $request->dob_bs,
        ];

        // Email update logic:
        // - If user logged in via email (has email but no phone), email is immutable — skip.
        // - If user logged in via phone and wants to add/change email, must be OTP-verified in this request.
        //   OR if the submitted email is the same as the one already stored (no change).
        if (!($user->email && !$user->phone)) {
            $newEmail = $request->email;
            if ($newEmail) {
                $emailVerified = $request->input('email_verified') === '1';
                $emailUnchanged = ($newEmail === $user->email);
                if ($emailVerified || $emailUnchanged) {
                    $data['email'] = $newEmail;
                }
                // If email changed but not verified, silently ignore the change
            } else {
                $data['email'] = null;
            }
        }

        // Phone update logic:
        // - If user logged in via phone (has phone but no email), phone is immutable — skip.
        // - If user logged in via email and wants to add/change phone, must be OTP-verified in this request.
        //   OR if the submitted phone is the same as the one already stored (no change).
        if (!($user->phone && !$user->email)) {
            $newPhone = $request->phone;
            if ($newPhone) {
                $phoneVerified = $request->input('phone_verified') === '1';
                $phoneUnchanged = ($newPhone === $user->phone);
                if ($phoneVerified || $phoneUnchanged) {
                    $data['phone'] = $newPhone;
                }
                // If phone changed but not verified, silently ignore
            } else {
                $data['phone'] = null;
            }
        }

        // Handle profile photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && file_exists(public_path('images/user/' . $user->photo))) {
                @unlink(public_path('images/user/' . $user->photo));
            }

            $file = $request->file('photo');
            $fileName = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/user'), $fileName);
            $data['photo'] = $fileName;
        }

        // Handle password update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('user.profile')->with('success_message', 'Profile updated successfully!');
    }

    public function downloadCard($id)
    {
        $participant = \App\Models\EventBookingParticipant::with('booking.event')->findOrFail($id);
        
        $user = auth()->user();
        if ($participant->booking->user_id !== $user->id && $participant->booking->contact_email !== $user->email && $participant->booking->contact_phone !== $user->phone) {
            abort(403, 'Unauthorized');
        }

        // Auto-heal missing QR code (avoids Imagick requirements completely)
        if (empty($participant->qr_code)) {
            try {
                $participant->update(['qr_code' => $participant->generateQrCodeBase64()]);
            } catch (\Exception $e) {
                \Log::error('Dynamic QR generation failed on downloadCard: ' . $e->getMessage());
            }
        }

        $siteSetting = \App\Models\SiteSetting::first();
        
        return view($this->loadCommonDataToView('website.dashboard.card'), compact('participant', 'siteSetting'));
    }

    public function downloadBookingCards($id)
    {
        $booking = \App\Models\EventBooking::with(['participants', 'event', 'user'])->findOrFail($id);
        
        $user = auth()->user();
        if ($booking->user_id !== $user->id && $booking->contact_email !== $user->email && $booking->contact_phone !== $user->phone) {
            abort(403, 'Unauthorized');
        }

        // Auto-heal missing QR codes for all participants
        foreach ($booking->participants as $participant) {
            if (empty($participant->qr_code)) {
                try {
                    $participant->update(['qr_code' => $participant->generateQrCodeBase64()]);
                } catch (\Exception $e) {
                    \Log::error('Dynamic QR generation failed on downloadBookingCards: ' . $e->getMessage());
                }
            }
        }

        $siteSetting = \App\Models\SiteSetting::first();
        
        return view($this->loadCommonDataToView('website.dashboard.booking_cards'), compact('booking', 'siteSetting'));
    }

    public function reapplyBooking($id)
    {
        $booking = \App\Models\EventBooking::with('participants')->where('user_id', auth()->id())->findOrFail($id);

        if ($booking->status !== \App\Models\EventBooking::STATUS_REJECTED) {
            return redirect()->back()->with('error_message', 'Only rejected bookings can be reapplied.');
        }

        // Populate session
        session([
            'booking_is_member'          => $booking->is_member,
            'booking_cooperative_id'     => $booking->cooperative_id,
            'booking_cooperative_name'   => $booking->cooperative_name,
            'booking_total_participants' => $booking->total_participants,
            'booking_routines'           => $booking->selected_routines,
            'booking_contact_phone'      => $booking->contact_phone,
            'booking_contact_email'      => $booking->contact_email,
        ]);

        $participants = [];
        foreach ($booking->participants as $p) {
            $participants[] = [
                'name'        => $p->name,
                'dob_bs'      => $p->dob_bs,
                'phone'       => $p->phone,
                'email'       => $p->email,
                'gender'      => $p->gender,
                'designation' => $p->designation,
            ];
        }
        session(['booking_participants' => $participants]);

        // Delete the old rejected booking so it's fully replaced by the new application.
        // Also remove the old voucher if it exists.
        if ($booking->payment_voucher && file_exists(public_path('images/vouchers/' . $booking->payment_voucher))) {
            @unlink(public_path('images/vouchers/' . $booking->payment_voucher));
        }
        $booking->delete();

        return redirect()->route('events.book.step2', $booking->event->slug);
    }
}
