<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h5 class="sub-title">Site Information </h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Title </label> <span class="red">*</span>
                        {!! Form::text('title', null,[
                              'class'=> $errors->has('title')?'form-control is-invalid':'form-control',
                              'placeholder'=>"Type Site Name here..",
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
                        <label for="title">Sub Title </label> <span class="red">*</span>
                        {!! Form::text('sub_title', null,[
                              'class'=> $errors->has('sub_title')?'form-control is-invalid':'form-control',
                              'placeholder'=>"Type Site Sub Title here..",
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
                        <label for="title">Company Alias </label> <span class="red">*</span>
                        {!! Form::text('company_alias', null,[
                              'class'=> $errors->has('company_alias')?'form-control is-invalid':'form-control',
                              'placeholder'=>"Type Site Name Shortcut..",
                              ]) !!}
                        @error('company_alias')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email </label> <span class="red">*</span>
                        {{--                                <small>(We will send an email after verifying with identity card in this email address)</small>--}}
                        {!! Form::text('email', null,[
                              'class'=> $errors->has('email')?'form-control is-invalid':'form-control',
                              'placeholder'=>"Type Site Email here..",
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
                        <label for="phone">Phone No</label> <span class="red">*</span>
                        {!! Form::text('phone', null,[
                             'class'=> $errors->has('phone')?'form-control is-invalid':'form-control',
                             'placeholder'=>'eg. 9814525625',
                             ]) !!}
                        @error('phone')
                        <span class="help-block invalid-feedback">
                                 <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>PAN No</label> <span class="red">*</span>
                        {!! Form::text('pan_no', null,[
                             'class'=> $errors->has('pan_no')?'form-control is-invalid':'form-control',
                             'placeholder'=>'eg. 15825625',
                             ]) !!}
                        @error('pan_no')
                        <span class="help-block invalid-feedback">
                                 <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Reg No</label> <span class="red">*</span>
                        {!! Form::text('reg_no', null,[
                             'class'=> $errors->has('reg_no')?'form-control is-invalid':'form-control',
                             'placeholder'=>'eg. Darta No.',
                             ]) !!}
                        @error('reg_no')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Location</label> <span class="red">*</span>
                        {!! Form::text('location', null,[
                             'class'=> $errors->has('location')?'form-control is-invalid':'form-control',
                             'placeholder'=>'eg. Banepa -3, Kavre',
                             ]) !!}
                        @error('location')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    @if(isset($errors))
                        {{ $errors }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="card mt-3">
        <div class="card-body">
            <h5 class="sub-title">Public Site Colors</h5>
            <p class="text-muted">These colors update the public website, including page headings and breadcrumbs.</p>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="primary_color">Primary color</label>
                    <input id="primary_color" name="primary_color" type="color" class="form-control" style="height:48px;padding:5px" value="{{ old('primary_color', $data['row']->primary_color ?? '#08784b') }}">
                    @error('primary_color') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label for="secondary_color">Secondary color</label>
                    <input id="secondary_color" name="secondary_color" type="color" class="form-control" style="height:48px;padding:5px" value="{{ old('secondary_color', $data['row']->secondary_color ?? '#00acf1') }}">
                    @error('secondary_color') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </div>
</div>
