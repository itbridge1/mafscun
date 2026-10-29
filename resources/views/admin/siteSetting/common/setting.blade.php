<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h5 class="sub-title">AboutUs Setting</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>AboutUs HomePage 1</label> <span class="red">*</span>
                        {!! Form::select('about_1', $data['aboutUs'],null,[
                              'class'=> $errors->has('about_1')?'form-control is-invalid':'form-control',
                              'placeholder'=>"--SELECT ABOUT US PAGE--",
                        ]) !!}
                        @error('about_1')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Commitment From CEO</label>
                        {!! Form::select('about_2', $data['aboutUs'],null,[
                              'class'=> $errors->has('about_2')?'form-control is-invalid':'form-control',
                              'placeholder'=>"--SELECT ABOUT US PAGE--",
                        ]) !!}
                        @error('about_2')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Chairman Voice</label> <span class="red">*</span>
                        {!! Form::select('about_3', $data['aboutUs'],null,[
                              'class'=> $errors->has('about_3')?'form-control is-invalid':'form-control',
                              'placeholder'=>"--SELECT ABOUT US PAGE--",
                        ]) !!}
                        @error('about_3')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>AboutUs HomePage 4</label> <span class="red">*</span>
                        {!! Form::select('about_4', $data['aboutUs'],null,[
                              'class'=> $errors->has('about_4')?'form-control is-invalid':'form-control',
                              'placeholder'=>"--SELECT ABOUT US PAGE--",
                        ]) !!}
                        @error('about_4')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h5 class="sub-title">Footer Setting</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>First Footer</label> <span class="red">*</span>
                        {!! Form::select('footer_1', $data['menus'],null,[
                              'class'=> $errors->has('footer_1')?'form-control is-invalid':'form-control',
                              'placeholder'=>"--SELECT FIRST FOOTER MENU--",
                        ]) !!}
                        @error('footer_1')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Second Footer</label> <span class="red">*</span>
                        {!! Form::select('footer_2', $data['menus'],null,[
                              'class'=> $errors->has('footer_2')?'form-control is-invalid':'form-control',
                              'placeholder'=>"--SELECT SECOND FOOTER MENU--",
                        ]) !!}
                        @error('footer_2')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Third Footer</label> <span class="red">*</span>
                        {!! Form::select('footer_3', $data['menus'],null,[
                              'class'=> $errors->has('footer_3')?'form-control is-invalid':'form-control',
                              'placeholder'=>"--SELECT THIRD FOOTER MENU--",
                        ]) !!}
                        @error('footer_3')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Fourth Footer</label>
                        {!! Form::select('footer_4', $data['menus'],null,[
                              'class'=> $errors->has('footer_4')?'form-control is-invalid':'form-control',
                              'placeholder'=>"--SELECT FOURTH FOOTER MENU--",
                        ]) !!}
                        @error('footer_4')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Statistics Text</label>
                        {!! Form::text('statistics_text',null,[
                              'class'=> $errors->has('statistics_text')?'form-control is-invalid':'form-control',
                              'placeholder'=>"Eg. Type Here",
                        ]) !!}
                        @error('statistics_text')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Statistics Title for chart</label>
                        {!! Form::text('copyright_text',null,[
                              'class'=> $errors->has('copyright_text')?'form-control is-invalid':'form-control',
                              'placeholder'=>"Eg. Type Here",
                        ]) !!}
                        @error('copyright_text')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Footer Text</label>
                        {!! Form::textarea('footer_text',null,[
                              'class'=> $errors->has('footer_text')?'form-control summernote is-invalid':'form-control summernote',
                              'placeholder'=>"Eg. Type Here",
                        ]) !!}
                        @error('footer_text')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
