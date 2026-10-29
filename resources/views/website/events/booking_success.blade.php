@extends('website.common.layout')
@section('title', 'Booking Submitted Successfully')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow border-0 rounded-lg text-center">
                    <div class="card-body p-5">
                        @if($_booking->status == \App\Models\EventBooking::STATUS_APPROVED)
                            <div class="mb-4">
                                <i class="fa fa-check-circle text-success" style="font-size: 5rem;"></i>
                            </div>
                            <h2 class="text-dark font-weight-bold mb-3">Booking Approved!</h2>
                            <p class="text-muted text-lg mb-4">
                                Your booking for <strong>{{ $_booking->event->title }}</strong> is <span class="badge badge-success">Approved</span>.
                            </p>
                        @elseif($_booking->status == \App\Models\EventBooking::STATUS_REJECTED)
                            <div class="mb-4">
                                <i class="fa fa-times-circle text-danger" style="font-size: 5rem;"></i>
                            </div>
                            <h2 class="text-dark font-weight-bold mb-3">Booking Rejected</h2>
                            <p class="text-muted text-lg mb-4">
                                Your booking for <strong>{{ $_booking->event->title }}</strong> was <span class="badge badge-danger">Rejected</span>.
                            </p>
                        @else
                            <div class="mb-4">
                                <i class="fa fa-check-circle text-success" style="font-size: 5rem;"></i>
                            </div>
                            <h2 class="text-dark font-weight-bold mb-3">Booking Request Submitted!</h2>
                            <p class="text-muted text-lg mb-4">
                                Your booking for <strong>{{ $_booking->event->title }}</strong> has been received and is currently <span class="badge badge-warning">Pending Approval</span>.
                            </p>
                            
                            @if(session('success'))
                                <div class="alert alert-success text-left mb-4 shadow-sm">
                                    <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
                                </div>
                            @endif

                            <div class="alert alert-info shadow-sm text-left mb-4">
                                <h5 class="alert-heading font-weight-bold"><i class="fa fa-info-circle"></i> What happens next?</h5>
                                <p class="mb-0">
                                    Once the admin verifies your uploaded payment voucher, your booking will be approved.
                                    You will receive an email at <strong>{{ $_booking->contact_email }}</strong> containing your event details and a unique <strong>QR Code</strong> for entry.
                                </p>
                            </div>
                        @endif

                        {{-- Voucher Details --}}
                      

                        <div class="booking-slip shadow border bg-white mb-5 mx-auto text-left" style="max-width: 550px; font-family: 'Courier New', Courier, monospace; border-radius: 12px; overflow: hidden;">
                            <!-- Slip Header -->
                            <div class="bg-success text-white p-4 text-center position-relative">
                                <h4 class="font-weight-bold mb-0 text-uppercase tracking-wider">Booking Receipt</h4>
                                <small class="text-white-50">Please keep this slip for reference</small>
                                <!-- Left cutout -->
                                <div class="position-absolute bg-white rounded-circle" style="width: 20px; height: 20px; bottom: -10px; left: -10px; z-index: 5;"></div>
                                <!-- Right cutout -->
                                <div class="position-absolute bg-white rounded-circle" style="width: 20px; height: 20px; bottom: -10px; right: -10px; z-index: 5;"></div>
                            </div>
                            
                            <!-- Slip Body -->
                            <div class="p-4 bg-white position-relative">
                                <div class="text-center mb-4">
                                    <span class="text-muted text-xs text-uppercase d-block mb-1">Booking Reference</span>
                                    <h3 class="text-success font-weight-bold font-monospace tracking-wide mb-1" style="font-size: 1.8rem;">
                                        #BKM-{{ sprintf('%05d', $_booking->id) }}
                                    </h3>
                                    <small class="text-muted d-block font-sans" style="word-break: break-all;">Token: {{ $_booking->booking_token }}</small>
                                </div>
                                
                                <div class="py-3 my-3" style="border-top: 2px dashed #ddd; border-bottom: 2px dashed #ddd;">
                                    <div class="row">
                                        <div class="col-6 mb-2">
                                            <span class="text-muted text-xs d-block">EVENT</span>
                                            <strong class="text-dark font-sans">{{ $_booking->event->title }}</strong>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <span class="text-muted text-xs d-block">SELECTED ROUTINES & TICKETS</span>
                                            @if($_booking->selected_routines && count($_booking->selected_routines) > 0)
                                                <ul class="pl-3 mb-0 text-dark font-sans text-left">
                                                @foreach($_booking->selected_routines as $rt)
                                                    @php
                                                        $rModel = $rt['id'] ? \App\Models\EventRoutine::find($rt['id']) : null;
                                                        $rName = $rModel ? $rModel->display_schedule : 'General Admission';
                                                        $tType = $rt['type'] === 'group' ? 'Group Ticket' : 'Single Ticket';
                                                    @endphp
                                                    <li>{{ $rName }} <span class="badge badge-light ml-1 border">{{ $tType }}</span></li>
                                                @endforeach
                                                </ul>
                                            @else
                                                <strong class="text-dark font-sans">General Admission</strong>
                                            @endif
                                        </div>
                                        <div class="col-6 mb-2">
                                            <span class="text-muted text-xs d-block">TOTAL PARTICIPANTS</span>
                                            <strong class="text-dark font-sans">{{ $_booking->total_participants }} Person(s)</strong>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted text-xs d-block">PAID AMOUNT</span>
                                            <strong class="text-success font-sans" style="font-size: 1.1rem;">Rs. {{ number_format($_booking->total_amount, 2) }}</strong>
                                        </div>
                                        @if($_booking->status == \App\Models\EventBooking::STATUS_APPROVED)
                                        <div class="col-12 mt-3">
                                            <span class="text-muted text-xs d-block">APPROVED BY</span>
                                            <strong class="text-dark font-sans">{{ $_booking->approvedBy ? $_booking->approvedBy->name : 'Auto approved' }}</strong>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <span class="text-muted text-xs d-block mb-2">PARTICIPANTS</span>
                                    <ul class="list-unstyled mb-0 font-sans" style="font-size: 0.9rem;">
                                        @foreach($_booking->participants as $idx => $p)
                                            <li class="pb-1 text-dark">
                                                <i class="fa fa-user-circle text-muted mr-1"></i> {{ $idx + 1 }}. {{ $p->name }}
                                                @if($p->gender) <small class="text-muted">({{ $p->gender }})</small> @endif
                                                @if($p->dob_bs)
                                                    <small class="text-muted">(DOB: {{ $p->dob_bs }} BS)</small>
                                                @endif
                                                @if($p->designation)
                                                    <div class="text-muted small ml-4" style="margin-top: -2px;">Designation: {{ $p->designation }}</div>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="text-center mt-4 pt-3" style="border-top: 2px dashed #ddd;">
                                    @if($_booking->status == \App\Models\EventBooking::STATUS_APPROVED)
                                        <a href="{{ route('user.booking.cards', $_booking->id) }}" target="_blank" class="btn btn-sm btn-primary font-sans d-print-none font-weight-bold"><i class="fa fa-print mr-1"></i> Print ID Cards</a>
                                    @elseif($_booking->status == \App\Models\EventBooking::STATUS_REJECTED)
                                        <div class="d-inline-block bg-light p-2 rounded border">
                                            <span class="badge badge-danger font-sans px-3 py-2 text-uppercase tracking-wider" style="font-size: 0.75rem;">Status: Rejected</span>
                                        </div>
                                    @else
                                        <div class="d-inline-block bg-light p-2 rounded border">
                                            <div class="text-muted font-sans text-xs mb-1" style="font-size: 0.8rem;"><i class="fa fa-clock-o text-warning"></i> Verification Pending</div>
                                            <span class="badge badge-warning font-sans px-3 py-2 text-uppercase tracking-wider" style="font-size: 0.75rem;">Status: Pending Voucher Verification</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Slip Footer -->
                            <div class="bg-light p-3 text-center border-top d-print-none">
                                <button onclick="window.print();" class="btn btn-sm btn-outline-secondary font-sans"><i class="fa fa-print mr-1"></i> Print Ticket Slip</button>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('events.index') }}" class="btn btn-outline-success px-4"><i class="fa fa-list mr-2"></i> Browse More Events</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
<style>
@media print {
    body * {
        visibility: hidden;
    }
    .booking-slip, .booking-slip * {
        visibility: visible;
    }
    .booking-slip {
        position: absolute;
        left: 50%;
        top: 50px;
        transform: translateX(-50%);
        width: 100%;
        max-width: 550px !important;
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection
