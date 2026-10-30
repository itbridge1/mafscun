<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="service_category_id">Service Category*</label>
                {!! Form::select('service_category_id', $data['categories'] ?? [], $data['selected_category'] ?? null, [
                    'class' => $errors->has('service_category_id') ? 'form-control is-invalid' : 'form-control',
                    'placeholder' => '-- SELECT CATEGORY --',
                    'required' => 'required'
                ]) !!}
                @error('service_category_id')
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
                    'placeholder' => 'Eg. Short Term Loan / Personal Savings',
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
                <label for="sub_title">Sub Title</label>
                {!! Form::text('sub_title', null, [
                    'class' => $errors->has('sub_title') ? 'form-control is-invalid' : 'form-control',
                    'placeholder' => 'Eg. Attractive interest rates',
                ]) !!}
                @error('sub_title')
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
            <div class="card card-gray-dark">
                <div class="card-header">
                    Photo (Optional)
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="photo_upload">Upload Photo</label>
                            {!! Form::file('main_image', [
                                'class' => $errors->has('main_image') ? 'form-control btn btn-sm is-invalid' : 'form-control btn btn-sm',
                                'id' => 'photo',
                                'onchange' => "showPreview(event);",
                                'accept' => 'image/png, image/gif, image/jpeg'
                            ]) !!}
                            @error('main_image')
                            <span class="help-block invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="photo">Existing Image</label>
                            <div>
                                <img width="150" height="150" src="{{ \App\Facades\ViewHelper::getImagePath(@$_folder, @$data['row']->photo) }}" class="img-fluid" id="file-ip-1-preview">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="description">Description</label>
                {!! Form::textarea('description', null, [
                    'class' => $errors->has('description') ? 'form-control summernote is-invalid' : 'form-control summernote',
                    'placeholder' => 'Detailed description of this sub-category',
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

@section('formJs')
    <script>
        function showPreview(event) {
            if (event.target.files.length > 0) {
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("file-ip-1-preview");
                preview.src = src;
                preview.style.display = "block";
            }
        }
    </script>
@endsection
