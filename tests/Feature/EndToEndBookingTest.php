<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\EventRoutine;
use App\Models\EventBooking;
use App\Models\EventBookingParticipant;
use App\Models\CooperativeInformation;
use Illuminate\Support\Facades\Mail;

class EndToEndBookingTest extends TestCase
{
    // Use RefreshDatabase if we want a clean DB, but since we want to test in the current DB, we won't use it.
    // Instead we will just create data and optionally clean it up.
    
    public function test_end_to_end_booking_flow()
    {
        Mail::fake();

        // 1. Create or get Admin
        $admin = User::firstOrCreate(
            ['email' => 'dev@bagmati.com'],
            ['name' => 'Admin', 'password' => bcrypt('dev@bagmati.com'), 'phone' => '9876543210']
        );
        // Ensure the admin has permission to manage events if required.
        // Assuming they can access admin routes.

        // 2. Create or get User
        $user = User::firstOrCreate(
            ['email' => 'sesemi0129@gmail.com'],
            ['name' => 'Test User', 'password' => bcrypt('password'), 'phone' => '1234567890']
        );
        
        // 3. Create Cooperative if not exists
        $coop = CooperativeInformation::firstOrCreate(
            ['member_no' => '9999'],
            ['cooperative_name' => 'Test Coop', 'rank' => 1, 'status' => 1, 'address' => 'Test Address']
        );

        $this->actingAs($admin);

        // 4. Create an event with routines via POST request to admin endpoint
        $title = 'E2E Test Multi-Day Event ' . uniqid();
        $response = $this->post(route('admin.event.store'), [
            'title' => $title,
            'total_seats' => 50,
            'event_price' => 2000,
            'price_type' => 'per_person',
            'start_date' => now()->addDays(5)->format('Y-m-d'),
            'status' => 1,
            'routines' => [
                [
                    'date' => now()->addDays(5)->format('Y-m-d'),
                    'is_all_day' => 0,
                    'start_time' => '10:00',
                    'end_time' => '12:00',
                    'about' => 'Morning Session',
                    'price' => 500,
                ],
                [
                    'date' => now()->addDays(6)->format('Y-m-d'),
                    'is_all_day' => 1,
                    'start_time' => '00:00',
                    'end_time' => '23:59',
                    'about' => 'Full Day Workshop',
                    'price' => 1500,
                ]
            ]
        ]);
        $response->assertRedirect(route('admin.event.index'));
        
        $event = Event::where('title', $title)->orderBy('id', 'desc')->first();
        $this->assertNotNull($event);
        $this->assertEquals(2, $event->routines()->count());
        
        $routineId = $event->routines->first()->id;
        // 5. Login as User and Book the Event
        $this->actingAs($user);
        
        // Step 1: Select Routine and participants
        $response = $this->post(route('events.book.postStep1', $event->slug), [
            'event_routine_selection' => $event->routines->first()->id . '_single',
            'total_participants' => 2,
            'is_member' => 1,
            'cooperative_id' => $coop->id,
        ]);
        $response->assertRedirect(route('events.book.step2', $event->slug));
        
        // Step 2: Provide participant details
        $response = $this->post(route('events.book.postStep2', $event->slug), [
            'contact_phone' => '1234567890',
            'contact_email' => 'sesemi0129@gmail.com',
            'participants' => [
                [
                    'name' => 'John Doe',
                    'phone' => '1234567890',
                    'email' => 'john@example.com',
                    'dob_bs' => '2050-01-01',
                    'designation' => 'Manager'
                ],
                [
                    'name' => 'Jane Doe',
                    'phone' => '0987654321',
                    'email' => 'jane@example.com',
                    'dob_bs' => '2051-01-01',
                    'designation' => 'Staff'
                ]
            ]
        ]);
        $response->assertRedirect(route('events.book.step3', $event->slug));
        
        // Step 3: Confirm booking
        $response = $this->post(route('events.book.store', $event->slug), [
            'payment_method' => 'cash',
            'payment_voucher' => \Illuminate\Http\UploadedFile::fake()->image('voucher.jpg'),
        ]);
        
        $booking = EventBooking::where('event_id', $event->id)->where('user_id', $user->id)->first();
        $this->assertNotNull($booking);
        $this->assertEquals(0, $booking->status);
        $this->assertEquals(2, $booking->participants()->count());
        $response->assertRedirect(route('events.booking.success', $booking->booking_token));

        // 6. Admin Approves Booking
        $this->actingAs($admin);
        $response = $this->post(route('admin.event.booking.approve', $booking->id));
        $response->assertRedirect();
        
        $booking->refresh();
        $this->assertEquals(1, $booking->status);
        $this->assertNotNull($booking->qr_code);

        // 7. Admin sends Mail
        $response = $this->post(route('admin.event.booking.send-mail', $booking->id));
        $response->assertRedirect();
        
        // Assert email was sent
        Mail::assertSent(\App\Mail\EventBookingMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
        
        echo "E2E Test Passed: Created event, booked by user, approved by admin, and sent email.\n";
    }
}
