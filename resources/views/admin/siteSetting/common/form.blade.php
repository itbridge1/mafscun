<div class="card-body">
    <div class="row">
        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab"
                   data-toggle="pill" href="#custom-content-below-home"
                   role="tab" aria-controls="custom-content-below-home"
                   aria-selected="true">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="upload-tab"
                   data-toggle="pill" href="#upload-content"
                   role="tab" aria-controls="upload-content"
                   aria-selected="false">
                    Upload
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="social-tab"
                   data-toggle="pill" href="#social-tab-content"
                   role="tab" aria-controls="social-tab-content"
                   aria-selected="false">
                    Social
                    @if($errors->has('social.facebook_link')
                    || $errors->has('social.twitter_link')
                    || $errors->has('social.youtube_link')
                    || $errors->has('powered_by_link')
                    || $errors->has('social.insta_link'))
                        <span class="red">*</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="meta-tab"
                   data-toggle="pill" href="#meta-tab-content"
                   role="tab" aria-controls="meta-tab-content"
                   aria-selected="false">
                    <i class="fas fa-search mr-1 text-primary"></i> SEO &amp; Header Meta
                    @if($errors->has('keywords')
                    || $errors->has('description')
                    || $errors->has('google_site_verification'))
                        <span class="red">*</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="settings-tab"
                   data-toggle="pill" href="#content-settings"
                   role="tab" aria-controls="content-settings"
                   aria-selected="false">Settings</a>
            </li>
          
        </ul>
        <div class="tab-content" id="custom-content-below-tabContent">
            <div class="tab-pane fade active show" id="custom-content-below-home" role="tabpanel" aria-labelledby="home-tab">
               @include($_view_path.'.common.home')
               @include($_view_path.'.common.statistics')
            </div>
            <div class="tab-pane fade" id="upload-content" role="tabpanel" aria-labelledby="upload-tab">
                @include($_view_path.'.common.upload')
            </div>
             
            <div class="tab-pane fade" id="social-tab-content" role="tabpanel" aria-labelledby="social-tab">
                @include($_view_path.'.common.social')
            </div>
            <div class="tab-pane fade" id="meta-tab-content" role="tabpanel" aria-labelledby="meta-tab">
                @include($_view_path.'.common.meta')
            </div>
            <div class="tab-pane fade" id="content-settings" role="tabpanel" aria-labelledby="settings-tab">
                @include($_view_path.'.common.setting')
            </div>
        </div>
    </div>
</div>
<!-- /.card-body -->
<div class="card-footer">
    <button type="submit" class="btn btn-primary">{{$button}}</button>
</div>
@section('formJs')
    @include($_view_path.'.common.form-scripts')
@endsection
