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

            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="card mt-3">
        <div class="card-header bg-light">
            <h5 class="sub-title mb-0 font-weight-bold text-dark"><i class="fas fa-search mr-2 text-primary"></i> Search Engine Optimization (SEO) &amp; Webmaster Verification</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="google_site_verification">Google Site Verification Code</label>
                        {!! Form::text('google_site_verification', null,[
                              'class'=> $errors->has('google_site_verification')?'form-control is-invalid':'form-control',
                              'placeholder'=>"eg. ctGa90qa43WdsGLltz-5BelYcGLsDfAhPzaX69j7rXw",
                              ]) !!}
                        <small class="form-text text-muted">Enter your Google Search Console verification token (from meta tag content attribute).</small>
                        @error('google_site_verification')
                        <span class="help-block invalid-feedback">
                            <strong> {{ $message }} </strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="keywords">Meta Keywords</label>
                        {!! Form::text('keywords', null,[
                              'class'=> $errors->has('keywords')?'form-control is-invalid':'form-control',
                              'placeholder'=>"eg. mafscun, makwanpur, hetauda, sahkari, cooperative",
                              ]) !!}
                        <small class="form-text text-muted">Comma-separated keywords for search engines.</small>
                        @error('keywords')
                        <span class="help-block invalid-feedback">
                            <strong> {{ $message }} </strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="description">SEO Meta Description</label>
                        {!! Form::textarea('description', null,[
                              'class'=> $errors->has('description')?'form-control is-invalid':'form-control',
                              'rows' => 3,
                              'placeholder'=>"Type clear, plain text summary of the organization (150-160 characters recommended).",
                              ]) !!}
                        <small class="form-text text-muted">This plain-text description appears in Google search results and social share previews (Facebook, Twitter, LinkedIn).</small>
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
