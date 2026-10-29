<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Province*</label>
                {!! Form::select('province_id', $data['provinces'] ?? [],null,[
                      'class'=> $errors->has('province_id')?'form-control is-invalid':'form-control',
                      'placeholder'=>'--SELECT PROVINCE--',
                      ]) !!}
                @error('province_id')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Name*</label>
                {!! Form::text('name', null,[
                      'class'=> $errors->has('name')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. Kavre',
                      ]) !!}
                @error('name')
                    <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Nepali Name*</label>
                {!! Form::text('nepali_name', null,[
                      'class'=> $errors->has('nepali_name')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. काभ्रेपलाञ्चोक',
                      ]) !!}
                @error('nepali_name')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Headquarter</label>
                {!! Form::text('headquarter', null,[
                      'class'=> $errors->has('headquarter')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. Dhulikhel',
                      ]) !!}
                @error('headquarter')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="title">Area</label>
                {!! Form::number('area', null,[
                     'class'=> $errors->has('area')?'form-control is-invalid':'form-control',
                     'placeholder'=>'Eg. 2154',
                ]) !!}
                @error('area')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="title">Status</label>
                <br>
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
                <label for="autoselect">Autoselect on Frontend Chart (Only 1 Active)</label>
                <br>
                {!! Form::checkbox('autoselect', null, isset($data['row']) && $data['row']->autoselect ? true : false, [
                   'class'=> $errors->has('autoselect')?'is-invalid':'',
                   'data-bootstrap-switch',
                   'data-off-color' => 'secondary',
                   'data-on-color' => 'primary',
                   'data-on-text' => 'YES',
                   'data-off-text' => 'NO'
               ]) !!}
                <small class="form-text text-muted">When enabled, the frontend chart automatically selects this area and hides the dropdown.</small>
                @error('autoselect')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-12">
            <div class="card card-gray-dark">
                <div class="card-header">
                    Photo
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="photo_upload">Upload Photo</label>
                            {!! Form::file('main_image',[
                                'class'=> $errors->has('main_image')?'form-control btn btn-sm is-invalid':'form-control btn btn-sm',
                                'id'=>'photo',
                                'onchange'=>"showPreview(event);",
                                'accept'=>'image/png, image/gif, image/jpeg'
                             ]) !!}
                            @error('main_image')
                                <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="photo">Existing Image</label>
                            <div>
                               <img width="150" height="150" src="{{ ViewHelper::getImagePath(@$_folder,@$data['row']->photo) }}" class="img-fluid" id="file-ip-1-preview">
                            </div>
                        </div>
                    </div>
                </div>
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
        $(function (){
            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })
        })
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
@endsection
