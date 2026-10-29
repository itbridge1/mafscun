<div class="row routine-row border rounded p-3 mb-3 bg-light position-relative">
    @if($routine && isset($routine->id))
        <input type="hidden" name="routines[{{ $ri }}][id]" value="{{ $routine->id }}">
    @endif
    
    <div class="col-md-12 text-right mb-2">
        <button type="button" class="btn btn-sm btn-info duplicate-routine-btn mr-1" title="Duplicate Routine">
            <i class="fas fa-copy"></i>
        </button>
        <button type="button" class="btn btn-sm btn-danger remove-routine-btn" title="Remove Routine">
            <i class="fas fa-trash"></i>
        </button>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label>Date</label>
            {!! Form::date("routines[{$ri}][date]", $routine && isset($routine->date) ? $routine->date : null, ['class' => $errors->has("routines.{$ri}.date") ? 'form-control routine-date is-invalid' : 'form-control routine-date']) !!}
            @error("routines.{$ri}.date") <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label>All Day?</label>
            <div class="custom-control custom-switch mt-2">
                <input type="checkbox" class="custom-control-input all-day-toggle" name="routines[{{ $ri }}][is_all_day]" value="1" id="allDayToggle_{{ $ri }}" {{ ($routine && isset($routine->is_all_day) && $routine->is_all_day) ? 'checked' : '' }}>
                <label class="custom-control-label" for="allDayToggle_{{ $ri }}">Yes</label>
            </div>
        </div>
    </div>
    <div class="col-md-2 time-container" style="display: {{ ($routine && isset($routine->is_all_day) && $routine->is_all_day) ? 'none' : 'block' }}">
        <div class="form-group">
            <label>Start Time</label>
            {!! Form::time("routines[{$ri}][start_time]", $routine && isset($routine->start_time) ? $routine->start_time : null, ['class' => $errors->has("routines.{$ri}.start_time") ? 'form-control time-input routine-start-time is-invalid' : 'form-control time-input routine-start-time']) !!}
            @error("routines.{$ri}.start_time") <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
        </div>
    </div>
    <div class="col-md-2 time-container" style="display: {{ ($routine && isset($routine->is_all_day) && $routine->is_all_day) ? 'none' : 'block' }}">
        <div class="form-group">
            <label>End Time</label>
            {!! Form::time("routines[{$ri}][end_time]", $routine && isset($routine->end_time) ? $routine->end_time : null, ['class' => $errors->has("routines.{$ri}.end_time") ? 'form-control time-input routine-end-time is-invalid' : 'form-control time-input routine-end-time']) !!}
            @error("routines.{$ri}.end_time") <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label>About Routine</label>
            {!! Form::text("routines[{$ri}][about]", $routine && isset($routine->about) ? $routine->about : null, ['class' => $errors->has("routines.{$ri}.about") ? 'form-control routine-about is-invalid' : 'form-control routine-about', 'placeholder' => 'Morning session...']) !!}
            @error("routines.{$ri}.about") <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
        </div>
    </div>
    <div class="col-md-1" style="min-width: 110px;">
        <div class="form-group">
            <label class="text-xs font-weight-bold">Member Price</label>
            {!! Form::number("routines[{$ri}][price]", $routine && isset($routine->price) ? $routine->price : null, ['class' => $errors->has("routines.{$ri}.price") ? 'form-control routine-price is-invalid' : 'form-control routine-price', 'step' => '0.01', 'min' => 0]) !!}
            @error("routines.{$ri}.price") <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
        </div>
    </div>
    <div class="col-md-1" style="min-width: 120px;">
        <div class="form-group">
            <label class="text-xs font-weight-bold">Non-Member Price</label>
            {!! Form::number("routines[{$ri}][non_member_price]", $routine && isset($routine->non_member_price) ? $routine->non_member_price : null, ['class' => $errors->has("routines.{$ri}.non_member_price") ? 'form-control routine-non-member-price is-invalid' : 'form-control routine-non-member-price', 'step' => '0.01', 'min' => 0, 'placeholder' => 'Same as Member']) !!}
            @error("routines.{$ri}.non_member_price") <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
        </div>
    </div>

    {{-- Group Pricing Section --}}
    <div class="col-md-12 mt-2">
        <div class="d-flex align-items-center bg-white p-2 border rounded flex-wrap">
            <div class="custom-control custom-checkbox mr-4 my-1">
                <input type="checkbox" class="custom-control-input routine-group-toggle" name="routines[{{ $ri }}][has_group_pricing]" value="1" id="groupToggle_{{ $ri }}" {{ ($routine && isset($routine->has_group_pricing) && $routine->has_group_pricing) ? 'checked' : '' }}>
                <label class="custom-control-label font-weight-bold text-success" for="groupToggle_{{ $ri }}">Enable Group Pricing?</label>
            </div>
            
            <div class="routine-group-fields d-flex align-items-center flex-wrap my-1" style="display: {{ ($routine && isset($routine->has_group_pricing) && $routine->has_group_pricing) ? 'flex' : 'none' }} !important; gap: 15px;">
                <div class="form-group mb-0 d-flex align-items-center">
                    <label class="mb-0 mr-2 text-xs">Group Size</label>
                    {!! Form::number("routines[{$ri}][group_size]", $routine && isset($routine->group_size) ? $routine->group_size : null, ['class' => $errors->has("routines.{$ri}.group_size") ? 'form-control form-control-sm routine-group-size is-invalid' : 'form-control form-control-sm routine-group-size', 'min' => 2, 'style' => 'width:70px;']) !!}
                    @error("routines.{$ri}.group_size") <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
                <div class="form-group mb-0 d-flex align-items-center">
                    <label class="mb-0 mr-2 text-xs font-weight-bold">Member Grp Price</label>
                    {!! Form::number("routines[{$ri}][group_price]", $routine && isset($routine->group_price) ? $routine->group_price : null, ['class' => $errors->has("routines.{$ri}.group_price") ? 'form-control form-control-sm routine-group-price is-invalid' : 'form-control form-control-sm routine-group-price', 'step' => '0.01', 'min' => 0, 'style' => 'width:110px;']) !!}
                    @error("routines.{$ri}.group_price") <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
                <div class="form-group mb-0 d-flex align-items-center">
                    <label class="mb-0 mr-2 text-xs font-weight-bold">Non-Member Grp Price</label>
                    {!! Form::number("routines[{$ri}][non_member_group_price]", $routine && isset($routine->non_member_group_price) ? $routine->non_member_group_price : null, ['class' => $errors->has("routines.{$ri}.non_member_group_price") ? 'form-control form-control-sm routine-non-member-group-price is-invalid' : 'form-control form-control-sm routine-non-member-group-price', 'step' => '0.01', 'min' => 0, 'placeholder' => 'Same as Member', 'style' => 'width:130px;']) !!}
                    @error("routines.{$ri}.non_member_group_price") <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>
        </div>
    </div>
</div>
