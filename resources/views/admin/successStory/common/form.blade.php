<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Name*</label>
                {!! Form::text('name', null,[
                      'class'=> $errors->has('name')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. Kapil Sapkota',
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
                <label for="name">Organization</label>
                {!! Form::text('organization', null,[
                      'class'=> $errors->has('organization')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. It Bridge Nepal',
                      ]) !!}
                @error('organization')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Designation</label>
                {!! Form::text('designation', null,[
                      'class'=> $errors->has('designation')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. Web Developer',
                      ]) !!}
                @error('designation')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="name">MembershipNo</label>
                {!! Form::text('membership_no', null,[
                      'class'=> $errors->has('membership_no')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. 00111-11',
                      ]) !!}
                @error('membership_no')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="name">Phone</label>
                {!! Form::text('phone', null,[
                      'class'=> $errors->has('phone')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. 00111-11',
                      ]) !!}
                @error('phone')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="name">Email</label>
                {!! Form::text('email', null,[
                      'class'=> $errors->has('email')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. 00111-11',
                      ]) !!}
                @error('email')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
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
        $(function (){
            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })
        })
    </script>
@endsection
