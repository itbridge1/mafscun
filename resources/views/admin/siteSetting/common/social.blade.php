<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h5 class="sub-title">
                Social Links
            </h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Facebook Link</label> <span class="red">*</span>
                        {!! Form::text('social[facebook_link]', null,[
                              'class'=> $errors->has('social.facebook_link')?'form-control is-invalid':'form-control',
                              'placeholder'=>"eg. Facebook Link",
                              ]) !!}
                        @error('social.facebook_link')
                        <span class="help-block invalid-feedback">
                                <strong> {{ $message }} </strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Twitter Link</label> <span class="red">*</span>
                        {!! Form::text('social[twitter_link]', null,[
                              'class'=> $errors->has('social.twitter_link')?'form-control is-invalid':'form-control',
                              'placeholder'=>"eg. Twitter Link",
                              ]) !!}
                        @error('social.twitter_link')
                        <span class="help-block invalid-feedback">
                           <strong> {{ $message }} </strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Youtube Link</label>
                        {!! Form::text('social[youtube_link]', null,[
                              'class'=> $errors->has('social.youtube_link')?'form-control is-invalid':'form-control',
                              'placeholder'=>"eg. Youtube Link",
                              ]) !!}
                        @error('social.youtube_link')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Insta Link</label>
                        {!! Form::text('social[insta_link]', null,[
                              'class'=> $errors->has('social.insta_link')?'form-control is-invalid':'form-control',
                              'placeholder'=>"eg. Insta Link",
                              ]) !!}
                        @error('social.insta_link')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> Powered By </label>
                        {!! Form::text('powered_by', null,[
                              'class'=> $errors->has('powered_by')?'form-control is-invalid':'form-control',
                              'placeholder'=>"eg. Powered By Text",
                              ]) !!}
                        @error('powered_by')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Powered By Link</label>
                        {!! Form::text('powered_by_link', null,[
                              'class'=> $errors->has('powered_by_link')?'form-control is-invalid':'form-control',
                              'placeholder'=>"eg. Powered By Text",
                              ]) !!}
                        @error('powered_by_link')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Keywords</label>
                        {!! Form::text('keywords', null,[
                              'class'=> $errors->has('keywords')?'form-control is-invalid':'form-control',
                              'placeholder'=>"eg. Keywords",
                              ]) !!}
                        @error('keywords')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>SEO Description</label>
                        {!! Form::textarea('description', null,[
                              'class'=> $errors->has('description')?'form-control summenote is-invalid':'form-control summenote',
                              'placeholder'=>"eg. Type Here.",
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
    </div>
</div>
