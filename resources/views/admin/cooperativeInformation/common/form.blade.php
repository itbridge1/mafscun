<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Cooperative Name <span class="text-danger">*</span></label>
                {!! Form::text('cooperative_name', null, ['class' => $errors->has('cooperative_name') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Enter Cooperative Name']) !!}
                @error('cooperative_name') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Member No <span class="text-danger">*</span></label>
                {!! Form::text('member_no', null, ['class' => $errors->has('member_no') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Enter Member Number']) !!}
                @error('member_no') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Address <span class="text-danger">*</span></label>
                {!! Form::text('address', null, ['class' => $errors->has('address') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Enter Address']) !!}
                @error('address') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Status</label>
                {!! Form::select('status', ['1' => 'Active', '0' => 'Inactive'], null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
</div>

<div class="card-footer">
    <button type="submit" class="btn btn-primary">{{ $button }}</button>
    <a href="{{ route($_base_route . '.index') }}" class="btn btn-secondary ml-2">Cancel</a>
</div>
