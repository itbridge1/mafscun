{{-- Shared form partial for Event create/edit --}}
<div class="card-body">
    <div class="row">
    {{$errors}}
        {{-- Title --}}
        <div class="col-md-6">
            <div class="form-group">
                <label>Title <span class="text-danger">*</span></label>
                {!! Form::text('title', null, ['class' => $errors->has('title') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Event Title']) !!}
                @error('title') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
            </div>
        </div>

        {{-- Total Seats --}}
        <div class="col-md-3">
            <div class="form-group">
                <label>Total Seats <span class="text-danger">*</span></label>
                {!! Form::number('total_seats', null, ['class' => $errors->has('total_seats') ? 'form-control is-invalid' : 'form-control', 'min' => 1]) !!}
                @error('total_seats') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
            </div>
        </div>



        {{-- Short Description --}}
        <div class="col-md-12">
            <div class="form-group">
                <label>Short Description</label>
                {!! Form::textarea('short_description', null, ['class' => $errors->has('short_description') ? 'form-control is-invalid' : 'form-control', 'rows' => 2, 'placeholder' => 'Brief info shown on listing page...']) !!}
                @error('short_description') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
            </div>
        </div>

        {{-- ======== EVENT DATES ======== --}}
        <div class="col-md-12">
            <div class="card border-0 bg-light mb-2">
                <div class="card-header bg-gradient-info py-2">
                    <h6 class="mb-0 text-white"><i class="fa fa-calendar"></i> Event Date</h6>
                </div>
                <div class="card-body pb-1">
                    <div class="row">
                        {{-- Start Date AD --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Start Date (AD) <span class="text-danger">*</span></label>
                                {!! Form::date('start_date', isset($data['row']) ? optional($data['row']->start_date)->format('Y-m-d') : null, ['class' => 'form-control', 'id' => 'start_date_ad']) !!}
                                @error('start_date') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        {{-- Start Date BS --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Start Date (BS) <span class="text-danger">*</span> <small class="text-muted">("-" is  Auto-filled)</small></label>
                                {!! Form::text('start_date_bs', null, ['class' => 'form-control', 'id' => 'start_date_bs', 'placeholder' => 'YYYY-MM-DD', 'pattern' => '[0-9]{4}-[0-9]{2}-[0-9]{2}']) !!}
                                @error('start_date_bs') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        {{-- End Date AD --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>End Date (AD) <small class="text-muted">(optional)</small></label>
                                <div class="input-group">
                                    {!! Form::date('end_date', isset($data['row']) ? optional($data['row']->end_date)->format('Y-m-d') : null, ['class' => 'form-control', 'id' => 'end_date_ad']) !!}
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="clear-end-date" title="Clear end date"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                @error('end_date') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        {{-- End Date BS --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>End Date (BS) <small class="text-muted">(optional, "-" is  Auto-filled)</small></label>
                                {!! Form::text('end_date_bs', null, ['class' => 'form-control', 'id' => 'end_date_bs', 'placeholder' => 'YYYY-MM-DD', 'pattern' => '[0-9]{4}-[0-9]{2}-[0-9]{2}']) !!}
                                @error('end_date_bs') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        {{-- Registration Deadline --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Registration Deadline <small class="text-muted">(optional)</small></label>
                                <div class="input-group">
                                    {!! Form::date('registration_deadline', isset($data['row']) && $data['row']->registration_deadline ? optional($data['row']->registration_deadline)->format('Y-m-d') : null, ['class' => 'form-control', 'id' => 'registration_deadline']) !!}
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="clear-registration-deadline" title="Clear deadline"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                @error('registration_deadline') <span class="text-danger small">{{ $message }}</span> @enderror
                                <small class="form-text text-muted">Last date users can book this event.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Remarks --}}
        <div class="col-md-6">
            <div class="form-group">
                <label>Remarks</label>
                {!! Form::textarea('remarks', null, ['class' => $errors->has('remarks') ? 'form-control is-invalid' : 'form-control', 'rows' => 3, 'placeholder' => 'Any special remarks...']) !!}
                @error('remarks') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
            </div>
        </div>

        {{-- Google Map Embed --}}
        <div class="col-md-6">
            <div class="form-group">
                <label>Google Map Embed Code</label>
                {!! Form::textarea('google_map', null, ['class' => $errors->has('google_map') ? 'form-control is-invalid' : 'form-control', 'rows' => 3, 'placeholder' => 'Paste Google Maps <iframe> embed code here...']) !!}
                @error('google_map') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
            </div>
        </div>

        {{-- Status --}}
        <div class="col-md-6">
            <div class="form-group">
                <label>Status</label>
                {!! Form::select('status', ['1' => 'Active', '0' => 'Inactive'], null, ['class' => 'form-control']) !!}
            </div>
        </div>

        {{-- Photo --}}
        <div class="col-md-12">
            <div class="card card-gray-dark">
                <div class="card-header">Event Photo</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Upload Photo</label>
                            {!! Form::file('main_photo', ['class' => 'form-control btn btn-sm', 'id' => 'event_photo', 'onchange' => 'showEventPhotoPreview(event)', 'accept' => 'image/*']) !!}
                            @error('main_photo') <span class="text-danger"><strong>{{ $message }}</strong></span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label>Preview</label>
                            <div>
                                <img id="event_photo_preview" width="150" height="100"
                                     src="{{ isset($data['row']) ? \App\Facades\ViewHelper::getImagePath('events', $data['row']->photo) : '' }}"
                                     class="img-fluid rounded" style="{{ isset($data['row']) && $data['row']->photo ? '' : 'display:none' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Description --}}
        <div class="col-md-12">
            <div class="form-group">
                <label>Full Description</label>
                {!! Form::textarea('description', null, ['class' => 'form-control summernote']) !!}
            </div>
        </div>

    </div>

    {{-- ============================== ROUTINES SECTION ============================== --}}
    <hr>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="fas fa-clock"></i> Event Routines (Schedule &amp; Pricing)</h5>
        <button type="button" class="btn btn-sm btn-primary" id="add-routine-btn">
            <i class="fas fa-plus"></i> Add Routine
        </button>
    </div>

    <div id="routines-container">
        @php
            $routines = old('routines', isset($data['row']) ? $data['row']->routines : []);
        @endphp
        @if($routines && (is_array($routines) ? count($routines) > 0 : $routines->count() > 0))
            @foreach($routines as $ri => $routine)
                @php
                    $routineObj = is_array($routine) ? (object) $routine : $routine;
                @endphp
                @include('admin.event.common.routine_row', ['ri' => $ri, 'routine' => $routineObj])
            @endforeach
        @endif
    </div>

    {{-- ============================== EVENT PRICING ============================== --}}
    <div id="final-event-pricing-section">
        <hr>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="fas fa-money-bill"></i> Final Event Pricing</h5>
        </div>
        
        <div class="row bg-light p-3 border rounded mb-3 align-items-center">
            {{-- Member Single Price --}}
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <label>Member Single Price <span class="text-danger">*</span></label>
                    {!! Form::number('event_price', null, ['class' => $errors->has('event_price') ? 'form-control is-invalid' : 'form-control', 'step' => '0.01', 'required', 'id' => 'event_price', 'placeholder' => '0.00']) !!}
                    @error('event_price') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            {{-- Non-Member Single Price --}}
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <label>Non-Member Single Price</label>
                    {!! Form::number('non_member_price', null, ['class' => $errors->has('non_member_price') ? 'form-control is-invalid' : 'form-control', 'step' => '0.01', 'id' => 'non_member_price', 'placeholder' => 'Same as Member Price']) !!}
                    <small class="text-muted text-xs">Optional. If empty, Member Price is used.</small>
                    @error('non_member_price') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            {{-- Group Pricing Toggle --}}
            <div class="col-md-2 text-center">
                <div class="custom-control custom-switch mt-3">
                    {!! Form::checkbox('has_group_pricing', 1, old('has_group_pricing', isset($data['row']) ? $data['row']->has_group_pricing : false), ['class' => 'custom-control-input', 'id' => 'event-group-toggle']) !!}
                    <label class="custom-control-label" for="event-group-toggle">Enable Group Pricing?</label>
                </div>
            </div>

            {{-- Group Fields --}}
            <div class="col-md-4 event-group-fields" style="display: {{ old('has_group_pricing', isset($data['row']) ? $data['row']->has_group_pricing : false) ? 'flex' : 'none' }}; gap: 8px;">
                <div class="form-group mb-0" style="min-width: 80px;">
                    <label class="text-xs">Group Size <span class="text-danger">*</span></label>
                    {!! Form::number('group_size', null, ['class' => $errors->has('group_size') ? 'form-control form-control-sm is-invalid event-group-size' : 'form-control form-control-sm event-group-size', 'min' => 1, 'id' => 'event_group_size']) !!}
                    @error('group_size') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                </div>
                <div class="form-group mb-0 flex-grow-1">
                    <label class="text-xs">Member Grp Price <span class="text-danger">*</span></label>
                    {!! Form::number('group_price', null, ['class' => $errors->has('group_price') ? 'form-control form-control-sm is-invalid event-group-price' : 'form-control form-control-sm event-group-price', 'step' => '0.01', 'id' => 'event_group_price']) !!}
                    @error('group_price') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                </div>
                <div class="form-group mb-0 flex-grow-1">
                    <label class="text-xs">Non-Member Grp Price</label>
                    {!! Form::number('non_member_group_price', null, ['class' => $errors->has('non_member_group_price') ? 'form-control form-control-sm is-invalid event-group-price' : 'form-control form-control-sm event-group-price', 'step' => '0.01', 'id' => 'non_member_group_price', 'placeholder' => 'Same as Member']) !!}
                    @error('non_member_group_price') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>
            
            {{-- Hidden price_type for legacy support --}}
            {!! Form::hidden('price_type', 'per_person') !!}
        </div>
    </div>

    {{-- ============================== CUSTOM PARTICIPANT QUESTIONS ============================== --}}
    <div id="event-questions-section" class="mt-4">
        <hr>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 text-primary">
                <i class="fas fa-question-circle mr-1"></i> Additional Participant Questions (सहभागीहरूका लागि अतिरिक्त प्रश्नहरू)
            </h5>
            <button type="button" class="btn btn-sm btn-outline-primary" id="add-question-btn">
                <i class="fa fa-plus mr-1"></i> Add Question
            </button>
        </div>
        <p class="text-muted small">
            Define custom questions that every participant must fill during booking (e.g. Accommodation required, Food preference, T-Shirt Size, Number of years in cooperative, etc.).
        </p>

        <div id="questions-container">
            @php
                $isCreate = !isset($data['row']);
                $isSubmitted = old('_token') !== null;
                if ($isCreate && !$isSubmitted) {
                    $defaultQuestions = [
                        [
                            'id' => null,
                            'title' => 'Food Preference',
                            'type' => 'radio',
                            'options' => 'Veg, Non-Veg',
                            'is_required' => 1,
                            'order' => 0,
                        ],
                        [
                            'id' => null,
                            'title' => 'T-shirt Size',
                            'type' => 'select',
                            'options' => 'S, M, L, XL, XXL',
                            'is_required' => 1,
                            'order' => 1,
                        ]
                    ];
                } else {
                    $defaultQuestions = [];
                }
                $questions = old('questions', isset($data['row']) ? $data['row']->questions : $defaultQuestions);
            @endphp
            @if($questions && (is_array($questions) ? count($questions) > 0 : $questions->count() > 0))
                @foreach($questions as $qi => $question)
                    @include('admin.event.common.question_row', ['qi' => $qi, 'question' => $question])
                @endforeach
            @endif
        </div>
    </div>

</div>

<div class="card-footer">
    <button type="submit" class="btn btn-primary">{{ $button }}</button>
    <a href="{{ route('admin.event.index') }}" class="btn btn-secondary ml-2">Cancel</a>
</div>

@section('formJs')
<script>
    let routineIndex = {{ old('routines') ? count(old('routines')) : (isset($data['row']) ? $data['row']->routines->count() : 0) }};
    function toggleFinalPricingVisibility() {
        const finalSection = document.getElementById('final-event-pricing-section');
        const rowsCount = document.querySelectorAll('.routine-row').length;
        if (rowsCount > 0) {
            finalSection.style.display = 'none';
        } else {
            finalSection.style.display = 'block';
        }
    }

    // Init on page load (for edit page)
    window.addEventListener('DOMContentLoaded', function () {
        toggleFinalPricingVisibility();
        restrictRoutineDates();
        if (!document.getElementById('start_date_bs').value) {
            syncBs('start_date_ad', 'start_date_bs');
        }
        if (!document.getElementById('end_date_bs').value && document.getElementById('end_date_ad').value) {
            syncBs('end_date_ad', 'end_date_bs');
        }
    });

    document.getElementById('add-routine-btn').addEventListener('click', function () {
        const container = document.getElementById('routines-container');
        const templateNode = document.getElementById('routine-template');
        const templateHtml = templateNode.innerHTML.replace(/__RI__/g, routineIndex);
        container.insertAdjacentHTML('beforeend', templateHtml);
        routineIndex++;
        toggleFinalPricingVisibility();
        restrictRoutineDates();
    });
    
    function updateEventPrice() {
        let total = 0;
        let nonMemberTotal = 0;
        let hasAnyNonMember = false;

        document.querySelectorAll('.routine-price').forEach(function(input) {
            total += parseFloat(input.value) || 0;
        });
        document.querySelectorAll('.routine-non-member-price').forEach(function(input) {
            if (input.value !== '') {
                hasAnyNonMember = true;
                nonMemberTotal += parseFloat(input.value) || 0;
            }
        });

        const eventPriceInput = document.querySelector('input[name="event_price"]');
        if (eventPriceInput) {
            eventPriceInput.value = total.toFixed(2);
        }
        const nonMemberPriceInput = document.querySelector('input[name="non_member_price"]');
        if (nonMemberPriceInput && hasAnyNonMember) {
            nonMemberPriceInput.value = nonMemberTotal.toFixed(2);
        }
    }

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('routine-price') || e.target.classList.contains('routine-non-member-price')) {
            updateEventPrice();
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('all-day-toggle')) {
            const row = e.target.closest('.routine-row');
            const timeContainers = row.querySelectorAll('.time-container');
            if (e.target.checked) {
                timeContainers.forEach(container => container.style.display = 'none');
                row.querySelectorAll('.time-input').forEach(input => input.value = '');
            } else {
                timeContainers.forEach(container => container.style.display = 'block');
            }
        }
        
        if (e.target.classList.contains('routine-group-toggle')) {
            const row = e.target.closest('.routine-row');
            const groupFields = row.querySelector('.routine-group-fields');
            if (e.target.checked) {
                groupFields.style.setProperty('display', 'flex', 'important');
            } else {
                groupFields.style.setProperty('display', 'none', 'important');
                row.querySelector('.routine-group-size').value = '';
                row.querySelector('.routine-group-price').value = '';
                const nmGp = row.querySelector('.routine-non-member-group-price');
                if (nmGp) nmGp.value = '';
            }
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-routine-btn')) {
            const row = e.target.closest('.routine-row');
            row.remove();
            updateEventPrice();
            toggleFinalPricingVisibility();
        } else if (e.target.closest('.duplicate-routine-btn')) {
            const row = e.target.closest('.routine-row');
            
            // Extract values
            const dateVal = row.querySelector('.routine-date').value;
            const isAllDay = row.querySelector('.all-day-toggle').checked;
            const startVal = row.querySelector('.routine-start-time').value;
            const endVal = row.querySelector('.routine-end-time').value;
            const aboutVal = row.querySelector('.routine-about').value;
            const priceVal = row.querySelector('.routine-price').value;
            const nonMemberPriceVal = row.querySelector('.routine-non-member-price') ? row.querySelector('.routine-non-member-price').value : '';
            const hasGroup = row.querySelector('.routine-group-toggle').checked;
            const groupSize = row.querySelector('.routine-group-size').value;
            const groupPrice = row.querySelector('.routine-group-price').value;
            const nonMemberGroupPriceVal = row.querySelector('.routine-non-member-group-price') ? row.querySelector('.routine-non-member-group-price').value : '';
            
            // Get clean template
            const templateNode = document.getElementById('routine-template');
            const templateHtml = templateNode.innerHTML.replace(/__RI__/g, routineIndex);
            
            // Insert
            const container = document.getElementById('routines-container');
            container.insertAdjacentHTML('beforeend', templateHtml);
            
            // Find newly inserted row and populate
            const newRow = container.lastElementChild;
            if (dateVal) newRow.querySelector('.routine-date').value = dateVal;
            if (isAllDay) {
                newRow.querySelector('.all-day-toggle').checked = true;
                newRow.querySelectorAll('.time-container').forEach(c => c.style.display = 'none');
            }
            if (startVal) newRow.querySelector('.routine-start-time').value = startVal;
            if (endVal) newRow.querySelector('.routine-end-time').value = endVal;
            if (aboutVal) newRow.querySelector('.routine-about').value = aboutVal;
            if (priceVal) newRow.querySelector('.routine-price').value = priceVal;
            if (nonMemberPriceVal && newRow.querySelector('.routine-non-member-price')) {
                newRow.querySelector('.routine-non-member-price').value = nonMemberPriceVal;
            }
            
            if (hasGroup) {
                newRow.querySelector('.routine-group-toggle').checked = true;
                newRow.querySelector('.routine-group-fields').style.setProperty('display', 'flex', 'important');
                if (groupSize) newRow.querySelector('.routine-group-size').value = groupSize;
                if (groupPrice) newRow.querySelector('.routine-group-price').value = groupPrice;
                if (nonMemberGroupPriceVal && newRow.querySelector('.routine-non-member-group-price')) {
                    newRow.querySelector('.routine-non-member-group-price').value = nonMemberGroupPriceVal;
                }
            }
            
            routineIndex++;
            updateEventPrice();
            restrictRoutineDates();
        }
    });

    document.addEventListener('change', function (e) {
        if (e.target.id === 'event-group-toggle') {
            const groupFields = document.querySelector('.event-group-fields');
            if (e.target.checked) {
                groupFields.style.setProperty('display', 'flex', 'important');
            } else {
                groupFields.style.setProperty('display', 'none', 'important');
                document.getElementById('event_group_size').value = '';
                document.getElementById('event_group_price').value = '';
            }
        }
    });

    function showEventPhotoPreview(event) {
        if (event.target.files.length > 0) {
            const src     = URL.createObjectURL(event.target.files[0]);
            const preview = document.getElementById('event_photo_preview');
            preview.src   = src;
            preview.style.display = 'block';
        }
    }

    /* ========== AD → BS Conversion ========== */
    // Nepali months mapped with their max days
    const NP_MONTHS_DAYS = [
        [30,32,31,32,31,30,30,30,29,30,29,30], // 2000 BS
        [31,31,32,31,31,31,30,29,30,29,30,30],
        [31,31,32,32,31,30,30,29,30,29,30,30],
        [31,32,31,32,31,30,30,30,29,29,30,31],
        [30,32,31,32,31,30,30,30,29,30,29,31],
        [31,31,32,31,31,31,30,29,30,29,30,30],
        [31,31,32,31,32,30,30,29,30,29,30,30],
        [31,32,31,32,31,30,30,30,29,29,30,30],
        [31,31,31,32,31,31,29,30,29,30,29,31],
        [31,31,32,31,31,31,30,29,29,30,30,30],
        [31,31,32,31,32,30,30,29,30,29,30,30],
        [31,32,31,32,31,30,30,30,29,29,30,30],
        [31,31,31,32,31,31,30,29,30,29,30,30],
        [31,31,32,31,31,30,30,30,29,30,29,31],
        [31,31,32,32,31,30,29,30,30,29,29,31],
        [31,32,31,32,31,30,30,29,30,29,30,30],
        [31,32,31,32,31,30,30,30,29,29,30,31],
        [30,32,31,32,31,31,29,30,29,30,29,31],
        [31,31,32,31,31,31,30,29,30,29,30,30],
        [31,31,32,31,32,29,30,29,30,29,30,30],
        [31,32,31,32,31,30,30,30,29,29,30,31],
        [31,31,31,32,31,31,29,30,30,29,29,31],
        [31,31,32,31,31,30,30,30,29,30,29,31],
        [31,31,32,31,32,30,30,29,30,29,30,30],
        [31,32,31,32,31,30,30,30,29,29,30,30],
        [31,31,31,32,31,31,30,29,30,29,30,30],
        [31,31,32,31,31,31,30,29,30,29,30,30],
        [31,31,32,32,31,30,30,29,30,29,30,30],
        [31,32,31,32,31,30,30,30,29,29,30,30],
        [31,32,31,32,31,30,30,29,30,29,30,31],
        [30,32,31,32,31,31,29,30,29,30,29,31],
        [31,31,32,31,31,30,30,29,30,29,30,30],
        [31,31,32,31,32,29,30,29,30,29,30,30],
        [31,32,31,32,31,30,30,30,29,29,30,31],
        [31,31,31,32,31,31,29,30,29,30,29,31],
        [31,31,32,31,31,30,30,30,29,30,29,31],
        [31,32,31,32,31,30,30,29,30,29,30,30],
        [31,32,31,32,31,30,30,30,29,29,30,30],
        [31,31,31,32,31,31,30,29,30,29,30,30],
        [31,31,32,31,31,31,30,29,30,29,30,30],
        [31,31,32,31,32,30,30,29,30,29,30,30],
        [31,32,31,32,31,30,30,30,29,29,30,30],
        [31,32,31,32,31,30,30,29,30,29,30,30],
        [31,31,32,31,31,31,30,29,30,29,30,30],
        [31,31,32,31,32,29,30,29,30,29,30,30],
        [31,32,31,32,31,30,30,30,29,29,30,31],
        [31,31,31,32,31,31,29,30,29,30,29,31],
        [31,31,32,31,31,30,30,30,29,30,29,31],
        [31,32,31,32,31,30,30,29,30,29,30,30],
        [31,32,31,32,31,30,30,30,29,29,30,30],
        [31,31,32,31,31,31,30,29,30,29,30,30],
    ];
    const NP_START_YEAR = 2000;
    const NP_START_DATE = { y: 1943, m: 4, d: 13 }; // April 13, 1943 AD = Baisakh 1, 2000 BS

    function adToBs(adDateStr) {
        if (!adDateStr) return '';
        const [year, month, day] = adDateStr.split('-').map(Number);
        const adDate = new Date(year, month - 1, day);
        const startAD = new Date(NP_START_DATE.y, NP_START_DATE.m - 1, NP_START_DATE.d);
        let totalDays = Math.floor((adDate - startAD) / 86400000);
        if (totalDays < 0) return '';
        let bsYear = NP_START_YEAR, bsMonth = 1, bsDay = 1;
        let yearIdx = 0;
        while (yearIdx < NP_MONTHS_DAYS.length) {
            const monthDays = NP_MONTHS_DAYS[yearIdx];
            const yearDays = monthDays.reduce((a, b) => a + b, 0);
            if (totalDays < yearDays) break;
            totalDays -= yearDays;
            bsYear++;
            yearIdx++;
        }
        if (yearIdx >= NP_MONTHS_DAYS.length) return '';
        const monthDays = NP_MONTHS_DAYS[yearIdx];
        for (let m = 0; m < 12; m++) {
            if (totalDays < monthDays[m]) {
                bsMonth = m + 1;
                bsDay   = totalDays + 1;
                break;
            }
            totalDays -= monthDays[m];
        }
        return `${bsYear}-${String(bsMonth).padStart(2,'0')}-${String(bsDay).padStart(2,'0')}`;
    }

    function syncBs(adInputId, bsInputId) {
        const adVal = document.getElementById(adInputId).value;
        const bsInput = document.getElementById(bsInputId);
        const newBs = adToBs(adVal);
        
        // Only update if we successfully generated a BS date, 
        // to prevent auto-flushing manually entered BS dates if conversion fails.
        if (newBs) {
            bsInput.value = newBs;
        }
    }

    function restrictRoutineDates() {
        const startAd = document.getElementById('start_date_ad').value;
        const endAd = document.getElementById('end_date_ad').value;
        
        document.querySelectorAll('.routine-date').forEach(input => {
            if (startAd) {
                input.setAttribute('min', startAd);
            } else {
                input.removeAttribute('min');
            }
            if (endAd) {
                input.setAttribute('max', endAd);
            } else {
                input.removeAttribute('max');
            }
        });
    }

    document.getElementById('start_date_ad').addEventListener('change', () => {
        syncBs('start_date_ad', 'start_date_bs');
        restrictRoutineDates();
    });
    document.getElementById('end_date_ad').addEventListener('change', () => {
        syncBs('end_date_ad', 'end_date_bs');
        restrictRoutineDates();
    });
    document.getElementById('clear-end-date').addEventListener('click', function () {
        document.getElementById('end_date_ad').value = '';
        document.getElementById('end_date_bs').value = '';
        restrictRoutineDates();
    });
    document.getElementById('clear-registration-deadline').addEventListener('click', function () {
        document.getElementById('registration_deadline').value = '';
    });

    // Auto-format BS date: YYYY-MM-DD with month ≤12 and day ≤32
    function formatBsDateInput(input) {
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
        if (input.value !== formatted) {
            input.value = formatted;
        }
    }

    document.getElementById('start_date_bs').addEventListener('input', function () {
        formatBsDateInput(this);
    });
    document.getElementById('end_date_bs').addEventListener('input', function () {
        formatBsDateInput(this);
    });

    // Init on page load (for edit page)
    window.addEventListener('DOMContentLoaded', function () {
        toggleFinalPricingVisibility();
        if (!document.getElementById('start_date_bs').value) {
            syncBs('start_date_ad', 'start_date_bs');
        }
        if (!document.getElementById('end_date_bs').value && document.getElementById('end_date_ad').value) {
            syncBs('end_date_ad', 'end_date_bs');
        }
    });

    // Custom Questions Management
    let questionIndex = document.querySelectorAll('#questions-container .question-row').length;

    function renumberQuestions() {
        document.querySelectorAll('#questions-container .question-row').forEach(function(row, idx) {
            const numEl = row.querySelector('.question-number');
            if (numEl) numEl.textContent = '#' + (idx + 1);
            const orderInput = row.querySelector('.question-order-input');
            if (orderInput) orderInput.value = idx;
        });
    }

    const addQuestionBtn = document.getElementById('add-question-btn');
    if (addQuestionBtn) {
        addQuestionBtn.addEventListener('click', function () {
            const container = document.getElementById('questions-container');
            const templateNode = document.getElementById('question-template');
            const templateHtml = templateNode.innerHTML.replace(/__QI__/g, questionIndex);
            container.insertAdjacentHTML('beforeend', templateHtml);
            questionIndex++;
            renumberQuestions();
        });
    }

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-question-btn')) {
            const row = e.target.closest('.question-row');
            if (row) {
                row.remove();
                renumberQuestions();
            }
        }

        if (e.target.classList.contains('btn-preset')) {
            const preset = e.target.getAttribute('data-preset');
            const row = e.target.closest('.question-row');
            const input = row.querySelector('.question-options-input');
            if (input) {
                input.value = preset;
            }
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('question-type-select')) {
            const row = e.target.closest('.question-row');
            const optionsWrapper = row.querySelector('.question-options-wrapper');
            const val = e.target.value;
            if (['radio', 'select', 'checkbox'].includes(val)) {
                optionsWrapper.style.display = 'flex';
                row.querySelector('.question-options-input').setAttribute('required', 'required');
            } else {
                optionsWrapper.style.display = 'none';
                row.querySelector('.question-options-input').removeAttribute('required');
            }
        }
    });
</script>

{{-- Routine Row Template for JS injection --}}
<template id="routine-template">
    @include('admin.event.common.routine_row', ['ri' => '__RI__', 'routine' => null])
</template>

{{-- Question Row Template for JS injection --}}
<template id="question-template">
    @include('admin.event.common.question_row', ['qi' => '__QI__', 'question' => null])
</template>
@endsection
