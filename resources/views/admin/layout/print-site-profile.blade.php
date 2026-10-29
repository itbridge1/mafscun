<div class="row">
    <div class="col-3 text-center">
        <img width="120" height="80" src="{{ \App\Facades\ViewHelper::getImagePath('siteProfile',$_site_profile->logo) }}" alt="" class="logo">
    </div>
    <div class="col-6 text-center">
        {!! $_site_profile->header_text !!}
    </div>
    <div class="col-3"></div>
</div>
