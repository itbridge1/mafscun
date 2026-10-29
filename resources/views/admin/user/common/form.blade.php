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
                <label for="name">Email*</label>
                {!! Form::text('email', null,[
                     'class'=> $errors->has('email')?'form-control is-invalid':'form-control',
                     'placeholder'=>'Eg. kapilsapkota1001@gmail.com',
                ]) !!}
                @error('email')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Password*</label>
                <div class="input-group mb-3">
                    {!! Form::password('password',[
                         'class'=> $errors->has('password')?'form-control is-invalid':'form-control',
                         'placeholder'=>'Enter password here.',
                         'id'=>'password'
                    ]) !!}
                    <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="ace-icon fa fa-eye" id="togglePassword"></i>
                    </span>
                    </div>
                    @error('password')
                    <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Confirm Password*</label>
                <div class="input-group mb-3">
                    {!! Form::password('password_confirmation',[
                     'class'=> $errors->has('password_confirmation')?'form-control is-invalid':'form-control',
                     'placeholder'=>'Confirm your password..',
                     'id'=>'confirm-password'
                     ]) !!}
                    <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="ace-icon fa fa-eye" id="toggleConfirmPassword"></i>
                    </span>
                    </div>
                    @error('password_confirmation')
                        <span class="help-block invalid-feedback">
                            <strong> {{ $message }} </strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="description">Role*</label>
                {!! Form::select('role', $data['roles'],isset($data['role'])?$data['role']:null,[
                     'class'=> $errors->has('address')?'form-control is-invalid':'form-control',
                     'placeholder'=>'-- Select Role --',
                ]) !!}
                @error('address')
                <span class="help-block invalid-feedback">
                         <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="description">Address</label>
                {!! Form::text('address', null,[
                     'class'=> $errors->has('address')?'form-control is-invalid':'form-control',
                     'placeholder'=>'Banepa-8, Shantinagar, Kavre',
                     'max'=>255,
                     'rows'=>2
                ]) !!}
                @error('address')
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
            <div class="card card-gray-dark">
                <div class="card-header">
                    Signature
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="photo_upload">Upload Signature</label>
                            {!! Form::file('main_signature',[
                                'class'=> $errors->has('main_signature')?'form-control btn btn-sm is-invalid':'form-control btn btn-sm',
                                'id'=>'signature',
                                'onchange'=>"showSignaturePreview(event);",
                                'accept'=>'image/png, image/gif, image/jpeg'
                             ]) !!}
                            @error('main_signature')
                                <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="photo">Existing Image</label>
                            <div>
                               <img width="150" height="150" src="{{ ViewHelper::getImagePath(@$_folder,@$data['row']->signature) }}" class="img-fluid" id="file-ip-2-preview">
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
