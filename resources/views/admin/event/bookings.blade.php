@extends('admin.layout.frame')
@section('title', 'Event Bookings')
@section('css')
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6"><h1>Bookings for: {{ $data['event']->title }}</h1></div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ $dashboard_url }}"><i class="fa fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.event.index') }}">Events</a></li>
                            <li class="breadcrumb-item active">Bookings</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <!-- Dashboard Metrics Row -->
                <div class="row">
                    <!-- Approved Bookings -->
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $data['stats']['approved_bookings'] }}</h3>
                                <p>Approved Bookings</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                    <!-- Total Approved Participants -->
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $data['stats']['total_participants_approved'] }}</h3>
                                <p>Approved Participants</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    <!-- Total Present -->
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $data['stats']['present_bookings'] }}</h3>
                                <p>Total Attended (Present)</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                    <!-- Attendance Rate % -->
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner text-dark">
                                <h3>{{ $data['stats']['attendance_rate'] }}%</h3>
                                <p class="text-dark">Attendance Rate</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card card-outline card-primary mb-2">
                            <div class="card-body p-2 d-flex align-items-center">
                                <span class="text-bold mr-3"><i class="fa fa-filter"></i> Filter Bookings:</span>
                                <a href="{{ route('admin.event.bookings', [$data['event']->id]) }}" class="btn btn-sm {{ request('status') === null ? 'btn-primary' : 'btn-outline-primary' }} mr-2">
                                    All ({{ $data['stats']['total_bookings'] }})
                                </a>
                                <a href="{{ route('admin.event.bookings', [$data['event']->id, 'status' => \App\Models\EventBooking::STATUS_APPROVED]) }}" class="btn btn-sm {{ request('status') == \App\Models\EventBooking::STATUS_APPROVED ? 'btn-success' : 'btn-outline-success' }} mr-2">
                                    Approved Only ({{ $data['stats']['approved_bookings'] }})
                                </a>
                                <a href="{{ route('admin.event.bookings', [$data['event']->id, 'status' => \App\Models\EventBooking::STATUS_PENDING]) }}" class="btn btn-sm {{ request('status') == \App\Models\EventBooking::STATUS_PENDING ? 'btn-warning text-dark' : 'btn-outline-warning' }} mr-2">
                                    Pending Only ({{ $data['stats']['pending_bookings'] }})
                                </a>
                                <a href="{{ route('admin.event.bookings', [$data['event']->id, 'status' => \App\Models\EventBooking::STATUS_REJECTED]) }}" class="btn btn-sm {{ request('status') == \App\Models\EventBooking::STATUS_REJECTED ? 'btn-danger' : 'btn-outline-danger' }} mr-2">
                                    Rejected Only ({{ $data['stats']['rejected_bookings'] }})
                                </a>
                                <a href="{{ route('admin.event.bookings', [$data['event']->id, 'status' => \App\Models\EventBooking::STATUS_DRAFT]) }}" class="btn btn-sm {{ request('status') == \App\Models\EventBooking::STATUS_DRAFT ? 'btn-secondary' : 'btn-outline-secondary' }}">
                                    Drafts ({{ $data['stats']['draft_bookings'] }})
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title text-bold mb-0">Bookings List</h3>
                                <div class="ml-auto">
                                    @if($data['stats']['approved_bookings'] > 0)
                                        <a href="{{ route('admin.event.participants', $data['event']->id) }}" class="btn btn-sm btn-info font-weight-bold mr-2">
                                            <i class="fa fa-users"></i> View Participants List
                                        </a>
                                        <a href="{{ route('admin.event.bookings.bulk-cards', $data['event']->id) }}" target="_blank" class="btn btn-sm btn-success font-weight-bold mr-2">
                                            <i class="fa fa-print"></i> Print All Approved Cards ({{ $data['stats']['approved_bookings'] }})
                                        </a>
                                        <button type="button" class="btn btn-sm btn-warning text-dark font-weight-bold mr-2" data-toggle="modal" data-target="#sendMessageModal">
                                            <i class="fa fa-envelope"></i> Send Message to Approved ({{ $data['stats']['approved_bookings'] }})
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addBookingModal">
                                        <i class="fa fa-plus"></i> Add Booking Manually
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped text-sm">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Booked By</th>
                                            <th>Token</th>
                                            <th>Contact</th>
                                            <th>Routine</th>
                                            <th>Participants</th>
                                            <th>Amount</th>
                                            <th>Voucher</th>
                                            <th>Status</th>
                                            <th>Presence</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data['bookings'] as $key => $booking)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                @if($booking->user)
                                                    <div class="text-sm">
                                                        <strong>{{ $booking->user->name }}</strong><br>
                                                        @if($booking->user->phone)
                                                            <span class="text-muted"><i class="fas fa-phone fa-xs"></i> {{ $booking->user->phone }}</span><br>
                                                        @endif
                                                        @if($booking->user->email)
                                                            <span class="text-muted"><i class="fas fa-envelope fa-xs"></i> {{ $booking->user->email }}</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="badge badge-secondary">Guest</span>
                                                @endif
                                            </td>
                                            <td>{{ substr($booking->booking_token, 0, 8) }}...</td>
                                            <td>
                                                {{ $booking->contact_email }}<br>
                                                {{ $booking->contact_phone }}
                                            </td>
                                            <td>
                                                @if($booking->routine)
                                                    {{ $booking->routine->display_schedule }}
                                                @else
                                                    General
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $booking->total_participants }}</strong>
                                                <ul class="pl-3 mb-0">
                                                    @foreach($booking->participants as $p)
                                                        <li>{{ $p->name }} ({{ $p->phone ?? 'N/A' }})</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>Rs. {{ number_format($booking->total_amount, 2) }}</td>
                                            <td>
                                                @if($booking->payment_voucher)
                                                    @if(Str::endsWith($booking->payment_voucher, '.pdf'))
                                                        <a href="{{ asset('images/vouchers/' . $booking->payment_voucher) }}" target="_blank" class="btn btn-xs btn-outline-info">
                                                            <i class="fa fa-file-pdf"></i> PDF
                                                        </a>
                                                    @else
                                                        <a href="{{ asset('images/vouchers/' . $booking->payment_voucher) }}" target="_blank">
                                                            <img src="{{ asset('images/vouchers/' . $booking->payment_voucher) }}" style="max-width: 50px; max-height: 50px;" class="img-thumbnail">
                                                        </a>
                                                    @endif
                                                @else
                                                    <span class="text-muted small">No Voucher</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $booking->status_badge }}">
                                                    {{ $booking->status_label }}
                                                </span>
                                                @if($booking->status == \App\Models\EventBooking::STATUS_DRAFT)
                                                    <div class="mt-1 small font-weight-bold">
                                                        @php
                                                            $diffInMins = now()->diffInMinutes($booking->created_at);
                                                            $timeLeft = 15 - $diffInMins;
                                                        @endphp
                                                        @if($timeLeft > 0)
                                                            <span class="text-warning"><i class="fa fa-clock"></i> Expires in {{ $timeLeft }} min(s)</span>
                                                        @else
                                                            <span class="text-danger"><i class="fa fa-times-circle"></i> Expired</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($booking->is_present)
                                                    <span class="badge badge-success">Present</span>
                                                @else
                                                    <span class="badge badge-secondary">Absent</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.event.booking.show', $booking->id) }}" class="btn btn-xs btn-outline-dark" title="View Booking Details">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                @if($booking->status == \App\Models\EventBooking::STATUS_PENDING)
                                                    <form action="{{ route('admin.event.booking.approve', $booking->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-xs btn-success" title="Approve Booking"><i class="fa fa-check"></i></button>
                                                    </form>
                                                    <button type="button" class="btn btn-xs btn-danger" title="Reject Booking" onclick="openRejectModal({{ $booking->id }})">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                @endif

                                                @if($booking->status == \App\Models\EventBooking::STATUS_APPROVED)
                                                    <a href="{{ route('admin.event.booking.cards', $booking->id) }}" target="_blank" class="btn btn-xs btn-primary mt-1" title="Print Participant ID Cards"><i class="fa fa-print"></i></a>
                                                    <form action="{{ route('admin.event.booking.send-mail', $booking->id) }}" method="POST" class="d-inline mt-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-xs btn-info" title="Resend QR Email / Link"><i class="fa fa-envelope"></i></button>
                                                    </form>
                                                    @if(!$booking->is_present)
                                                        <form action="{{ route('admin.event.booking.mark-present', $booking->id) }}" method="POST" class="d-inline mt-1">
                                                            @csrf
                                                            <button type="submit" class="btn btn-xs btn-primary" title="Mark Present Manually"><i class="fa fa-user-check"></i></button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Send Bulk Message Modal -->
    <div class="modal fade" id="sendMessageModal" tabindex="-1" role="dialog" aria-labelledby="sendMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <form action="{{ route('admin.event.bookings.send-bulk-message', $data['event']->id) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title text-dark font-weight-bold" id="sendMessageModalLabel"><i class="fa fa-envelope"></i> Send Message to Approved</h5>
                        <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-left">
                        <div class="alert alert-info py-2">
                            <i class="fa fa-info-circle"></i> This message will be sent to the contact email of all <strong>{{ $data['stats']['approved_bookings'] }}</strong> approved bookings for this event.
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Email Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control" required placeholder="e.g. Schedule Update, Venue Announcement, etc." value="Update regarding {{ $data['event']->title }}">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Message Content <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="8" required placeholder="Write your message here...">Your QR code and the event details are provided below:</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning text-dark font-weight-bold"><i class="fa fa-paper-plane"></i> Send Message</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Booking Modal -->
    <div class="modal fade" id="addBookingModal" tabindex="-1" role="dialog" aria-labelledby="addBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('admin.event.bookings.store', $data['event']->id) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white" id="addBookingModalLabel"><i class="fa fa-plus-circle"></i> Add Booking Manually</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="font-weight-bold">Select Existing User <small class="text-muted">(Optional - Autofills Contact & Participant 1 info)</small></label>
                                <div class="input-group">
                                    <select id="select-user-dropdown" class="form-control select2" style="width: 80%;">
                                        <option value="">-- Guest Booking / Register New User --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" 
                                                    data-name="{{ $user->name }}" 
                                                    data-phone="{{ $user->phone }}" 
                                                    data-email="{{ $user->email }}">
                                                {{ $user->name }} ({{ $user->phone ?? $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#registerUserModal" title="Register New User">
                                            <i class="fa fa-plus"></i> Register
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contact Email <span class="text-danger">*</span></label>
                                    <input type="email" id="contact_email" name="contact_email" class="form-control" required placeholder="Enter contact email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contact Phone</label>
                                    <input type="text" id="contact_phone" name="contact_phone" class="form-control" placeholder="Enter contact phone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Event Routine</label>
                                    <select name="event_routine_id" class="form-control">
                                        <option value="">General (No Routine)</option>
                                        @foreach($data['event']->routines as $routine)
                                            <option value="{{ $routine->id }}">
                                                {{ $routine->display_schedule }}
                                                @if($routine->about) ({{ $routine->about }}) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Membership <span class="text-danger">*</span></label>
                                    <select name="is_member" id="modal_is_member" class="form-control">
                                        <option value="1">Cooperative Member</option>
                                        <option value="0">Non-member</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cooperative Name</label>
                                    <input type="text" name="cooperative_name" class="form-control" placeholder="Cooperative Name">
                                </div>
                            </div>
                        </div>

                        <h5 class="mt-4 border-bottom pb-2 font-weight-bold">Participants</h5>
                        <div id="modal-participants-container">
                            <div class="card mb-3 p-3 bg-light modal-participant-row" data-index="0">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold">First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="participants[0][first_name]" class="form-control form-control-sm" required placeholder="First Name">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold">Middle Name</label>
                                            <input type="text" name="participants[0][middle_name]" class="form-control form-control-sm" placeholder="Middle Name">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" name="participants[0][last_name]" class="form-control form-control-sm" required placeholder="Last Name">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold">Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" name="participants[0][phone]" class="form-control form-control-sm" required placeholder="Phone">
                                        </div>
                                    </div>
                                    <div class="col-md-4 row">
                                        <div class="col-md-6 mb-2">
                                            <label class="small font-weight-bold">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="participants[0][email]" class="form-control form-control-sm" required placeholder="Email">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="small font-weight-bold">Gender <span class="text-danger">*</span></label>
                                            <select name="participants[0][gender]" class="form-control form-control-sm" required>
                                                <option value="" disabled selected>Select gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold">DOB (BS) <span class="text-danger">*</span></label>
                                            <input type="text" name="participants[0][dob_bs]" class="form-control form-control-sm dob-bs-input" required placeholder="YYYY-MM-DD" maxlength="10">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold">Designation</label>
                                            <input type="text" name="participants[0][designation]" class="form-control form-control-sm" placeholder="Designation">
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end justify-content-end">
                                        <button type="button" class="btn btn-sm btn-danger remove-participant-btn mb-2" disabled><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-xs btn-outline-primary" id="add-modal-participant-btn">
                            <i class="fa fa-plus"></i> Add Participant
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Booking</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Register User Modal -->
    <div class="modal fade" id="registerUserModal" tabindex="-1" role="dialog" aria-labelledby="registerUserModalLabel" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="registerUserModalLabel"><i class="fas fa-user-plus"></i> Register & Verify New User</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="modal-reg-alert" class="alert d-none py-2 font-weight-bold text-sm" role="alert"></div>

                    <div class="form-group">
                        <label>Full Name <span class="text-danger">*</span></label>
                        <input type="text" id="reg-name" class="form-control" placeholder="Enter full name" required>
                    </div>
                    <div class="form-group">
                        <label>Mobile Number or Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" id="reg-identifier" class="form-control" placeholder="e.g. 98XXXXXXXX or user@email.com" required>
                            <div class="input-group-append">
                                <button type="button" id="reg-send-otp-btn" class="btn btn-outline-success font-weight-bold">Send OTP</button>
                            </div>
                        </div>
                        <small class="text-muted">A 6-digit OTP code will be sent to verify this user.</small>
                    </div>
                    <div class="form-group mb-0">
                        <label>Enter 6-Digit OTP Code</label>
                        <input type="text" id="reg-otp" class="form-control text-center font-weight-bold" placeholder="------" maxlength="6" disabled style="letter-spacing: 5px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="reg-verify-btn" class="btn btn-success font-weight-bold" disabled>Verify & Register User</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Booking Modal -->
    <div class="modal fade" id="rejectBookingModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="rejectBookingForm" method="POST">
                    @csrf
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white"><i class="fa fa-times-circle"></i> Reject Booking</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Rejection Reason / Note <span class="text-danger">*</span></label>
                            <textarea name="rejection_note" class="form-control" rows="3" required placeholder="Enter reason for rejection..."></textarea>
                            <small class="text-muted">This note will be sent to the user via email and displayed on their dashboard.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('backend/assets/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script>
        $(function () {
            $('#select-user-dropdown').select2({
                theme: 'bootstrap4',
                placeholder: 'Search/Select User...'
            });

            $("#example1").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false, "info": true, "paging": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        // Admin manual booking participants handler
        let modalParticipantIndex = 1;
        document.getElementById('add-modal-participant-btn').addEventListener('click', function () {
            const container = document.getElementById('modal-participants-container');
            const rowHtml = `
                <div class="card mb-3 p-3 bg-light modal-participant-row" data-index="${modalParticipantIndex}">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="participants[${modalParticipantIndex}][first_name]" class="form-control form-control-sm" required placeholder="First Name">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold">Middle Name</label>
                                <input type="text" name="participants[${modalParticipantIndex}][middle_name]" class="form-control form-control-sm" placeholder="Middle Name">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="participants[${modalParticipantIndex}][last_name]" class="form-control form-control-sm" required placeholder="Last Name">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" name="participants[${modalParticipantIndex}][phone]" class="form-control form-control-sm" required placeholder="Phone">
                            </div>
                        </div>
                        <div class="col-md-4 row">
                            <div class="col-md-6 mb-2">
                                <label class="small font-weight-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="participants[${modalParticipantIndex}][email]" class="form-control form-control-sm" required placeholder="Email">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="small font-weight-bold">Gender <span class="text-danger">*</span></label>
                                <select name="participants[${modalParticipantIndex}][gender]" class="form-control form-control-sm" required>
                                    <option value="" disabled selected>Select gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold">DOB (BS) <span class="text-danger">*</span></label>
                                <input type="text" name="participants[${modalParticipantIndex}][dob_bs]" class="form-control form-control-sm dob-bs-input" required placeholder="YYYY-MM-DD" maxlength="10">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold">Designation</label>
                                <input type="text" name="participants[${modalParticipantIndex}][designation]" class="form-control form-control-sm" placeholder="Designation">
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end justify-content-end">
                            <button type="button" class="btn btn-sm btn-danger remove-participant-btn mb-2"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', rowHtml);
            modalParticipantIndex++;
        });

        document.addEventListener('click', function (e) {
            if (e.target.closest('.remove-participant-btn')) {
                const row = e.target.closest('.modal-participant-row');
                row.remove();
            }
        });

        // Auto format BS Date in Modal
        function formatDobBs(input) {
            let raw = input.value.replace(/[^0-9]/g, '');
            if (raw.length > 8) raw = raw.slice(0, 8);
            let formatted = '';
            if (raw.length <= 4) {
                formatted = raw;
            } else if (raw.length <= 6) {
                let year  = raw.slice(0, 4);
                let month = raw.slice(4);
                if (parseInt(month) > 12) month = '12';
                formatted = year + '-' + month;
            } else {
                let year  = raw.slice(0, 4);
                let month = raw.slice(4, 6);
                let day   = raw.slice(6);
                if (parseInt(month) > 12) month = '12';
                if (parseInt(day) > 32) day = '32';
                formatted = year + '-' + month + '-' + day;
            }
            input.value = formatted;
        }

        document.addEventListener('input', function (e) {
            if (e.target && e.target.classList.contains('dob-bs-input')) {
                formatDobBs(e.target);
            }
        });

        // Autofill Contact Info when Existing User is Selected
        $('#select-user-dropdown').on('change', function () {
            const selectedOption = $(this).find('option:selected');
            if (selectedOption.val()) {
                const name = selectedOption.data('name');
                const phone = selectedOption.data('phone');
                const email = selectedOption.data('email');

                $('#contact_email').val(email || '');
                $('#contact_phone').val(phone || '');

                // Fill Participant 1 Name, Phone
                $('input[name="participants[0][name]"]').val(name || '');
                $('input[name="participants[0][phone]"]').val(phone || '');
            } else {
                $('#contact_email').val('');
                $('#contact_phone').val('');
                $('input[name="participants[0][name]"]').val('');
                $('input[name="participants[0][phone]"]').val('');
            }
        });

        // Register User Modal: Send OTP AJAX
        let activeRegIdentifier = '';
        $('#reg-send-otp-btn').on('click', function () {
            const identifier = $('#reg-identifier').val().trim();
            const regName = $('#reg-name').val().trim();
            const btn = $(this);

            if (!regName) {
                showRegAlert('Please enter the user\'s Full Name first.', 'alert-danger');
                return;
            }
            if (!identifier) {
                showRegAlert('Please enter a Mobile Number or Email.', 'alert-danger');
                return;
            }

            btn.prop('disabled', true).text('Sending...');
            hideRegAlert();

            $.ajax({
                url: "{{ route('admin.otp-requests.send-otp') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    identifier: identifier
                },
                success: function (res) {
                    if (res.success) {
                        showRegAlert(res.message + ' Enter the code to register.', 'alert-success');
                        activeRegIdentifier = res.identifier;
                        $('#reg-otp').prop('disabled', false).focus();
                        $('#reg-verify-btn').prop('disabled', false);
                        btn.prop('disabled', false).text('Resend OTP');
                    } else {
                        showRegAlert(res.message || 'Failed to send OTP.', 'alert-danger');
                        btn.prop('disabled', false).text('Send OTP');
                    }
                },
                error: function (xhr) {
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error sending OTP.';
                    showRegAlert(errorMsg, 'alert-danger');
                    btn.prop('disabled', false).text('Send OTP');
                }
            });
        });

        // Register User Modal: Verify & Register AJAX
        $('#reg-verify-btn').on('click', function () {
            const otpCode = $('#reg-otp').val().trim();
            const name = $('#reg-name').val().trim();
            const btn = $(this);

            if (!otpCode || otpCode.length !== 6) {
                showRegAlert('Please enter a 6-digit OTP code.', 'alert-danger');
                return;
            }

            btn.prop('disabled', true).text('Registering...');
            hideRegAlert();

            $.ajax({
                url: "{{ route('admin.otp-requests.register') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: name,
                    identifier: activeRegIdentifier,
                    otp_code: otpCode
                },
                success: function (res) {
                    if (res.success) {
                        showRegAlert(res.message, 'alert-success');
                        
                        // Append new option to dropdown and select it
                        const newUserOption = new Option(
                            `${res.user.name} (${res.user.phone || res.user.email})`, 
                            res.user.id, 
                            true, 
                            true
                        );
                        $(newUserOption)
                            .attr('data-name', res.user.name)
                            .attr('data-phone', res.user.phone)
                            .attr('data-email', res.user.email);
                        
                        $('#select-user-dropdown').append(newUserOption).trigger('change');

                        // Reset modal fields and hide modal after delay
                        setTimeout(function () {
                            $('#registerUserModal').modal('hide');
                            $('#reg-name').val('');
                            $('#reg-identifier').val('');
                            $('#reg-otp').val('').prop('disabled', true);
                            $('#reg-verify-btn').prop('disabled', true);
                            $('#reg-send-otp-btn').prop('disabled', false).text('Send OTP');
                            hideRegAlert();
                        }, 1000);
                    } else {
                        showRegAlert(res.message || 'Invalid OTP code.', 'alert-danger');
                        btn.prop('disabled', false).text('Verify & Register User');
                    }
                },
                error: function (xhr) {
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Invalid or expired OTP.';
                    showRegAlert(errorMsg, 'alert-danger');
                    btn.prop('disabled', false).text('Verify & Register User');
                }
            });
        });

        function showRegAlert(msg, className) {
            $('#modal-reg-alert').text(msg).attr('class', 'alert py-2 font-weight-bold text-sm ' + className).removeClass('d-none');
        }

        function hideRegAlert() {
            $('#modal-reg-alert').addClass('d-none');
        }

        function openRejectModal(bookingId) {
            let url = '{{ route("admin.event.booking.reject", ":id") }}';
            url = url.replace(':id', bookingId);
            $('#rejectBookingForm').attr('action', url);
            $('#rejectBookingModal').modal('show');
        }
    </script>
@endsection
