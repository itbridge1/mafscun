<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="resource_type_id">ResourceType *</label>
                {!! Form::select('resource_type_id',$data['resource_types'], null,[
                      'class'=> $errors->has('resource_type_id')?'form-control is-invalid':'form-control',
                      'id' => 'resource_type_select',
                      'placeholder'=>'--SELECT RESOURCE TYPE--',
                      ]) !!}
                @error('resource_type_id')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="resource_sub_type_id">Resource SubType <small class="text-muted">(Optional)</small></label>
                {!! Form::select('resource_sub_type_id', $data['resource_sub_types'] ?? [], null,[
                      'class'=> $errors->has('resource_sub_type_id')?'form-control is-invalid':'form-control',
                      'id' => 'resource_sub_type_select',
                      'placeholder'=>'-- None (Direct under Type) --',
                      ]) !!}
                @error('resource_sub_type_id')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Title*</label>
                {!! Form::text('title', null,[
                      'class'=> $errors->has('title')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg.Introduction',
                      ]) !!}
                @error('title')
                    <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Sub Title</label>
                {!! Form::text('sub_title', null,[
                      'class'=> $errors->has('sub_title')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg.Introduction',
                      ]) !!}
                @error('sub_title')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="title">Status</label><br>
                {!! Form::checkbox('status', null,isset($data['row'])?null:true, [
                   'class'=> $errors->has('status')?'is-invalid':'',
                   'data-bootstrap-switch',
                   'data-off-color' => 'danger',
                   'data-on-color' => 'success',
                   'data-on-text' => 'ACTIVE',
                   'data-off-text' => 'INACTIVE'
                ]) !!}
                @error('status')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>UploadType <span class="red">*</span></label>
                {!! Form::select('type',config('helper.fileType'), null,[
                     'class'=> $errors->has('type')?'form-control type is-invalid':'form-control type',
                     ]) !!}
                @error('type')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6 link">
            <div class="form-group">
                <label>Link <span class="red">*</span></label>
                {!! Form::text('link', (isset($data['row']) && $data['row']->type == "link")? $data['row']->file : null,[
                     'class'=> $errors->has('link')?'form-control is-invalid':'form-control',
                     'placeholder'=>'Enter link here..',
                     ]) !!}
                @error('link')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-12 file">
            <div class="card card-gray-dark">
                <div class="card-header">
                    File
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="photo_upload">Upload File</label>
                            {!! Form::file('main_file',[
                                'class'=> $errors->has('main_file')?'form-control btn btn-sm is-invalid':'form-control btn btn-sm',
                                'id'=>'photo',
                                'onchange'=>"showPreview(event);",

                             ]) !!}
                            @error('main_file')
                            <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="photo">Existing Image</label>
                            <div class="preview">
                                <img width="200" height="200" src="{{ ViewHelper::getImagePath(@$_folder,@$data['row']->file) }}"
                                     class="img-responsive"
                                     id="file-ip-1-preview">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="title">Description</label>
                {!! Form::textarea('description', null,[
                     'class'=> $errors->has('description')?'form-control summernote is-invalid':'form-control summernote',
                     'placeholder'=>'Eg. Description',
                ]) !!}
                @error('description')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>
    </div>
</div>
<!-- /.card-body -->
<div class="card-footer">
    <button type="submit" class="btn btn-primary">{{$button}}</button>
</div>

@section('formJs')
    <script src="{{ asset('backend/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>

    <script>
        function showPreview(event){
            if(event.target.files.length > 0){
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("file-ip-1-preview");
                var photo = document.getElementById("photo");
                preview.src = src;
                photo.value=src;
                preview.style.display = "block";

            }
        }
    </script>
    <script>
        $(function () {
            checkUploadType();
            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })
            $('.type').change(function (){
                checkUploadType();
            });
            function checkUploadType(){
                var type = $('.type').val();
                if(type=== "link" || type === "video"){
                    $('.link').show();
                    $('.file').hide();
                }else{
                    $('.file').show();
                    $('.link').hide();
                }
            }

            var selectedSubId = "{{ old('resource_sub_type_id', @$data['row']->resource_sub_type_id) }}";
            $('#resource_type_select').on('change', function() {
                var typeId = $(this).val();
                var $subSelect = $('#resource_sub_type_select');
                $subSelect.empty().append('<option value="">-- None (Direct under Type) --</option>');
                if (typeId) {
                    $.ajax({
                        url: "{{ url('admin/resourceSubType/by-type') }}/" + typeId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            if (data && data.length > 0) {
                                $.each(data, function(index, item) {
                                    var isSelected = (String(item.id) === String(selectedSubId)) ? 'selected' : '';
                                    $subSelect.append('<option value="' + item.id + '" ' + isSelected + '>' + item.title + '</option>');
                                });
                            }
                        }
                    });
                }
            });

            if ($('#resource_type_select').val() && $('#resource_sub_type_select option').length <= 1) {
                $('#resource_type_select').trigger('change');
            }
        })
    </script>
@endsection
