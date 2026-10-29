@extends('website.common.layout')
@section('title', 'My Account & Bookings')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Dashboard Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-body p-4 text-center">
                    <div class="mb-3 position-relative d-inline-block">
                        @if($user->photo)
                            <img src="{{ asset('images/user/' . $user->photo) }}" class="rounded-circle shadow-sm border" style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="bg-success text-white rounded-circle shadow-sm d-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px; font-size: 2.5rem; font-weight: bold;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <h5 class="font-weight-bold mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small mb-3">
                        @if($user->phone)
                            <i class="fas fa-phone-alt mr-1"></i> {{ $user->phone }}
                        @else
                            <i class="fas fa-envelope mr-1"></i> {{ $user->email }}
                        @endif
                    </p>
                    <hr>
                    <div class="list-group list-group-flush text-left font-weight-bold">
                        <a href="{{ route('user.dashboard') }}" class="list-group-item list-group-item-action border-0 py-3 text-success active" style="background-color: rgba(40,167,69,0.08); border-radius: 8px;">
                            <i class="fas fa-ticket-alt mr-2"></i> My Bookings
                        </a>
                        <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action border-0 py-3 text-dark">
                            <i class="fas fa-user-edit mr-2"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="col-lg-9">
            <!-- Metrics Row -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm border-0 bg-success text-white">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 text-uppercase tracking-wider font-weight-bold mb-1" style="font-size: 0.75rem;">Total Bookings</h6>
                                    <h3 class="font-weight-bold mb-0">{{ $bookings->count() }}</h3>
                                </div>
                                <i class="fas fa-history fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm border-0 bg-info text-white">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 text-uppercase tracking-wider font-weight-bold mb-1" style="font-size: 0.75rem;">Approved Tickets</h6>
                                    <h3 class="font-weight-bold mb-0">{{ $bookings->where('status', \App\Models\EventBooking::STATUS_APPROVED)->count() }}</h3>
                                </div>
                                <i class="fas fa-check-circle fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm border-0 bg-warning text-white">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 text-uppercase tracking-wider font-weight-bold mb-1" style="font-size: 0.75rem;">Pending Approval</h6>
                                    <h3 class="font-weight-bold mb-0">{{ $bookings->where('status', \App\Models\EventBooking::STATUS_PENDING)->count() }}</h3>
                                </div>
                                <i class="fas fa-hourglass-half fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking List Card -->
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-clipboard-list mr-1"></i> Your Booking History</h5>
                </div>
                <div class="card-body p-4">
                    @if(session('success_message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-1"></i> {{ session('success_message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($bookings->isEmpty())
                        <div class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/2767/2767146.png" style="width: 80px; opacity: 0.5;" class="mb-3">
                            <h5 class="text-muted">You haven't booked any events yet.</h5>
                            <a href="{{ route('events.index') }}" class="btn btn-success font-weight-bold px-4 mt-3">Browse Events</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-uppercase small text-muted">
                                    <tr>
                                        <th>SN</th>
                                        <th>Event Details</th>
                                        <th>Routine</th>
                                        <th>Tickets</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $idx => $booking)
                                        <tr>
                                            <td class="align-middle font-weight-bold">#{{ $idx + 1 }}</td>
                                            <td class="align-middle">
                                                <strong class="text-dark d-block mb-1">{{ $booking->event->title }}</strong>
                                                <small class="text-muted"><i class="far fa-calendar-alt mr-1"></i> Token: {{ substr($booking->booking_token, 0, 8) }}...</small>
                                            </td>
                                            <td class="align-middle">
                                                @if($booking->routine)
                                                    <span class="badge badge-light border text-dark font-weight-normal py-1 px-2">
                                                        <i class="far fa-clock text-success mr-1"></i> 
                                                        {{ $booking->routine->display_schedule }}
                                                    </span>
                                                @else
                                                    <span class="text-muted small">General Session</span>
                                                @endif
                                            </td>
                                            <td class="align-middle font-weight-bold text-center">
                                                {{ $booking->total_participants }}
                                            </td>
                                            <td class="align-middle text-success font-weight-bold">
                                                Rs. {{ number_format($booking->total_amount, 2) }}
                                            </td>
                                            <td class="align-middle">
                                                @if($booking->status == \App\Models\EventBooking::STATUS_APPROVED)
                                                    <span class="badge badge-success px-3 py-2"><i class="fas fa-check-circle mr-1"></i> Approved</span>
                                                @elseif($booking->status == \App\Models\EventBooking::STATUS_REJECTED)
                                                    <span class="badge badge-danger px-3 py-2"><i class="fas fa-times-circle mr-1"></i> Rejected</span>
                                                    @if($booking->rejection_note)
                                                        <div class="mt-2 small text-danger" style="max-width: 150px; line-height: 1.2;">
                                                            <strong>Reason:</strong> {{ $booking->rejection_note }}
                                                        </div>
                                                    @endif
                                                @elseif($booking->status == \App\Models\EventBooking::STATUS_DRAFT)
                                                    <span class="badge badge-secondary px-3 py-2"><i class="fas fa-edit mr-1"></i> Draft</span>
                                                    <div class="mt-1 small font-weight-bold">
                                                        @php
                                                            $diffInMins = now()->diffInMinutes($booking->created_at);
                                                            $timeLeft = 15 - $diffInMins;
                                                        @endphp
                                                        @if($timeLeft > 0)
                                                            <span class="text-warning"><i class="fa fa-clock"></i> {{ $timeLeft }} min(s) left</span>
                                                        @else
                                                            <span class="text-danger"><i class="fa fa-times-circle"></i> Expired</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="badge badge-warning text-dark px-3 py-2"><i class="fas fa-clock mr-1"></i> Pending</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-right">
                                                    @if($booking->status == \App\Models\EventBooking::STATUS_DRAFT && isset($timeLeft) && $timeLeft > 0)
                                                        <a href="{{ route('events.booking.continue', $booking->id) }}" class="btn btn-sm btn-outline-primary font-weight-bold mr-2">
                                                            <i class="fas fa-arrow-right mr-1"></i> Continue
                                                        </a>
                                                    @endif
                                                    @if($booking->status == \App\Models\EventBooking::STATUS_PENDING && !$booking->payment_voucher)
                                                        <button class="btn btn-sm btn-outline-warning font-weight-bold mr-2" data-toggle="modal" data-target="#uploadVoucherModal{{ $booking->id }}">
                                                            <i class="fas fa-upload mr-1"></i> Upload Voucher
                                                        </button>
                                                    @endif
                                                    @if($booking->status == \App\Models\EventBooking::STATUS_REJECTED)
                                                        <a href="{{ route('user.booking.reapply', $booking->id) }}" class="btn btn-sm btn-outline-danger font-weight-bold mr-2">
                                                            <i class="fas fa-redo-alt mr-1"></i> Re-apply
                                                        </a>
                                                    @endif
                                                    @if($booking->status == \App\Models\EventBooking::STATUS_APPROVED)
                                                        <a href="{{ route('user.booking.cards', $booking->id) }}" target="_blank" class="btn btn-sm btn-primary font-weight-bold mr-2">
                                                            <i class="fas fa-print mr-1"></i> Print Entry Cards
                                                        </a>
                                                    @endif
                                                    @if($booking->status != \App\Models\EventBooking::STATUS_DRAFT)
                                                        <a href="{{ route('events.booking.success', $booking->booking_token) }}" class="btn btn-sm btn-outline-success font-weight-bold">
                                                            <i class="fas fa-receipt mr-1"></i> Details
                                                        </a>
                                                    @endif
                                                </div>

                                                <!-- Upload Voucher Modal -->
                                                @if($booking->status == \App\Models\EventBooking::STATUS_PENDING && !$booking->payment_voucher)
                                                    <div class="modal fade text-left" id="uploadVoucherModal{{ $booking->id }}" tabindex="-1" role="dialog" aria-labelledby="uploadVoucherModalLabel{{ $booking->id }}" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <form action="{{ route('events.booking.upload-voucher', $booking->booking_token) }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-success text-white">
                                                                        <h5 class="modal-title" id="uploadVoucherModalLabel{{ $booking->id }}"><i class="fas fa-file-upload"></i> Upload Payment Voucher</h5>
                                                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body p-4">
                                                                        <div class="form-group mb-0">
                                                                            <label class="font-weight-bold text-dark">Select Payment Voucher Image/PDF <span class="text-danger">*</span></label>
                                                                            <input type="file" name="payment_voucher" class="form-control-file" required>
                                                                            <small class="form-text text-muted">Allowed types: jpeg, png, jpg, pdf. Max size: 4MB.</small>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-success font-weight-bold"><i class="fas fa-cloud-upload-alt"></i> Submit Voucher</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
