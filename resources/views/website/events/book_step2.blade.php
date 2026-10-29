@extends('website.common.layout')
@section('title', 'Book Event: Participant Details')
@section('content')

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow border-0 rounded-lg">
                    <div class="card-header bg-success text-white text-center py-4">
                        <h3 class="mb-0"><i class="fa fa-users"></i> Participant Details</h3>
                        <p class="mb-0 mt-2">{{ $_event->title }}</p>
                    </div>
                    <div class="card-body p-5">
                        {{-- Booking Step Progress --}}
                        <div class="row mb-5 text-center">
                            <div class="col-4 position-relative">
                                <div class="bg-success text-white border-success rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold; z-index: 2; position: relative;"><i class="fa fa-check"></i></div>
                                <h6 class="mt-2 text-success">Select Routine</h6>
                                <div class="position-absolute bg-success" style="height: 3px; width: 100%; top: 20px; left: 50%; z-index: 1;"></div>
                            </div>
                            <div class="col-4 position-relative">
                                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold; z-index: 2; position: relative;">2</div>
                                <h6 class="mt-2 text-success font-weight-bold">Participant Details</h6>
                                <div class="position-absolute bg-light" style="height: 3px; width: 100%; top: 20px; left: 50%; z-index: 1; border-top: 2px dashed #ddd;"></div>
                            </div>
                            <div class="col-4 position-relative">
                                <div class="bg-light text-muted border rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold; z-index: 2; position: relative;">3</div>
                                <h6 class="mt-2 text-muted">Payment & Voucher</h6>
                            </div>
                        </div>

                        {{-- Summary Box --}}
                        <div class="bg-light p-4 rounded mb-5 shadow-sm border">
                            <h5 class="text-primary border-bottom pb-2 mb-3"><i class="fa fa-info-circle"></i> Booking Summary</h5>
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <span class="text-muted d-block">Membership:</span>
                                    <strong class="text-dark">
                                        @if(session('booking_is_member', 1))
                                            <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i> Member</span>
                                        @else
                                            <span class="badge badge-info px-2 py-1"><i class="fa fa-user mr-1"></i> Non-member</span>
                                        @endif
                                    </strong>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <span class="text-muted d-block">Routine:</span>
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
                                <div class="col-md-3 mb-2">
                                    <span class="text-muted d-block">Participants:</span>
                                    <strong class="text-dark">{{ $total }}</strong>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <span class="text-muted d-block">Total Amount:</span>
                                    <strong class="text-success" style="font-size: 1.2rem;">Rs. {{ number_format($_event->calculateTotalPrice($total, session('booking_routines', []), (bool)session('booking_is_member', true)), 2) }}</strong>
                                </div>
                            </div>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('events.book.postStep2', $_event->slug) }}" method="POST">
                            @csrf
                            
                            {{-- Booking Contact Details --}}
                            <div class="card mb-4 border-0 shadow-sm bg-light">
                                <div class="card-header bg-primary text-white py-2">
                                    <h6 class="mb-0"><i class="fa fa-address-book"></i> Booking Contact Person</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Contact Phone @if(auth()->check() && auth()->user()->phone)<small class="text-muted">(Verified via Login)</small>@else<span class="text-danger">*</span>@endif</label>
                                                @if(auth()->check() && auth()->user()->phone)
                                                    <input type="text" name="contact_phone" class="form-control bg-light" value="{{ auth()->user()->phone }}" readonly>
                                                @else
                                                    <input type="text" name="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" placeholder="Enter contact phone" required value="{{ old('contact_phone', session('booking_contact_phone')) }}">
                                                    @error('contact_phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Contact Email @if(auth()->check() && auth()->user()->email)<small class="text-muted">(Verified via Login)</small>@else<span class="text-danger">*</span>@endif</label>
                                                @if(auth()->check() && auth()->user()->email)
                                                    <input type="email" name="contact_email" class="form-control bg-light" value="{{ auth()->user()->email }}" readonly>
                                                @else
                                                    <input type="email" name="contact_email" class="form-control @error('contact_email') is-invalid @enderror" placeholder="Enter contact email" required value="{{ old('contact_email', session('booking_contact_email')) }}">
                                                    @error('contact_email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <h5 class="mb-4 text-primary border-bottom pb-2">Participant Information</h5>
                            @for($i = 0; $i < $total; $i++)
                                <div class="card mb-4 border-0 shadow-sm bg-light">
                                    <div class="card-header bg-secondary text-white py-2 d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0"><i class="fa fa-user"></i> Participant {{ $i + 1 }}</h6>
                                        @if(auth()->check())
                                            <button type="button" class="btn btn-sm btn-light font-weight-bold" onclick="fillMyDetails({{ $i }})"><i class="fa fa-hand-pointer"></i> I am this participant</button>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>First Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="participants[{{ $i }}][first_name]" id="p_{{ $i }}_first_name" class="form-control @error('participants.'.$i.'.first_name') is-invalid @enderror" required placeholder="First name"
                                                        value="{{ old('participants.'.$i.'.first_name') }}">
                                                    @error('participants.'.$i.'.first_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Middle Name</label>
                                                    <input type="text" name="participants[{{ $i }}][middle_name]" id="p_{{ $i }}_middle_name" class="form-control @error('participants.'.$i.'.middle_name') is-invalid @enderror" placeholder="Middle name"
                                                        value="{{ old('participants.'.$i.'.middle_name') }}">
                                                    @error('participants.'.$i.'.middle_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Last Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="participants[{{ $i }}][last_name]" id="p_{{ $i }}_last_name" class="form-control @error('participants.'.$i.'.last_name') is-invalid @enderror" required placeholder="Last name"
                                                        value="{{ old('participants.'.$i.'.last_name') }}">
                                                    @error('participants.'.$i.'.last_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Date of Birth (BS) <span class="text-danger">*</span></label>
                                                    <input type="text" name="participants[{{ $i }}][dob_bs]" id="p_{{ $i }}_dob"
                                                           class="form-control dob-bs-input @error('participants.'.$i.'.dob_bs') is-invalid @enderror"
                                                           placeholder="YYYY-MM-DD"
                                                           maxlength="10"
                                                           required
                                                           autocomplete="off"
                                                           value="{{ old('participants.'.$i.'.dob_bs') }}">
                                                    @error('participants.'.$i.'.dob_bs')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @else
                                                        <small class="text-muted">Format: YYYY-MM-DD (Hyphens are auto-filled) &bull; Month 01–12, Day 01–32</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Phone Number <span class="text-danger">*</span></label>
                                                    <input type="text" name="participants[{{ $i }}][phone]" id="p_{{ $i }}_phone" class="form-control @error('participants.'.$i.'.phone') is-invalid @enderror" required placeholder="Enter phone number" value="{{ old('participants.'.$i.'.phone') }}">
                                                    @error('participants.'.$i.'.phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email Address <span class="text-danger">*</span></label>
                                                    <input type="email" name="participants[{{ $i }}][email]" id="p_{{ $i }}_email" class="form-control @error('participants.'.$i.'.email') is-invalid @enderror" required placeholder="Enter email address" value="{{ old('participants.'.$i.'.email') }}">
                                                    @error('participants.'.$i.'.email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Gender <span class="text-danger">*</span></label>
                                                    <select name="participants[{{ $i }}][gender]" class="form-control @error('participants.'.$i.'.gender') is-invalid @enderror" required>
                                                        <option value="" disabled {{ old('participants.'.$i.'.gender') ? '' : 'selected' }}>Select gender</option>
                                                        <option value="Male" {{ old('participants.'.$i.'.gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                                        <option value="Female" {{ old('participants.'.$i.'.gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                                        <option value="Other" {{ old('participants.'.$i.'.gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                    @error('participants.'.$i.'.gender')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Designation <small class="text-muted">(Optional)</small></label>
                                                    <input type="text" name="participants[{{ $i }}][designation]" class="form-control @error('participants.'.$i.'.designation') is-invalid @enderror" placeholder="Enter designation (e.g. Manager, Member)" value="{{ old('participants.'.$i.'.designation') }}">
                                                    @error('participants.'.$i.'.designation')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Custom Participant Questions --}}
                                            @if(isset($_event->questions) && $_event->questions->count() > 0)
                                                <div class="col-12 mt-2 pt-3 border-top">
                                                    <h6 class="text-primary font-weight-bold mb-3">
                                                        <i class="fas fa-clipboard-list mr-1"></i> Additional Event Questions
                                                    </h6>
                                                    <div class="row">
                                                        @foreach($_event->questions as $q)
                                                            @php
                                                                $qFieldName = "participants.{$i}.answers.{$q->id}";
                                                                $savedVal = old("participants.{$i}.answers.{$q->id}", session("booking_participants.{$i}.answers.{$q->id}"));
                                                                $parsedOptions = $q->parsed_options;
                                                            @endphp
                                                            <div class="col-md-6 mb-3">
                                                                <label class="font-weight-bold text-dark text-sm mb-1 d-block">
                                                                    {{ $q->title }}
                                                                    @if($q->is_required)
                                                                        <span class="text-danger">*</span>
                                                                    @else
                                                                        <small class="text-muted font-weight-normal">(Optional)</small>
                                                                    @endif
                                                                </label>

                                                                @if($q->type === 'text')
                                                                    <input type="text" name="participants[{{ $i }}][answers][{{ $q->id }}]" 
                                                                           class="form-control @error($qFieldName) is-invalid @enderror" 
                                                                           placeholder="Enter your answer" 
                                                                           value="{{ $savedVal }}"
                                                                           {{ $q->is_required ? 'required' : '' }}>

                                                                @elseif($q->type === 'number')
                                                                    <input type="number" name="participants[{{ $i }}][answers][{{ $q->id }}]" 
                                                                           class="form-control @error($qFieldName) is-invalid @enderror" 
                                                                           placeholder="Enter number only" 
                                                                           value="{{ $savedVal }}"
                                                                           {{ $q->is_required ? 'required' : '' }}>

                                                                @elseif($q->type === 'radio')
                                                                    <div class="pt-1">
                                                                        @foreach($parsedOptions as $optIndex => $option)
                                                                            @php
                                                                                $radioId = "q_{$q->id}_p{$i}_opt{$optIndex}";
                                                                                $isChecked = ($savedVal == $option);
                                                                            @endphp
                                                                            <div class="custom-control custom-radio custom-control-inline mr-3">
                                                                                <input type="radio" id="{{ $radioId }}" 
                                                                                       name="participants[{{ $i }}][answers][{{ $q->id }}]" 
                                                                                       value="{{ $option }}" 
                                                                                       class="custom-control-input" 
                                                                                       {{ $isChecked ? 'checked' : '' }}
                                                                                       {{ $q->is_required ? 'required' : '' }}>
                                                                                <label class="custom-control-label" for="{{ $radioId }}">{{ $option }}</label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>

                                                                @elseif($q->type === 'select')
                                                                    <select name="participants[{{ $i }}][answers][{{ $q->id }}]" 
                                                                            class="form-control @error($qFieldName) is-invalid @enderror"
                                                                            {{ $q->is_required ? 'required' : '' }}>
                                                                        <option value="" disabled {{ empty($savedVal) ? 'selected' : '' }}>-- Select an option --</option>
                                                                        @foreach($parsedOptions as $option)
                                                                            <option value="{{ $option }}" {{ $savedVal == $option ? 'selected' : '' }}>
                                                                                {{ $option }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>

                                                                @elseif($q->type === 'checkbox')
                                                                    <div class="pt-1">
                                                                        @php
                                                                            $selectedArray = is_array($savedVal) ? $savedVal : ($savedVal ? [$savedVal] : []);
                                                                        @endphp
                                                                        @foreach($parsedOptions as $optIndex => $option)
                                                                            @php
                                                                                $chkId = "q_{$q->id}_p{$i}_chk{$optIndex}";
                                                                                $isChecked = in_array($option, $selectedArray);
                                                                            @endphp
                                                                            <div class="custom-control custom-checkbox custom-control-inline mr-3">
                                                                                <input type="checkbox" id="{{ $chkId }}" 
                                                                                       name="participants[{{ $i }}][answers][{{ $q->id }}][]" 
                                                                                       value="{{ $option }}" 
                                                                                       class="custom-control-input" 
                                                                                       {{ $isChecked ? 'checked' : '' }}>
                                                                                <label class="custom-control-label" for="{{ $chkId }}">{{ $option }}</label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                @endif

                                                                @error($qFieldName)
                                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endfor

                            <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                                <a href="{{ route('events.book.step1', ['slug' => $_event->slug, 'edit' => 1]) }}" class="btn btn-outline-secondary px-4"><i class="fa fa-arrow-left mr-2"></i> Go Back</a>
                                <button type="submit" class="btn btn-success px-5 font-weight-bold shadow-sm">Proceed to Payment <i class="fa fa-arrow-right ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
// Auto-format BS date: YYYY-MM-DD with month ≤12 and day ≤32
function formatDobBs(input) {
    let raw = input.value.replace(/[^0-9]/g, ''); // digits only

    // Clamp to 8 digits (YYYYMMDD)
    if (raw.length > 8) raw = raw.slice(0, 8);

    let formatted = '';
    if (raw.length <= 4) {
        formatted = raw;
    } else if (raw.length <= 6) {
        let year  = raw.slice(0, 4);
        let month = raw.slice(4);
        // Clamp month
        if (parseInt(month) > 12) month = '12';
        formatted = year + '-' + month;
    } else {
        let year  = raw.slice(0, 4);
        let month = raw.slice(4, 6);
        let day   = raw.slice(6);
        // Clamp month
        if (parseInt(month) > 12) month = '12';
        // Clamp day (BS max is 32)
        if (parseInt(day) > 32) day = '32';
        formatted = year + '-' + month + '-' + day;
    }

    input.value = formatted;
}

// Attach to all existing and dynamically added dob-bs-input fields
document.addEventListener('input', function (e) {
    if (e.target && e.target.classList.contains('dob-bs-input')) {
        formatDobBs(e.target);
    }
});

@if(auth()->check())
function fillMyDetails(index) {
    document.getElementById('p_' + index + '_first_name').value = "{{ auth()->user()->first_name ?? '' }}";
    document.getElementById('p_' + index + '_middle_name').value = "{{ auth()->user()->middle_name ?? '' }}";
    document.getElementById('p_' + index + '_last_name').value = "{{ auth()->user()->last_name ?? '' }}";
    document.getElementById('p_' + index + '_dob').value = "{{ auth()->user()->dob_bs ?? '' }}";
    document.getElementById('p_' + index + '_phone').value = "{{ auth()->user()->phone ?? '' }}";
    document.getElementById('p_' + index + '_email').value = "{{ auth()->user()->email ?? '' }}";
}
@endif
</script>
@endsection
