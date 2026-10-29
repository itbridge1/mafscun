<div class="card-body">
    <div class="row">
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
                <label for="title">Sub Title</label>
                {!! Form::text('sub_title', null,[
                     'class'=> $errors->has('sub_title')?'form-control is-invalid':'form-control',
                     'placeholder'=>'Eg. Introduction 1.1',
                ]) !!}
                @error('sub_title')
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
                     'class'=> $errors->has('description')?'form-control is-invalid summernote':'form-control summernote',
                     'placeholder'=>'Eg. Description',
                ]) !!}
                @error('description')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>


        <div class="col-md-6">
            <div class="form-group">
                <label for="title">Status</label>
                {!! Form::select('status',['1' => 'Active', '0' => 'InActive'], null,[
                     'class'=> $errors->has('status')?'form-control is-invalid':'form-control',

                ]) !!}
                @error('status')
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

        function showSignaturePreview(event){
            if(event.target.files.length > 0){
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("file-ip-2-preview");
                var photo = document.getElementById("signature");
                preview.src = src;
                photo.value=src;
                preview.style.display = "block";

            }
        }
    </script>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const toggleConfrimPassword = document.querySelector('#toggleConfirmPassword');

        const password = document.querySelector('#password');
        const confirm_password = document.querySelector('#confirm-password');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });

        toggleConfrimPassword.addEventListener('click', function (e) {
            const s = confirm_password.getAttribute('type') === 'password' ? 'text' : 'password';
            confirm_password.setAttribute('type', s);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
@endsection
