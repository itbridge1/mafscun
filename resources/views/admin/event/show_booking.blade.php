@extends('admin.layout.frame')
@section('title', 'Booking Details')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Booking Details</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ $dashboard_url }}"><i class="fa fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.event.index') }}">Events</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.event.bookings', $booking->event_id) }}">Bookings</a></li>
                            <li class="breadcrumb-item active">Details</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-7">
                        <!-- Detail Information Card -->
                        <div class="card card-outline card-primary shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h3 class="card-title text-bold"><i class="fas fa-info-circle mr-1"></i> Booking Information</h3>
                                <div class="ml-auto">
                                    <button type="button" class="btn btn-sm btn-info font-weight-bold mr-2" data-toggle="modal" data-target="#editBookingModal">
                                        <i class="fa fa-edit"></i> Edit Booking
                                    </button>
                                    @if($booking->status == \App\Models\EventBooking::STATUS_PENDING)
                                        <form action="{{ route('admin.event.booking.approve', $booking->id) }}" method="POST" class="d-inline mr-2">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success font-weight-bold"><i class="fa fa-check"></i> Approve Booking</button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger font-weight-bold" data-toggle="modal" data-target="#rejectBookingModal"><i class="fa fa-times"></i> Reject Booking</button>
                                    @else
                                        @if($booking->status == \App\Models\EventBooking::STATUS_APPROVED)
                                            <a href="{{ route('admin.event.booking.cards', $booking->id) }}" target="_blank" class="btn btn-sm btn-primary font-weight-bold mr-2">
                                                <i class="fa fa-print"></i> Print ID Cards
                                            </a>
                                        @endif
                                        <span class="badge badge-lg badge-{{ $booking->status_badge }} px-3 py-2 font-weight-bold text-sm">
                                            {{ $booking->status_label }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-bordered mb-0">
                                    <tr>
                                        <th style="width: 35%;">Booking Token</th>
                                        <td class="font-weight-bold text-primary">{{ $booking->booking_token }}</td>
                                    </tr>
                                    <tr>
                                        <th>Event Name</th>
                                        <td><strong>{{ $booking->event->title }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Selected Routine(s) & Ticket Type</th>
                                        <td>
                                            @if($booking->selected_routines && count($booking->selected_routines) > 0)
                                                <ul class="pl-3 mb-0">
                                                @foreach($booking->selected_routines as $rt)
                                                    @php
                                                        $rModel = $rt['id'] ? \App\Models\EventRoutine::find($rt['id']) : null;
                                                        $rName = $rModel ? $rModel->display_schedule : 'General Admission';
                                                        $tType = $rt['type'] === 'group' ? 'Group Ticket' : 'Single Ticket';
                                                        $badgeClass = $rt['type'] === 'group' ? 'badge-info' : 'badge-success';
                                                    @endphp
                                                    <li>{{ $rName }} <span class="badge {{ $badgeClass }} ml-2">{{ $tType }}</span></li>
                                                @endforeach
                                                </ul>
                                            @else
                                                <span class="badge badge-secondary">General Admission</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Booked By</th>
                                        <td>
                                            @if($booking->user)
                                                <strong>{{ $booking->user->name }}</strong>
                                                <small class="text-muted d-block">User Account ID: {{ $booking->user->id }}</small>
                                            @else
                                                <span class="badge badge-secondary">Guest Booking</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Cooperative Name</th>
                                        <td>
                                            @if($booking->is_member)
                                                @if($booking->cooperative)
                                                    <strong>{{ $booking->cooperative->cooperative_name }}</strong>
                                                    <small class="text-muted d-block">Member No: {{ $booking->cooperative->member_no }}</small>
                                                @else
                                                    <strong class="text-danger">Member (Deleted Cooperative)</strong>
                                                @endif
                                            @else
                                                <strong>{{ $booking->cooperative_name ?: 'N/A' }}</strong> <span class="badge badge-secondary ml-1">Non-member</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Contact Phone</th>
                                        <td>{{ $booking->contact_phone ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Contact Email</th>
                                        <td>{{ $booking->contact_email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Routine Time</th>
                                        <td>
                                            @if($booking->routine)
                                                {{ $booking->routine->display_schedule }}
                                            @else
                                                General
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Total Amount Paid</th>
                                        <td class="text-success font-weight-bold text-lg">Rs. {{ number_format($booking->total_amount, 2) }}</td>
                                    </tr>
                                    @if($booking->status == \App\Models\EventBooking::STATUS_REJECTED && $booking->rejection_note)
                                    <tr>
                                        <th>Rejection Reason</th>
                                        <td class="text-danger">{{ $booking->rejection_note }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>Status / Presence</th>
                                        <td>
                                            <span class="badge badge-{{ $booking->status_badge }} px-2 py-1 font-weight-bold">
                                                {{ $booking->status_label }}
                                            </span>
                                            @if($booking->status == \App\Models\EventBooking::STATUS_DRAFT)
                                                <div class="mt-2 small font-weight-bold">
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
                                            @if($booking->is_present)
                                                <span class="badge badge-success px-2 py-1 font-weight-bold ml-2">Present</span>
                                            @else
                                                <span class="badge badge-secondary px-2 py-1 font-weight-bold ml-2">Absent</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($booking->status == \App\Models\EventBooking::STATUS_APPROVED)
                                    <tr>
                                        <th>Approved By</th>
                                        <td>
                                            @if($booking->approvedBy)
                                                <strong>{{ $booking->approvedBy->name }}</strong>
                                                <small class="text-muted d-block">Admin Email: {{ $booking->approvedBy->email }}</small>
                                            @else
                                                <span class="text-muted">Auto approved</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>Booked At</th>
                                        <td>{{ $booking->created_at->format('Y-M-d h:i A') }} <small class="text-muted">({{ $booking->created_at->diffForHumans() }})</small></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="card-footer bg-light">
                                <a href="{{ route('admin.event.bookings', $booking->event_id) }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back to Bookings</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <!-- Voucher File Card -->
                        <div class="card card-outline card-info shadow-sm">
                            <div class="card-header bg-light">
                                <h3 class="card-title text-bold"><i class="fas fa-file-invoice-dollar mr-1"></i> Payment Voucher</h3>
                            </div>
                            <div class="card-body text-center bg-light">
                                @if($booking->payment_voucher)
                                    @if(Str::endsWith($booking->payment_voucher, '.pdf'))
                                        <div class="py-5">
                                            <i class="far fa-file-pdf fa-5x text-danger mb-3"></i>
                                            <h5>PDF Document Uploaded</h5>
                                            <a href="{{ asset('images/vouchers/' . $booking->payment_voucher) }}" target="_blank" class="btn btn-info mt-3">
                                                <i class="fa fa-external-link-alt"></i> Open PDF Voucher
                                            </a>
                                        </div>
                                    @else
                                        <div class="p-2 border bg-white rounded shadow-sm">
                                            <img src="{{ asset('images/vouchers/' . $booking->payment_voucher) }}" 
                                                 class="img-fluid rounded" 
                                                 style="max-height: 400px; cursor: zoom-in; object-fit: contain;" 
                                                 id="view-voucher-img">
                                            <small class="text-muted d-block mt-2"><i class="fa fa-search-plus"></i> Click image to view in full screen</small>
                                        </div>
                                    @endif
                                @else
                                    <div class="py-5 text-muted">
                                        <i class="fas fa-exclamation-circle fa-4x mb-3 text-warning"></i>
                                        <h5>No Payment Voucher Uploaded</h5>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Participant List Card -->
                    <div class="col-12 mt-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-secondary text-white">
                                <h3 class="card-title font-weight-bold mb-0"><i class="fas fa-users mr-1"></i> Registered Participants ({{ count($booking->participants) }})</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th style="width: 60px;">SN</th>
                                                <th>Full Name</th>
                                                <th>Gender</th>
                                                <th>Designation</th>
                                                <th>Phone Number</th>
                                                <th>Date of Birth (BS)</th>
                                                @if($booking->event->questions && $booking->event->questions->count() > 0)
                                                    <th>Additional Info</th>
                                                @endif
                                                <th style="width: 140px;" class="text-center">Attendance</th>
                                                <th style="width: 100px;" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($booking->participants as $key => $p)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td><strong>{{ $p->name }}</strong></td>
                                                    <td>{{ $p->gender ?? 'N/A' }}</td>
                                                    <td>{{ $p->designation ?: 'N/A' }}</td>
                                                    <td>{{ $p->phone ?: 'N/A' }}</td>
                                                    <td>{{ $p->dob_bs ?: 'N/A' }}</td>
                                                    @if($booking->event->questions && $booking->event->questions->count() > 0)
                                                        <td>
                                                            @php $hasAnyAns = false; @endphp
                                                            @foreach($booking->event->questions as $q)
                                                                @php $ans = $p->getAnswerForQuestion($q->id); @endphp
                                                                @if($ans !== null && $ans !== '')
                                                                    @php $hasAnyAns = true; @endphp
                                                                    <div class="mb-1 text-xs">
                                                                        <span class="badge badge-light border text-muted" title="{{ $q->title }}">{{ Str::limit($q->title, 20) }}:</span>
                                                                        <strong class="text-primary">{{ $ans }}</strong>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                            @if(!$hasAnyAns)
                                                                <span class="text-muted text-xs font-italic">-</span>
                                                            @endif
                                                        </td>
                                                    @endif
                                                    <td class="text-center">
                                                        @if($p->is_present)
                                                            <span class="badge badge-success px-2 py-1"><i class="fa fa-check"></i> Present</span>
                                                        @else
                                                            <form action="{{ route('admin.event.booking.participant.mark-present', $p->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-xs btn-outline-success font-weight-bold"><i class="fa fa-user-check"></i> Mark Present</button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-xs btn-warning btn-edit-participant" 
                                                                data-id="{{ $p->id }}" 
                                                                data-name="{{ $p->name }}" 
                                                                data-gender="{{ $p->gender ?? 'Male' }}" 
                                                                data-designation="{{ $p->designation }}" 
                                                                data-phone="{{ $p->phone }}" 
                                                                data-email="{{ $p->email }}" 
                                                                data-dob_bs="{{ $p->dob_bs }}" 
                                                                data-answers="{{ json_encode($p->custom_answers ?? []) }}"
                                                                title="Edit Participant">
                                                            <i class="fa fa-edit"></i> Edit
                                                        </button>
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
            </div>
        </section>
    </div>

    <!-- Voucher Image Zoom Modal -->
    <div class="modal fade" id="zoomVoucherModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1070;">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content bg-transparent border-0 shadow-none">
                <div class="modal-body text-center p-0">
                    <img id="zoom-voucher-img" src="" style="max-width: 95vw; max-height: 95vh; object-fit: contain; cursor: zoom-out;" data-dismiss="modal">
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Booking Modal -->
    <div class="modal fade" id="editBookingModal" tabindex="-1" role="dialog" aria-labelledby="editBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('admin.event.booking.update', $booking->id) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="editBookingModalLabel"><i class="fa fa-edit"></i> Edit Booking Details</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contact Phone</label>
                                    <input type="text" name="contact_phone" class="form-control" value="{{ $booking->contact_phone }}" placeholder="Contact Phone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contact Email <span class="text-danger">*</span></label>
                                    <input type="email" name="contact_email" class="form-control" value="{{ $booking->contact_email }}" required placeholder="Contact Email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Event Routine</label>
                                    <select name="event_routine_id" class="form-control">
                                        <option value="">General (No Routine)</option>
                                        @foreach($booking->event->routines as $routine)
                                            <option value="{{ $routine->id }}" {{ $booking->event_routine_id == $routine->id ? 'selected' : '' }}>
                                                {{ $routine->display_schedule }}
                                                @if($routine->about) ({{ $routine->about }}) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if(!$booking->is_member)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Cooperative Name (Non-member)</label>
                                        <input type="text" name="cooperative_name" class="form-control" value="{{ $booking->cooperative_name }}" placeholder="Cooperative Name">
                                    </div>
                                </div>
                            @endif
                        </div>

                        <h5 class="mt-4 border-bottom pb-2 font-weight-bold">Participants Details</h5>
                        @foreach($booking->participants as $idx => $p)
                            <div class="card mb-3 p-3 bg-light text-left">
                                <div class="row">
                                    <input type="hidden" name="participants[{{ $idx }}][id]" value="{{ $p->id }}">
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold text-dark">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" name="participants[{{ $idx }}][name]" class="form-control form-control-sm" required value="{{ $p->name }}" placeholder="Full Name">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold text-dark">Gender <span class="text-danger">*</span></label>
                                            <select name="participants[{{ $idx }}][gender]" class="form-control form-control-sm" required>
                                                <option value="Male" {{ ($p->gender ?? 'Male') == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ ($p->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                                <option value="Other" {{ ($p->gender ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold text-dark">Designation</label>
                                            <input type="text" name="participants[{{ $idx }}][designation]" class="form-control form-control-sm" value="{{ $p->designation }}" placeholder="Designation">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold text-dark">Phone Number</label>
                                            <input type="text" name="participants[{{ $idx }}][phone]" class="form-control form-control-sm" value="{{ $p->phone }}" placeholder="Phone">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold text-dark">Email</label>
                                            <input type="email" name="participants[{{ $idx }}][email]" class="form-control form-control-sm" value="{{ $p->email }}" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold text-dark">Date of Birth (BS)</label>
                                            <input type="text" name="participants[{{ $idx }}][dob_bs]" class="form-control form-control-sm dob-bs-input" value="{{ $p->dob_bs }}" placeholder="YYYY-MM-DD" maxlength="10">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Booking Modal -->
    <div class="modal fade" id="rejectBookingModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.event.booking.reject', $booking->id) }}" method="POST">
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
    <!-- Edit Participant Modal -->
    <div class="modal fade" id="editParticipantModal" tabindex="-1" role="dialog" aria-labelledby="editParticipantModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="editParticipantForm" method="POST" action="">
                    @csrf
                    <div class="modal-header bg-primary text-white p-3">
                        <h5 class="modal-title font-weight-bold" id="editParticipantModalLabel"><i class="fa fa-user-edit mr-2"></i> Edit Participant Details</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="form-group">
                            <label for="edit_p_name" class="font-weight-bold">Participant Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_p_name" class="form-control" required placeholder="Full Name">
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="edit_p_gender" class="font-weight-bold">Gender <span class="text-danger">*</span></label>
                                <select name="gender" id="edit_p_gender" class="form-control" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="edit_p_designation" class="font-weight-bold">Designation</label>
                                <input type="text" name="designation" id="edit_p_designation" class="form-control" placeholder="e.g. Manager, Officer">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="edit_p_phone" class="font-weight-bold">Phone Number</label>
                                <input type="text" name="phone" id="edit_p_phone" class="form-control" placeholder="Mobile Number">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="edit_p_email" class="font-weight-bold">Email Address</label>
                                <input type="email" name="email" id="edit_p_email" class="form-control" placeholder="email@example.com">
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label for="edit_p_dob_bs" class="font-weight-bold">Date of Birth (BS)</label>
                            <input type="text" name="dob_bs" id="edit_p_dob_bs" class="form-control" placeholder="YYYY-MM-DD">
                        </div>

                        @if($booking->event->questions && $booking->event->questions->count() > 0)
                            <hr class="my-3">
                            <h6 class="font-weight-bold text-dark mb-2"><i class="fa fa-question-circle text-primary mr-1"></i> Additional Questions</h6>
                            @foreach($booking->event->questions as $q)
                                <div class="form-group mb-2">
                                    <label class="font-weight-bold mb-1">
                                        {{ $q->title }}
                                        @if($q->is_required) <span class="text-danger">*</span> @endif
                                    </label>
                                    @if($q->type === 'text')
                                        <input type="text" name="custom_answers[{{ $q->id }}]" class="form-control form-control-sm edit-q-field" data-qid="{{ $q->id }}" data-type="text" placeholder="Enter {{ strtolower($q->title) }}">
                                    @elseif($q->type === 'number')
                                        <input type="number" name="custom_answers[{{ $q->id }}]" class="form-control form-control-sm edit-q-field" data-qid="{{ $q->id }}" data-type="number" placeholder="Enter number">
                                    @elseif($q->type === 'select')
                                        <select name="custom_answers[{{ $q->id }}]" class="form-control form-control-sm edit-q-field" data-qid="{{ $q->id }}" data-type="select">
                                            <option value="">-- Select --</option>
                                            @foreach($q->parsed_options as $opt)
                                                <option value="{{ $opt }}">{{ $opt }}</option>
                                            @endforeach
                                        </select>
                                    @elseif($q->type === 'radio')
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($q->parsed_options as $optIdx => $opt)
                                                <div class="custom-control custom-radio mr-3">
                                                    <input type="radio" id="edit_q_{{ $q->id }}_{{ $optIdx }}" name="custom_answers[{{ $q->id }}]" value="{{ $opt }}" class="custom-control-input edit-q-field edit-q-radio-{{ $q->id }}" data-qid="{{ $q->id }}" data-type="radio">
                                                    <label class="custom-control-label font-weight-normal" for="edit_q_{{ $q->id }}_{{ $optIdx }}">{{ $opt }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($q->type === 'checkbox')
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($q->parsed_options as $optIdx => $opt)
                                                <div class="custom-control custom-checkbox mr-3">
                                                    <input type="checkbox" id="edit_q_{{ $q->id }}_{{ $optIdx }}" name="custom_answers[{{ $q->id }}][]" value="{{ $opt }}" class="custom-control-input edit-q-field edit-q-check-{{ $q->id }}" data-qid="{{ $q->id }}" data-type="checkbox">
                                                    <label class="custom-control-label font-weight-normal" for="edit_q_{{ $q->id }}_{{ $optIdx }}">{{ $opt }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="modal-footer bg-light p-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm font-weight-bold"><i class="fa fa-save mr-1"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).on('click', '#view-voucher-img', function() {
            var src = $(this).attr('src');
            $('#zoom-voucher-img').attr('src', src);
            $('#zoomVoucherModal').modal('show');
        });

        $(document).on('click', '.btn-edit-participant', function() {
            let btn = $(this);
            let id = btn.data('id');
            let name = btn.data('name') || '';
            let gender = btn.data('gender') || 'Male';
            let designation = btn.data('designation') || '';
            let phone = btn.data('phone') || '';
            let email = btn.data('email') || '';
            let dob_bs = btn.data('dob_bs') || '';
            let rawAnswers = btn.data('answers');
            let answers = {};
            if (typeof rawAnswers === 'object' && rawAnswers !== null) {
                answers = rawAnswers;
            } else if (typeof rawAnswers === 'string') {
                try { answers = JSON.parse(rawAnswers); } catch(e) { answers = {}; }
            }

            let url = '{{ route("admin.event.booking.participant.update", ":id") }}'.replace(':id', id);
            $('#editParticipantForm').attr('action', url);
            $('#edit_p_name').val(name);
            $('#edit_p_gender').val(gender);
            $('#edit_p_designation').val(designation);
            $('#edit_p_phone').val(phone);
            $('#edit_p_email').val(email);
            $('#edit_p_dob_bs').val(dob_bs);

            // Populate custom answers
            $('.edit-q-field').each(function() {
                let field = $(this);
                let qid = field.data('qid');
                let type = field.data('type');
                let val = (answers[qid] !== undefined) ? answers[qid] : (answers[String(qid)] !== undefined ? answers[String(qid)] : '');

                if (type === 'text' || type === 'number' || type === 'select') {
                    field.val(val);
                } else if (type === 'radio') {
                    field.prop('checked', field.val() == val);
                } else if (type === 'checkbox') {
                    let arr = Array.isArray(val) ? val : (val ? [val] : []);
                    field.prop('checked', arr.includes(field.val()));
                }
            });

            $('#editParticipantModal').modal('show');
        });
    </script>
@endsection
