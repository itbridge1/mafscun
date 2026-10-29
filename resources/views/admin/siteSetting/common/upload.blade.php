<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h5 class="sub-title">Uploads</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="sub-title">Site Logo </h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="photo">Existing Logo</label>
                                    <div class="preview">
                                        <img width="200" height="200"
                                             src="{{ ViewHelper::getImagePath(@$_folder,@$data['row']->site_logo) }}"
                                             class="img-responsive" id="file-ip-1-preview">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="photo_upload">Upload Logo</label> <span class="red">*</span>
                                    {!! Form::file('main_logo',[
                                        'class'=> $errors->has('main_logo')?'form-control btn btn-info btn-sm is-invalid':'form-control btn btn-info btn-sm',
                                        'id'=>'photo',
                                        'onchange'=>"showPreview(event);",
                                        'accept'=>'image/png, image/gif, image/jpeg'
                                     ]) !!}
                                    @error('main_logo')
                                    <span class="help-block invalid-feedback">
                                        <strong> {{ $message }} </strong>
                                </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="sub-title">Site Favicon</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="photo">Existing Favicon</label>
                                    <div class="preview">
                                        <img width="200" height="200"
                                             src="{{ ViewHelper::getImagePath(@$_folder,@$data['row']->site_favicon) }}"
                                             class="img-responsive" id="favicon-preview">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="photo_upload">Upload Company Logo</label>
                                    {!! Form::file('main_favicon',[
                                        'class'=> $errors->has('main_favicon')?'form-control btn btn-info btn-sm is-invalid':'form-control btn btn-info btn-sm',
                                        'id'=>'favicon',
                                        'onchange'=>"showFaviconPreview(event);",
                                        'accept'=>'image/png, image/gif, image/jpeg, image/x-icon'
                                     ]) !!}
                                    @error('main_favicon')
                                    <span class="help-block invalid-feedback">
                                        <strong> {{ $message }} </strong>
                                </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="sub-title">Company Logo</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="photo">Existing Company Logo</label>
                                    <div class="preview">
                                        <img width="200" height="200"
                                             src="{{ ViewHelper::getImagePath(@$_folder,@$data['row']->company_logo) }}"
                                             class="img-responsive" id="company_logo_preview">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="photo_upload">Upload Statistics Map</label>
                                    {!! Form::file('main_company_logo',[
                                        'class'=> $errors->has('main_company_logo')?'form-control btn btn-info btn-sm is-invalid':'form-control btn btn-info btn-sm',
                                        'id'=>'company_logo',
                                        'onchange'=>"showCompanyLogoPreview(event);",
                                        'accept'=>'image/png, image/gif, image/jpeg'
                                     ]) !!}
                                    @error('main_company_logo')
                                    <span class="help-block invalid-feedback">
                                        <strong> {{ $message }} </strong>
                                </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="sub-title">StatMap</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="photo">Existing StatMap</label>
                                    <div class="preview">
                                        <img width="200" height="200"
                                             src="{{ ViewHelper::getImagePath(@$_folder,@$data['row']->stat_map) }}"
                                             class="img-responsive" id="stat_map_preview">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="photo_upload">Upload Favicon</label> <span class="red">*</span>
                                    {!! Form::file('main_stat_map',[
                                        'class'=> $errors->has('main_stat_map')?'form-control btn btn-info btn-sm is-invalid':'form-control btn btn-info btn-sm',
                                        'id'=>'stat_map',
                                        'onchange'=>"showStatMapPreview(event);",
                                        'accept'=>'image/png, image/gif, image/jpeg'
                                     ]) !!}
                                    @error('main_stat_map')
                                    <span class="help-block invalid-feedback">
                                        <strong> {{ $message }} </strong>
                                </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="sub-title">Powered By Logo</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="photo">Existing Company Logo</label>
                                    <div class="preview">
                                        <img width="200" height="200"
                                             src="{{ ViewHelper::getImagePath(@$_folder,@$data['row']->powered_by_logo) }}"
                                             class="img-responsive" id="powered_by_logo_preview">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="photo_upload">Upload PoweredByLogo</label> <span class="red">*</span>
                                    {!! Form::file('main_powered_by_logo',[
                                        'class'=> $errors->has('main_powered_by_logo')?'form-control btn btn-info btn-sm is-invalid':'form-control btn btn-info btn-sm',
                                        'id'=>'powered_by_logo',
                                        'onchange'=>"showPoweredByLogoPreview(event);",
                                        'accept'=>'image/png, image/gif, image/jpeg'
                                     ]) !!}
                                    @error('main_powered_by_logo')
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
        </div>
    </div>
</div>
