@extends('website.common.layout')
@section('title', 'Book Event: ' . $_event->title)

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow border-0 rounded-lg">
                    <div class="card-header bg-success text-white text-center py-4" style="background: linear-gradient(135deg, var(--brand-green), var(--brand-green-hover)) !important;">
                        <h3 class="mb-0"><i class="fa fa-ticket"></i> Book Event</h3>
                        <p class="mb-0 mt-2">{{ $_event->title }}</p>
                    </div>
                    <div class="card-body p-5">
                        {{-- Booking Step Progress --}}
                        <div class="row mb-5 text-center">
                            <div class="col-4">
                                <div class="font-weight-bold text-success">
                                    <span class="badge badge-success rounded-circle px-3 py-2" style="font-size: 1.1rem;">1</span>
                                    <div class="mt-2" style="font-size: 0.9rem;">Select Routine</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="font-weight-bold text-muted">
                                    <span class="badge badge-secondary rounded-circle px-3 py-2" style="font-size: 1.1rem;">2</span>
                                    <div class="mt-2" style="font-size: 0.9rem;">Participant Details</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="font-weight-bold text-muted">
                                    <span class="badge badge-secondary rounded-circle px-3 py-2" style="font-size: 1.1rem;">3</span>
                                    <div class="mt-2" style="font-size: 0.9rem;">Payment & Voucher</div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('events.book.postStep1', $_event->slug) }}" method="POST">
                            @csrf
                            
                            @if($_event->routines && $_event->routines->count() > 0)
                                <h5 class="mb-4 text-primary border-bottom pb-2">1. Select Routine <span class="text-danger">*</span></h5>
                                <div class="form-group mb-5">
                                    <label class="font-weight-bold text-dark">Available Routines</label>
                                    <div class="list-group">
                                        @foreach($_event->routines as $index => $routine)
                                            {{-- Single Ticket Option --}}
                                            @php
                                                $rPrice = (float) $routine->price;
                                                $rNmPrice = ($routine->non_member_price !== null && $routine->non_member_price > 0) ? (float) $routine->non_member_price : $rPrice;
                                                $hasDiff = ($rNmPrice != $rPrice);
                                            @endphp
                                            <label class="list-group-item list-group-item-action d-flex justify-content-between align-items-center cursor-pointer py-3">
                                                <div class="custom-control custom-checkbox">
                                                    @php
                                                        $isCheckedSingle = false;
                                                        $oldRoutines = old('event_routines');
                                                        if (is_array($oldRoutines) && isset($oldRoutines[$routine->id]) && $oldRoutines[$routine->id] == 'single') {
                                                             $isCheckedSingle = true;
                                                        } elseif (!old('event_routines') && is_array(session('booking_routines'))) {
                                                            foreach (session('booking_routines') as $r) {
                                                                if (($r['id'] == $routine->id) && $r['type'] == 'single') $isCheckedSingle = true;
                                                            }
                                                        }
                                                    @endphp
                                                    <input type="checkbox" id="routine_{{ $routine->id }}_single" name="event_routines[{{ $routine->id }}]" class="custom-control-input routine-checkbox" value="single" data-group-size="1" data-routine-id="{{ $routine->id }}" data-ticket-type="single" data-member-price="{{ $rPrice }}" data-non-member-price="{{ $rNmPrice }}" {{ $isCheckedSingle ? 'checked' : '' }}>
                                                    <span class="custom-control-label font-weight-bold ml-2 text-dark">
                                                        {{ $routine->about ?? 'Routine' }} 
                                                        <span class="text-muted">(Single Ticket)</span>
                                                        @if($rPrice > 0 || $rNmPrice > 0)
                                                            <span class="badge badge-success ml-2 price-badge" data-member-label="Rs. {{ number_format($rPrice, 2) }}" data-non-member-label="Rs. {{ number_format($rNmPrice, 2) }}">
                                                                Rs. {{ number_format($rPrice, 2) }}
                                                            </span>
                                                            @if($hasDiff)
                                                                <small class="text-muted ml-1 price-diff-hint">(Non-member: Rs. {{ number_format($rNmPrice, 2) }})</small>
                                                            @endif
                                                        @endif
                                                    </span>
                                                    <div class="text-muted small ml-4 mt-1">
                                                        @if($routine->date)
                                                            <strong>{{ \Carbon\Carbon::parse($routine->date)->format('M d, Y') }}</strong> | 
                                                        @endif
                                                        @if($routine->is_all_day)
                                                            All Day
                                                        @else
                                                            {{ \Carbon\Carbon::parse($routine->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($routine->end_time)->format('h:i A') }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </label>

                                            {{-- Group Ticket Option --}}
                                            @if($routine->has_group_pricing && $routine->group_size > 0)
                                            @php
                                                $rGrpPrice = (float) $routine->group_price;
                                                $rNmGrpPrice = ($routine->non_member_group_price !== null && $routine->non_member_group_price > 0) ? (float) $routine->non_member_group_price : $rGrpPrice;
                                                $hasGrpDiff = ($rNmGrpPrice != $rGrpPrice);
                                            @endphp
                                            <label class="list-group-item list-group-item-action d-flex justify-content-between align-items-center cursor-pointer py-3 bg-light">
                                                <div class="custom-control custom-checkbox">
                                                    @php
                                                        $isCheckedGroup = false;
                                                        $oldRoutines = old('event_routines');
                                                        if (is_array($oldRoutines) && isset($oldRoutines[$routine->id]) && $oldRoutines[$routine->id] == 'group') {
                                                            $isCheckedGroup = true;
                                                        } elseif (!old('event_routines') && is_array(session('booking_routines'))) {
                                                            foreach (session('booking_routines') as $r) {
                                                                if (($r['id'] == $routine->id) && $r['type'] == 'group') $isCheckedGroup = true;
                                                            }
                                                        }
                                                    @endphp
                                                    <input type="checkbox" id="routine_{{ $routine->id }}_group" name="event_routines[{{ $routine->id }}]" class="custom-control-input routine-checkbox" value="group" data-group-size="{{ $routine->group_size }}" data-routine-id="{{ $routine->id }}" data-ticket-type="group" data-member-price="{{ $rGrpPrice }}" data-non-member-price="{{ $rNmGrpPrice }}" {{ $isCheckedGroup ? 'checked' : '' }}>
                                                    <span class="custom-control-label font-weight-bold ml-2 text-dark">
                                                        {{ $routine->about ?? 'Routine' }} 
                                                        <span class="text-success">(Group Ticket - sold in multiples of {{ $routine->group_size }})</span>
                                                        @if($rGrpPrice > 0 || $rNmGrpPrice > 0)
                                                            <span class="badge badge-info ml-2 price-badge" data-member-label="Rs. {{ number_format($rGrpPrice, 2) }}" data-non-member-label="Rs. {{ number_format($rNmGrpPrice, 2) }}">
                                                                Rs. {{ number_format($rGrpPrice, 2) }}
                                                            </span>
                                                            @if($hasGrpDiff)
                                                                <small class="text-muted ml-1 price-diff-hint">(Non-member: Rs. {{ number_format($rNmGrpPrice, 2) }})</small>
                                                            @endif
                                                        @endif
                                                    </span>
                                                    <div class="text-muted small ml-4 mt-1">
                                                        Same schedule as above. Ideal for groups!
                                                    </div>
                                                </div>
                                            </label>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if(!$_event->routines || $_event->routines->count() == 0)
                                @php
                                    $genPrice = (float) $_event->event_price;
                                    $genNmPrice = ($_event->non_member_price !== null && $_event->non_member_price > 0) ? (float) $_event->non_member_price : $genPrice;
                                    $hasGenDiff = ($genNmPrice != $genPrice);
                                @endphp
                                @if($_event->has_group_pricing && $_event->group_size > 0)
                                    @php
                                        $genGrpPrice = (float) $_event->group_price;
                                        $genNmGrpPrice = ($_event->non_member_group_price !== null && $_event->non_member_group_price > 0) ? (float) $_event->non_member_group_price : $genGrpPrice;
                                        $hasGenGrpDiff = ($genNmGrpPrice != $genGrpPrice);
                                    @endphp
                                    <h5 class="mb-4 text-primary border-bottom pb-2">1. Select Ticket Type <span class="text-danger">*</span></h5>
                                    <div class="form-group mb-5">
                                        <div class="list-group">
                                            {{-- Single Ticket Option --}}
                                            <label class="list-group-item list-group-item-action d-flex justify-content-between align-items-center cursor-pointer py-3">
                                                <div class="custom-control custom-checkbox">
                                                    @php
                                                        $isCheckedGenSingle = false;
                                                        $oldRoutines = old('event_routines');
                                                        if (is_array($oldRoutines) && isset($oldRoutines[0]) && $oldRoutines[0] == 'single') {
                                                            $isCheckedGenSingle = true;
                                                        } elseif (!old('event_routines') && is_array(session('booking_routines'))) {
                                                            foreach (session('booking_routines') as $r) {
                                                                if (($r['id'] == null) && $r['type'] == 'single') $isCheckedGenSingle = true;
                                                            }
                                                        }
                                                    @endphp
                                                    <input type="checkbox" id="routine_0_single" name="event_routines[0]" class="custom-control-input routine-checkbox" value="single" data-group-size="1" data-routine-id="0" data-ticket-type="single" data-member-price="{{ $genPrice }}" data-non-member-price="{{ $genNmPrice }}" {{ $isCheckedGenSingle ? 'checked' : '' }}>
                                                    <span class="custom-control-label font-weight-bold ml-2 text-dark">
                                                        General Admission 
                                                        <span class="text-muted">(Single Ticket)</span>
                                                        @if($genPrice > 0 || $genNmPrice > 0)
                                                            <span class="badge badge-success ml-2 price-badge" data-member-label="Rs. {{ number_format($genPrice, 2) }}" data-non-member-label="Rs. {{ number_format($genNmPrice, 2) }}">
                                                                Rs. {{ number_format($genPrice, 2) }}
                                                            </span>
                                                            @if($hasGenDiff)
                                                                <small class="text-muted ml-1 price-diff-hint">(Non-member: Rs. {{ number_format($genNmPrice, 2) }})</small>
                                                            @endif
                                                        @endif
                                                    </span>
                                                </div>
                                            </label>

                                            {{-- Group Ticket Option --}}
                                            <label class="list-group-item list-group-item-action d-flex justify-content-between align-items-center cursor-pointer py-3 bg-light">
                                                <div class="custom-control custom-checkbox">
                                                    @php
                                                        $isCheckedGenGroup = false;
                                                        $oldRoutines = old('event_routines');
                                                        if (is_array($oldRoutines) && isset($oldRoutines[0]) && $oldRoutines[0] == 'group') {
                                                            $isCheckedGenGroup = true;
                                                        } elseif (!old('event_routines') && is_array(session('booking_routines'))) {
                                                            foreach (session('booking_routines') as $r) {
                                                                if (($r['id'] == null) && $r['type'] == 'group') $isCheckedGenGroup = true;
                                                            }
                                                        }
                                                    @endphp
                                                    <input type="checkbox" id="routine_0_group" name="event_routines[0]" class="custom-control-input routine-checkbox" value="group" data-group-size="{{ $_event->group_size }}" data-routine-id="0" data-ticket-type="group" data-member-price="{{ $genGrpPrice }}" data-non-member-price="{{ $genNmGrpPrice }}" {{ $isCheckedGenGroup ? 'checked' : '' }}>
                                                    <span class="custom-control-label font-weight-bold ml-2 text-dark">
                                                        General Admission 
                                                        <span class="text-success">(Group Ticket - sold in multiples of {{ $_event->group_size }})</span>
                                                        @if($genGrpPrice > 0 || $genNmGrpPrice > 0)
                                                            <span class="badge badge-info ml-2 price-badge" data-member-label="Rs. {{ number_format($genGrpPrice, 2) }}" data-non-member-label="Rs. {{ number_format($genNmGrpPrice, 2) }}">
                                                                Rs. {{ number_format($genGrpPrice, 2) }}
                                                            </span>
                                                            @if($hasGenGrpDiff)
                                                                <small class="text-muted ml-1 price-diff-hint">(Non-member: Rs. {{ number_format($genNmGrpPrice, 2) }})</small>
                                                            @endif
                                                        @endif
                                                    </span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                @else
                                    @php
                                        $isPerGroup = ($_event->price_type === 'per_group' && $_event->group_size > 0);
                                    @endphp
                                    <input type="hidden" name="event_routines[0]" value="{{ $isPerGroup ? 'group' : 'single' }}" class="routine-hidden-fallback" data-group-size="{{ $isPerGroup ? $_event->group_size : 1 }}" data-routine-id="0" data-ticket-type="{{ $isPerGroup ? 'group' : 'single' }}" data-member-price="{{ $genPrice }}" data-non-member-price="{{ $genNmPrice }}">
                                @endif
                            @endif

                            <h5 class="mb-4 text-primary border-bottom pb-2 mt-5">Cooperative Membership</h5>
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">Is your Cooperative a member of {{ $_site_setting->title ?? 'the Union' }}? <span class="text-danger">*</span></label>
                                <div class="custom-control custom-radio mb-2">
                                    <input type="radio" id="is_member_yes" name="is_member" class="custom-control-input" value="1" {{ old('is_member', session('booking_is_member', 1)) == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold ml-2 text-dark" for="is_member_yes">Yes, Member</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="is_member_no" name="is_member" class="custom-control-input" value="0" {{ old('is_member', session('booking_is_member', 1)) == 0 ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold ml-2 text-dark" for="is_member_no">No, Non-member</label>
                                </div>
                            </div>

                            <div class="form-group mb-5" id="cooperative_select_group">
                                <label class="font-weight-bold text-dark">Select Cooperative <span class="text-danger">*</span></label>
                                <select name="cooperative_id" class="form-control select2" id="cooperative_id_select" style="width: 100%;">
                                    <option value="">-- Select Cooperative --</option>
                                    @foreach($_cooperatives as $coop)
                                        <option value="{{ $coop->id }}" {{ old('cooperative_id', session('booking_cooperative_id')) == $coop->id ? 'selected' : '' }}>{{ $coop->cooperative_name }} (Member No: {{ $coop->member_no }})</option>
                                    @endforeach
                                </select>
                                @error('cooperative_id') <span class="text-danger"><strong>{{ $message }}</strong></span> @enderror
                            </div>

                            <div class="form-group mb-5" id="cooperative_text_group" style="display: none;">
                                <label class="font-weight-bold text-dark">Enter Cooperative Name <span class="text-danger">*</span></label>
                                <input type="text" name="cooperative_name" class="form-control" placeholder="Enter Cooperative Name" value="{{ old('cooperative_name', session('booking_cooperative_name')) }}">
                                @error('cooperative_name') <span class="text-danger"><strong>{{ $message }}</strong></span> @enderror
                            </div>

                            <h5 class="mb-4 text-primary border-bottom pb-2 mt-5">Total Participants</h5>
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">How many people are attending? <span class="text-danger">*</span></label>
                                <input type="number" name="total_participants" class="form-control form-control-lg bg-light font-weight-bold" min="1" max="{{ min(20, $_event->availableSeats()) }}" value="{{ old('total_participants', session('booking_total_participants', 1)) }}" required>
                                <small class="form-text text-muted">Maximum 20 participants per booking. 
                                    @if($_event->availableSeats() <= 5)
                                        <strong class="text-danger">Only {{ $_event->availableSeats() }} seats available!</strong>
                                    @else
                                        <strong class="text-success">Seats are available.</strong>
                                    @endif
                                </small>
                            </div>

                            {{-- Live Estimation Box --}}
                            <div class="card bg-light border-success mb-4 shadow-sm">
                                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted small d-block font-weight-bold">Estimated Total Amount:</span>
                                        <span class="badge badge-success px-2 py-1" id="membership-badge">Member Pricing Applied</span>
                                    </div>
                                    <div class="text-right">
                                        <strong class="text-success h4 font-weight-bold mb-0" id="live-estimate-amount">Rs. 0.00</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                                <a href="{{ route('events.show', $_event->slug) }}" class="btn btn-outline-secondary px-4"><i class="fa fa-arrow-left mr-2"></i> Back to Event</a>
                                <button type="submit" class="btn btn-success px-5 font-weight-bold shadow-sm" id="next-step-btn" style="background: linear-gradient(135deg, var(--brand-green), var(--brand-green-hover)); border: none;">Next Step <i class="fa fa-arrow-right ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
@parent
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .cursor-pointer { cursor: pointer; }
    .custom-control-label::before, .custom-control-label::after {
        top: 2px;
        left: -1.5rem;
    }
    .select2-container--default .select2-selection--single {
        height: calc(1.5em + 1rem + 2px) !important;
        padding: 0.5rem 1rem !important;
        line-height: 1.5 !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.3rem !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5 !important;
        padding-left: 0 !important;
        color: #495057 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(1.5em + 1rem) !important;
    }
</style>
@endsection

@section('js')
@parent
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#cooperative_id_select').select2({
            placeholder: '-- Select Cooperative --',
            allowClear: true
        });

        function updatePriceBadgesAndTotal() {
            let isMember = ($('input[name="is_member"]:checked').val() == '1');

            // Update membership badge in estimation card
            if (isMember) {
                $('#membership-badge').text('Member Pricing Applied').removeClass('badge-info badge-secondary').addClass('badge-success');
            } else {
                $('#membership-badge').text('Non-member Pricing Applied').removeClass('badge-success badge-secondary').addClass('badge-info');
            }

            // Update price badges in routine rows
            $('.price-badge').each(function() {
                let memLabel = $(this).data('member-label');
                let nonMemLabel = $(this).data('non-member-label');
                $(this).text(isMember ? memLabel : nonMemLabel);
            });

            // Update price diff hints
            $('.price-diff-hint').each(function() {
                let badge = $(this).siblings('.price-badge');
                if (badge.length > 0) {
                    let memLabel = badge.data('member-label');
                    let nonMemLabel = badge.data('non-member-label');
                    if (isMember) {
                        $(this).text('(Non-member: ' + nonMemLabel + ')');
                    } else {
                        $(this).text('(Member: ' + memLabel + ')');
                    }
                }
            });

            // Calculate live estimated total amount
            let partsInput = $('input[name="total_participants"]');
            let parts = parseInt(partsInput.val()) || 0;
            let total = 0;

            let selectedCheckboxes = $('.routine-checkbox:checked');
            if (selectedCheckboxes.length > 0) {
                selectedCheckboxes.each(function() {
                    let type = $(this).data('ticket-type');
                    let grpSize = parseInt($(this).data('group-size')) || 1;
                    let mPrice = parseFloat($(this).data('member-price')) || 0;
                    let nmPrice = parseFloat($(this).data('non-member-price')) || 0;
                    let effPrice = isMember ? mPrice : (nmPrice > 0 ? nmPrice : mPrice);

                    if (type === 'group' && grpSize > 0) {
                        total += Math.ceil(parts / grpSize) * effPrice;
                    } else {
                        total += parts * effPrice;
                    }
                });
            } else {
                let fallback = $('.routine-hidden-fallback');
                if (fallback.length > 0) {
                    let type = fallback.data('ticket-type');
                    let grpSize = parseInt(fallback.data('group-size')) || 1;
                    let mPrice = parseFloat(fallback.data('member-price')) || 0;
                    let nmPrice = parseFloat(fallback.data('non-member-price')) || 0;
                    let effPrice = isMember ? mPrice : (nmPrice > 0 ? nmPrice : mPrice);

                    if (type === 'group' && grpSize > 0) {
                        total += Math.ceil(parts / grpSize) * effPrice;
                    } else {
                        total += parts * effPrice;
                    }
                }
            }

            $('#live-estimate-amount').text('Rs. ' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        }

        $('input[name="is_member"]').change(function() {
            if ($(this).val() == '1') {
                $('#cooperative_select_group').show();
                $('#cooperative_text_group').hide();
                $('#cooperative_id_select').prop('required', true);
                $('input[name="cooperative_name"]').prop('required', false);
            } else {
                $('#cooperative_select_group').hide();
                $('#cooperative_text_group').show();
                $('#cooperative_id_select').prop('required', false);
                $('input[name="cooperative_name"]').prop('required', true);
            }
            updatePriceBadgesAndTotal();
        });

        $('input[name="is_member"]:checked').trigger('change');

        // Ensure only one ticket type is checked per routine
        $('.routine-checkbox').change(function() {
            if ($(this).is(':checked')) {
                let routineId = $(this).data('routine-id');
                let ticketType = $(this).data('ticket-type');
                if (ticketType === 'single') {
                    $('#routine_' + routineId + '_group').prop('checked', false);
                } else {
                    $('#routine_' + routineId + '_single').prop('checked', false);
                }
            }
            updateParticipantInput();
            updatePriceBadgesAndTotal();
        });

        // Logic for total participants input based on max group size selected
        function updateParticipantInput() {
            let selectedCheckboxes = $('.routine-checkbox:checked');
            let partsInput = $('input[name="total_participants"]');
            let currentVal = parseInt(partsInput.val()) || 1;
            
            let maxGroupSize = 1;
            let hasSelection = false;

            selectedCheckboxes.each(function() {
                hasSelection = true;
                let size = parseInt($(this).data('group-size')) || 1;
                if (size > maxGroupSize) {
                    maxGroupSize = size;
                }
            });

            if (hasSelection && maxGroupSize > 1) {
                partsInput.attr('min', maxGroupSize);
                partsInput.attr('step', maxGroupSize);
                if (currentVal < maxGroupSize) {
                    partsInput.val(maxGroupSize);
                } else if (currentVal % maxGroupSize !== 0) {
                    partsInput.val(Math.ceil(currentVal / maxGroupSize) * maxGroupSize);
                }
            } else {
                partsInput.attr('min', 1);
                partsInput.attr('step', 1);
            }
        }

        $('input[name="total_participants"]').on('input change', function() {
            updatePriceBadgesAndTotal();
        });
        
        // initialize on page load
        updateParticipantInput();
        updatePriceBadgesAndTotal();
        
        // Add form submission validation
        $('form').submit(function(e) {
            if ($('.routine-checkbox').length > 0 && $('.routine-checkbox:checked').length === 0) {
                e.preventDefault();
                alert('Please select at least one routine to proceed.');
            }
        });
    });
</script>
@endsection
