@extends('admin.layout.frame')
@section('title',auth()->user()->name." Profile")
@section('content')
    @php

    @endphp
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Profile</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">User</a></li>
                            <li class="breadcrumb-item active">{{ auth()->user()->name }} Profile</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">

                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img class="profile-user-img img-fluid img-circle" id="file-ip-1-preview"
                                         src="{{ \App\Facades\ViewHelper::getImagePath('user',auth()->user()->photo) }}"
                                         alt="User profile picture">
                                </div>

                                <h3 class="profile-username text-center">{{ auth()->user()->name }}</h3>

                                <ul class="list-group list-group-unbordered mb-3">
                                    @if(isset(auth()->user()->roles))
                                        @foreach(auth()->user()->roles as $role)
                                            <li class="list-group-item text-center">
                                                <b> {{ $role->display_name }}</b>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>

{{--                                <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>--}}
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                        <!-- About Me Box -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">About Me</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                                <p class="text-muted">{{ auth()->user()->address }}</p>

                                <hr>
                                <strong><i class="fas fa-sign"></i> Signature</strong>
                                <div class="text-center">
                                    <img class="profile-user-img img-fluid" id="file-ip-2-preview"
                                         src="{{ \App\Facades\ViewHelper::getImagePath('user',auth()->user()->signature) }}"
                                         alt="User profile picture">
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link  @if(!$errors->has('old_password') || !$errors->has('password')) active @endif" href="#settings" data-toggle="tab">Profile Update</a></li>
                                    <li class="nav-item"><a class="nav-link @if($errors->has('old_password') || $errors->has('password')) active @endif"  href="#passwordTab" data-toggle="tab">Change Password</a></li>
                                </ul>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <!-- /.tab-pane -->
                                    <div class="tab-pane @if($errors->has('old_password') || $errors->has('password')) active @endif" id="passwordTab">
                                        <!-- The timeline -->
                                        {!! Form::open ([
                                            'url'=> route('admin.passwordUpdate'),
                                            'enctype'=>'multipart/form-data',
                                            'id'=>"changePassword-form"
                                        ])!!}
                                            <div class="form-group row">
                                                <label for="inputName" class="col-sm-2 col-form-label">Old Password</label>
                                                <div class="col-sm-10">
                                                    <div class="input-group mb-3">
                                                        <input type="password" class="{{ $errors->has('old_password')?'form-control is-invalid':'form-control' }}"
                                                               id="old-password" name="old_password" placeholder="Old Password">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <i class="ace-icon fa fa-eye" id="toggleOldPassword"></i>
                                                            </span>
                                                        </div>
                                                        @error('old_password')
                                                        <span class="help-block invalid-feedback">
                                                            <strong> {{ $message }} </strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputName" class="col-sm-2 col-form-label">New Password</label>
                                                <div class="col-sm-10">
                                                    <div class="input-group mb-3">
                                                        <input type="password" class="{{ $errors->has('password')?'form-control is-invalid':'form-control' }}" id="password"
                                                               name="password"
                                                               placeholder="New Password">
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
                                            <div class="form-group row">
                                                <label for="inputName" class="col-sm-2 col-form-label">Confirm Password</label>
                                                <div class="col-sm-10">
                                                    <div class="input-group mb-3">
                                                        <input type="password" class="{{ $errors->has('password_confirmation')?'form-control is-invalid':'form-control' }}" id="confirm-password"
                                                               name="password_confirmation"
                                                               placeholder="Confirm Password">
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
                                            <div class="form-group row">
                                                <div class="offset-sm-2 col-sm-10">
                                                    <button type="submit" class="btn btn-success">Change Password</button>
                                                </div>
                                            </div>
                                        {!! Form::close() !!}
                                    </div>
                                    <!-- /.tab-pane -->

                                    <div class="tab-pane @if(!$errors->has('old_password') || !$errors->has('password')) active @endif" id="settings">
                                        {!! Form::open ([
                                            'url'=> route('admin.profileUpdate'),
                                            'enctype'=>'multipart/form-data',
                                            'id'=>"add-form"
                                        ])!!}
                                            <div class="form-group row">
                                                <label for="inputName" class="col-sm-2 col-form -label">Name</label>
                                                <div class="col-sm-10">
                                                    {!! Form::text('name', auth()->user()->name,[
                                                      'class'=> $errors->has('name')?'form-control is-invalid':'form-control',
                                                      'placeholder'=>'super-admin',
                                                      ]) !!}
                                                    @error('name')
                                                    <span class="help-block invalid-feedback">
                                                        <strong> {{ $message }} </strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                                <div class="col-sm-10">
                                                    {!! Form::text('email',  auth()->user()->email,[
                                                         'class'=> $errors->has('email')?'form-control is-invalid':'form-control',
                                                         'placeholder'=>'Enter your email',
                                                    ]) !!}
                                                    @error('email')
                                                    <span class="help-block invalid-feedback">
                                                        <strong> {{ $message }} </strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputName2" class="col-sm-2 col-form-label">Address</label>
                                                <div class="col-sm-10">
                                                    {!! Form::text('address',  auth()->user()->address,[
                                                         'class'=> $errors->has('address')?'form-control is-invalid':'form-control',
                                                         'placeholder'=>'Enter your address',
                                                     ]) !!}
                                                    @error('address')
                                                    <span class="help-block invalid-feedback">
                                                        <strong> {{ $message }} </strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputName2" class="col-sm-2 col-form-label">Change Profile</label>
                                                <div class="col-sm-10">
                                                    {!! Form::file('profile_photo'  ,[
                                                         'class'=> $errors->has('profile_photo')?'form-control is-invalid':'form-control',
                                                         'placeholder'=>'Enter your address',
                                                          'id'=>'photo',
                                                          'onchange'=>"showPreview(event);",
                                                         'accept' => 'image/png, image/gif, image/jpeg'
                                                     ]) !!}
                                                    @error('profile_photo')
                                                    <span class="help-block invalid-feedback">
                                                        <strong> {{ $message }} </strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="inputName2" class="col-sm-2 col-form-label">Change Signature</label>
                                                <div class="col-sm-10">
                                                    {!! Form::file('main_signature'  ,[
                                                         'class'=> $errors->has('main_signature')?'form-control is-invalid':'form-control',
                                                         'placeholder'=>'Enter your address',
                                                          'id'=>'signature',
                                                          'onchange'=>"showSignaturePreview(event);",
                                                         'accept' => 'image/png, image/gif, image/jpeg'
                                                     ]) !!}
                                                    @error('main_signature')
                                                    <span class="help-block invalid-feedback">
                                                        <strong> {{ $message }} </strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="offset-sm-2 col-sm-10">
                                                    <button type="submit" onclick="@php
                                                        \Illuminate\Support\Facades\Session::put('settingTab','true')
                                                    @endphp document.getElementById('add-form')" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        {!! Form::close() !!}
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div><!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@section('js')
    <script>
        function showPreview(event){
            if(event.target.files.length > 0){
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("file-ip-1-preview");
                var photo = document.getElementById("photo");
                console.log(photo)
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
        const toggleOldPassword = document.querySelector('#toggleOldPassword');
        const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');

        const password = document.querySelector('#password');
        const confirm_password = document.querySelector('#confirm-password');
        const old_password = document.querySelector('#old-password');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });

        toggleOldPassword.addEventListener('click', function (e) {
            const s = old_password.getAttribute('type') === 'password' ? 'text' : 'password';
            old_password.setAttribute('type', s);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });

        toggleConfirmPassword.addEventListener('click', function (e) {
            const s = confirm_password.getAttribute('type') === 'password' ? 'text' : 'password';
            confirm_password.setAttribute('type', s);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
@endsection
