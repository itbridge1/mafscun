<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Province*</label>
                {!! Form::select('province_id', $data['provinces'] ?? [],null,[
                      'class'=> $errors->has('province_id')?'form-control is-invalid':'form-control',
                      'placeholder'=>'--SELECT PROVINCE--',
                      'id' => 'province_id'
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
                <label for="name">District*</label>
                {!! Form::select('district_id', $data['districts'] ?? [],null,[
                      'class'=> $errors->has('district_id')?'form-control is-invalid':'form-control',
                      'placeholder'=>'--SELECT DISTRICT--',
                        'id' => 'district_id'
                      ]) !!}
                @error('district_id')
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
                <label for="name">Link</label>
                {!! Form::text('link', null,[
                      'class'=> $errors->has('link')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. https://en.wikipedia.org/wiki/Dhulikhel',
                      ]) !!}
                @error('link')
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
{{--                {!! Form::select('status',['1' => 'Active', '0' => 'InActive'], null,[--}}
{{--                     'class'=> $errors->has('status')?'form-control is-invalid':'form-control',--}}

{{--                ]) !!}--}}
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
