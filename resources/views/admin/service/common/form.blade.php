<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="service_category_id">Service Category*</label>
                {!! Form::select('service_category_id', $data['categories'] ?? [], $data['selected_category'] ?? null, [
                    'class' => $errors->has('service_category_id') ? 'form-control is-invalid' : 'form-control',
                    'id' => 'service_category_select',
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
                <label for="service_sub_category_id">Service Sub Category <small class="text-muted">(Optional - leave blank if direct under category)</small></label>
                {!! Form::select('service_sub_category_id', $data['subCategories'] ?? [], null, [
                    'class' => $errors->has('service_sub_category_id') ? 'form-control is-invalid' : 'form-control',
                    'id' => 'service_sub_category_select',
                    'placeholder' => '-- None (Direct Service under Category) --',
                ]) !!}
                @error('service_sub_category_id')
                <span class="help-block invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="title">Service Title*</label>
                {!! Form::text('title', null, [
                    'class' => $errors->has('title') ? 'form-control is-invalid' : 'form-control',
                    'placeholder' => 'Eg. Daily Savings / Agricultural Loan',
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
                    'placeholder' => 'Eg. Flexible deposit options',
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
                    'placeholder' => 'Detailed description of the service',
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

        $(document).ready(function() {
            var selectedSubId = "{{ old('service_sub_category_id', @$data['row']->service_sub_category_id) }}";

            $('#service_category_select').on('change', function() {
                var categoryId = $(this).val();
                var $subSelect = $('#service_sub_category_select');

                $subSelect.empty().append('<option value="">-- None (Direct Service under Category) --</option>');

                if (categoryId) {
                    $.ajax({
                        url: "{{ url('admin/serviceSubCategory/by-category') }}/" + categoryId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            if (data && data.length > 0) {
                                $.each(data, function(index, subCat) {
                                    var isSelected = (String(subCat.id) === String(selectedSubId)) ? 'selected' : '';
                                    $subSelect.append('<option value="' + subCat.id + '" ' + isSelected + '>' + subCat.title + '</option>');
                                });
                            }
                        }
                    });
                }
            });

            // Trigger on load if category selected and subcategories empty
            if ($('#service_category_select').val() && $('#service_sub_category_select option').length <= 1) {
                $('#service_category_select').trigger('change');
            }
        });
    </script>
@endsection
