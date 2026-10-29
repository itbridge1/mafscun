@extends('website.common.layout')
@section('title', 'Book Event: Payment & Voucher')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow border-0 rounded-lg">
                    <div class="card-header bg-success text-white text-center py-4">
                        <h3 class="mb-0"><i class="fa fa-credit-card"></i> Payment & Voucher Upload</h3>
                        <p class="mb-0 mt-2">{{ $_event->title }}</p>
                    </div>
                    <div class="card-body p-5 position-relative">
                        {{-- Booking Step Progress --}}
                        <div class="row mb-5 text-center">
                            <div class="col-4 position-relative">
                                <div class="bg-success text-white border-success rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold; z-index: 2; position: relative;"><i class="fa fa-check"></i></div>
                                <h6 class="mt-2 text-success">Select Routine</h6>
                                <div class="position-absolute bg-success" style="height: 3px; width: 100%; top: 20px; left: 50%; z-index: 1;"></div>
                            </div>
                            <div class="col-4 position-relative">
                                <div class="bg-success text-white border-success rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold; z-index: 2; position: relative;"><i class="fa fa-check"></i></div>
                                <h6 class="mt-2 text-success">Participant Details</h6>
                                <div class="position-absolute bg-success" style="height: 3px; width: 100%; top: 20px; left: 50%; z-index: 1;"></div>
                            </div>
                            <div class="col-4 position-relative">
                                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold; z-index: 2; position: relative;">3</div>
                                <h6 class="mt-2 text-success font-weight-bold">Payment & Voucher</h6>
                            </div>
                        </div>

                        {{-- Reservation Timer --}}
                        @if(isset($draftBooking))
                        <div class="alert alert-warning border-warning shadow-sm mb-5 text-center" id="reservation-timer-container">
                            <h5 class="mb-1 text-dark"><i class="fas fa-hourglass-half fa-spin text-warning mr-2"></i> Seats Reserved For: <strong id="countdown-timer" class="text-danger font-weight-bold" style="font-size: 1.25rem;">15:00</strong></h5>
                            <p class="mb-0 text-muted small">Please complete your payment and submit before the timer runs out, or your reserved seats may be given to someone else.</p>
                        </div>
                        
                        {{-- Expired Block Overlay --}}
                        <div id="expired-overlay" style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.9); z-index: 10; border-radius: 0.5rem;" class="flex-column align-items-center justify-content-center text-center p-5">
                            <i class="fas fa-clock text-danger mb-3" style="font-size: 4rem;"></i>
                            <h3 class="text-danger font-weight-bold">Reservation Expired!</h3>
                            <p class="text-dark mb-4">Your 15-minute seat reservation has ended. To check if seats are still available and continue, please refresh the page.</p>
                            <button type="button" onclick="window.location.reload()" class="btn btn-danger btn-lg font-weight-bold shadow-sm px-5">Refresh & Check Seats</button>
                        </div>
                        @endif

                        @php
                            $isMemberBooking = isset($draftBooking) ? (bool)$draftBooking->is_member : (bool)session('booking_is_member', true);
                            $finalTotal = (isset($draftBooking) && $draftBooking->total_amount !== null) 
                                ? (float) $draftBooking->total_amount 
                                : $_event->calculateTotalPrice(count($participants), session('booking_routines', []), $isMemberBooking);
                        @endphp

                        {{-- Summary Box --}}
                        <div class="bg-light p-4 rounded mb-5 shadow-sm border">
                            <h5 class="text-primary border-bottom pb-2 mb-3"><i class="fa fa-info-circle"></i> Booking Summary</h5>
                            <div class="row">
                                <div class="col-md-2 mb-2">
                                    <span class="text-muted d-block">Membership:</span>
                                    <strong class="text-dark">
                                        @if($isMemberBooking)
                                            <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i> Member</span>
                                        @else
                                            <span class="badge badge-info px-2 py-1"><i class="fa fa-user mr-1"></i> Non-member</span>
                                        @endif
                                    </strong>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <span class="text-muted d-block">Routine(s):</span>
                                    <strong class="text-dark">
                                        @if(isset($routines) && count($routines) > 0)
                                            <ul class="pl-3 mb-0">
                                            @foreach($routines as $rt)
                                                <li>{{ is_array($rt) ? $rt['display_schedule'] : $rt->display_schedule }}</li>
                                            @endforeach
                                            </ul>
                                        @else
                                            General (No Specific Routine)
                                        @endif
                                    </strong>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <span class="text-muted d-block">Participants:</span>
                                    <strong class="text-dark">{{ count($participants) }}</strong>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <span class="text-muted d-block">Total Amount:</span>
                                    <strong class="text-success" style="font-size: 1.2rem;">Rs. {{ number_format($finalTotal, 2) }}</strong>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <span class="text-muted d-block">Contact Email:</span>
                                    <strong class="text-dark">{{ $contact_email }}</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Options Section --}}
                        @if(isset($_payment_qrs) && $_payment_qrs->count() > 0)
                            <h4 class="mb-4 text-primary border-bottom pb-2">Payment Options</h4>
                            <p class="text-muted mb-4">Please scan one of the QR codes below to pay the total amount of <strong>Rs. {{ number_format($finalTotal, 2) }}</strong>, and upload your payment receipt / voucher in the section below.</p>
                            
                            <div class="row justify-content-center mb-5">
                                @foreach($_payment_qrs as $qr)
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100 shadow-sm border-0 bg-light text-center">
                                            <div class="card-body p-4">
                                                <h5 class="font-weight-bold text-dark mb-3">{{ $qr->title }}</h5>
                                                @if($qr->qr_image)
                                                    <img src="{{ \App\Facades\ViewHelper::getImagePath('paymentQr', $qr->qr_image) }}" alt="{{ $qr->title }}" class="img-fluid rounded border mb-3 shadow-sm" style="max-width: 200px;">
                                                @endif
                                                @if($qr->details)
                                                    <div class="text-muted small text-left border-top pt-2 mt-2">
                                                        {!! $qr->details !!}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Main Submission Form --}}
                        <form action="{{ route('events.book.store', $_event->slug) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <input type="hidden" name="contact_email" value="{{ $contact_email }}">
                            <input type="hidden" name="contact_phone" value="{{ $contact_phone }}">

                            @foreach($participants as $index => $p)
                                <input type="hidden" name="participants[{{ $index }}][first_name]" value="{{ $p['first_name'] ?? ($p['name'] ?? '') }}">
                                <input type="hidden" name="participants[{{ $index }}][middle_name]" value="{{ $p['middle_name'] ?? '' }}">
                                <input type="hidden" name="participants[{{ $index }}][last_name]" value="{{ $p['last_name'] ?? '' }}">
                                <input type="hidden" name="participants[{{ $index }}][dob_bs]" value="{{ $p['dob_bs'] ?? '' }}">
                                <input type="hidden" name="participants[{{ $index }}][phone]" value="{{ $p['phone'] ?? '' }}">
                                <input type="hidden" name="participants[{{ $index }}][email]" value="{{ $p['email'] ?? '' }}">
                                <input type="hidden" name="participants[{{ $index }}][gender]" value="{{ $p['gender'] ?? '' }}">
                            @endforeach

                            {{-- Payment Voucher Upload Field --}}
                            <div class="card border-success shadow-sm mb-5">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fa fa-upload"></i> Upload Payment Voucher / Receipt <span class="text-danger">*</span></h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold text-dark">Select Voucher File</label>
                                        <input type="file" name="payment_voucher" class="form-control-file @error('payment_voucher') is-invalid @enderror" required accept="image/*,.pdf">
                                        <small class="text-muted d-block mt-2">Allowed formats: JPG, JPEG, PNG, PDF (Max size: 4MB)</small>
                                        @error('payment_voucher')
                                            <div class="invalid-feedback d-block mt-2 font-weight-bold">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('events.book.step2', $_event->slug) }}" class="btn btn-outline-secondary px-4 py-2">
                                    <i class="fa fa-arrow-left mr-2"></i> Back to Step 2
                                </a>
                                <button type="submit" class="btn btn-success btn-lg px-5 py-2 text-bold shadow">
                                    Confirm & Submit Booking <i class="fa fa-check-circle ml-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@parent
@if(isset($draftBooking))
<script>
    $(document).ready(function() {
        // Calculate expiration time (created_at + 15 minutes)
        var createdAtStr = "{{ $draftBooking->created_at->toIso8601String() }}";
        var createdAt = new Date(createdAtStr).getTime();
        var expireTime = createdAt + (15 * 60 * 1000); // 15 minutes in milliseconds

        var x = setInterval(function() {
            var now = new Date().getTime();
            var distance = expireTime - now;

            if (distance < 0) {
                clearInterval(x);
                $('#countdown-timer').text("EXPIRED");
                $('#reservation-timer-container').removeClass('alert-warning').addClass('alert-danger');
                $('#expired-overlay').css('display', 'flex'); // Show the blocking overlay
                // Disable submit button
                $('button[type="submit"]').prop('disabled', true);
            } else {
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                $('#countdown-timer').text(minutes + "m " + seconds + "s");
            }
        }, 1000);
    });
</script>
@endif
@endsection
