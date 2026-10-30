<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="resource_type_id">Resource Type*</label>
                {!! Form::select('resource_type_id', $data['types'] ?? [], $data['selected_type'] ?? null, [
                    'class' => $errors->has('resource_type_id') ? 'form-control is-invalid' : 'form-control',
                    'placeholder' => '-- SELECT RESOURCE TYPE --',
                    'required' => 'required'
                ]) !!}
                @error('resource_type_id')
                <span class="help-block invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="title">Title*</label>
                {!! Form::text('title', null, [
                    'class' => $errors->has('title') ? 'form-control is-invalid' : 'form-control',
                    'placeholder' => 'Eg. Publications / Forms / Directives',
                    'required' => 'required'
                ]) !!}
                @error('title')
                <span class="help-block invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="status">Status</label>
                {!! Form::select('status', ['1' => 'Active', '0' => 'InActive'], null, [
                    'class' => $errors->has('status') ? 'form-control is-invalid' : 'form-control',
                ]) !!}
                @error('status')
                <span class="help-block invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="description">Description</label>
                {!! Form::textarea('description', null, [
                    'class' => $errors->has('description') ? 'form-control summernote is-invalid' : 'form-control summernote',
                    'placeholder' => 'Description of this sub type',
                ]) !!}
                @error('description')
                <span class="help-block invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
    </div>
</div>
<!-- /.card-body -->
<div class="card-footer">
    <button type="submit" class="btn btn-primary">{{ $button }}</button>
</div>
