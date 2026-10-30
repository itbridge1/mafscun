<div class="col-md-12">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h5 class="sub-title mb-0 font-weight-bold text-dark">
                <i class="fas fa-search mr-2 text-primary"></i> Search Engine Optimization (SEO) &amp; Webmaster Verification
            </h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-1"></i> यी सेटिङहरूले वेबसाइटको <code>&lt;head&gt;</code> भित्रका मेटा ट्यागहरू (Meta Tags), Google Search Console प्रमाणीकरण, तथा Facebook, Twitter र WhatsApp मा सेयर गर्दा देखिने विवरणहरू व्यवस्थापन गर्दछ ।
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="google_site_verification">
                            <i class="fab fa-google text-danger mr-1"></i> Google Site Verification Code
                        </label>
                        {!! Form::text('google_site_verification', null,[
                              'class'=> $errors->has('google_site_verification')?'form-control is-invalid':'form-control',
                              'placeholder'=>"eg. ctGa90qa43WdsGLltz-5BelYcGLsDfAhPzaX69j7rXw",
                              'id' => 'meta_google_code'
                              ]) !!}
                        <small class="form-text text-muted">
                            Google Search Console बाट प्राप्त भएको meta tag को content भ्यालु (<code>&lt;meta name="google-site-verification" content="..."&gt;</code>) यहाँ राख्नुहोस् ।
                        </small>
                        @error('google_site_verification')
                        <span class="help-block invalid-feedback">
                            <strong> {{ $message }} </strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="keywords">
                            <i class="fas fa-tags text-success mr-1"></i> Meta Keywords
                        </label>
                        {!! Form::text('keywords', null,[
                              'class'=> $errors->has('keywords')?'form-control is-invalid':'form-control',
                              'placeholder'=>"eg. mafscun, makwanpur, sahkari, cooperative, बचत तथा ऋण सहकारी संघ",
                              'id' => 'meta_keywords_input'
                              ]) !!}
                        <small class="form-text text-muted">खोजी इन्जिनका लागि अल्पविराम (comma) ले छुट्याएर किवर्डहरू राख्नुहोस् ।</small>
                        @error('keywords')
                        <span class="help-block invalid-feedback">
                            <strong> {{ $message }} </strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="description">
                            <i class="fas fa-align-left text-primary mr-1"></i> SEO Meta Description (खोजी विवरण)
                        </label>
                        {!! Form::textarea('description', null,[
                              'class'=> $errors->has('description')?'form-control is-invalid':'form-control',
                              'rows' => 3,
                              'placeholder'=>"संस्थाको संक्षिप्त र स्पष्ट विवरण (१५० देखि १६० क्यारेक्टर सिफारिस गरिएको)...",
                              'id' => 'meta_desc_input'
                              ]) !!}
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">यो विवरण Google search result तथा Facebook, Twitter, Viber मा लिंक सेयर गर्दा देखिन्छ ।</small>
                            <small class="text-muted" id="meta_desc_counter"></small>
                        </div>
                        @error('description')
                        <span class="help-block invalid-feedback">
                            <strong> {{ $message }} </strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </div>

            <hr>

            <!-- Previews -->
            <h6 class="font-weight-bold text-dark mt-3 mb-3">
                <i class="fas fa-eye mr-1 text-info"></i> Search Engine &amp; Social Share Previews (पूर्वावलोकन)
            </h6>
            <div class="row">
                <!-- Google Snippet Preview -->
                <div class="col-md-6 mb-3">
                    <div class="border rounded p-3 bg-white shadow-sm" style="font-family: Arial, sans-serif;">
                        <span class="badge badge-light border text-muted mb-2"><i class="fab fa-google text-danger mr-1"></i> Google Search Preview</span>
                        <div class="d-flex align-items-center mb-1">
                            <img src="{{ ViewHelper::getImagePath('siteSetting', @$data['row']->site_favicon) }}" width="16" height="16" class="mr-2 rounded" onerror="this.src='{{ asset('images/no-image.png') }}'">
                            <span style="font-size: 0.85rem; color: #202124;">{{ url('/') }}</span>
                        </div>
                        <h5 class="mb-1" style="color: #1a0dab; font-size: 1.15rem; font-weight: 400; cursor: pointer; text-decoration: underline;" id="preview_title">
                            {{ @$data['row']->title ?? 'जिल्ला बचत तथा ऋण सहकारी संघ लि., मकवानपुर' }}
                        </h5>
                        <p class="mb-0 text-muted" style="font-size: 0.88rem; line-height: 1.4;" id="preview_desc">
                            {{ !empty(@$data['row']->description) ? @$data['row']->description : 'बचत तथा ऋण सहकारी संस्थाहरूको साझा सञ्जाल...' }}
                        </p>
                    </div>
                </div>

                <!-- Social Card Preview -->
                <div class="col-md-6 mb-3">
                    <div class="border rounded overflow-hidden shadow-sm bg-white">
                        <span class="badge badge-light border text-muted m-2"><i class="fab fa-facebook text-primary mr-1"></i> Social Card Preview</span>
                        <div style="background: #f0f2f5; height: 120px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <img src="{{ ViewHelper::getImagePath('siteSetting', @$data['row']->site_logo) }}" style="max-height: 90px; max-width: 90%; object-fit: contain;" onerror="this.src='{{ asset('images/no-image.png') }}'">
                        </div>
                        <div class="p-3">
                            <span class="text-uppercase text-muted d-block" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ parse_url(url('/'), PHP_URL_HOST) }}</span>
                            <strong class="d-block text-dark mt-1" style="font-size: 0.95rem;" id="social_preview_title">{{ @$data['row']->title ?? 'MAFSCUN' }}</strong>
                            <p class="text-muted mb-0 mt-1" style="font-size: 0.82rem; line-height: 1.3;" id="social_preview_desc">{{ \Illuminate\Support\Str::limit(@$data['row']->description, 100) }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var descInput = document.getElementById("meta_desc_input");
        var previewDesc = document.getElementById("preview_desc");
        var socialPreviewDesc = document.getElementById("social_preview_desc");
        var counter = document.getElementById("meta_desc_counter");

        if (descInput) {
            function updateMeta() {
                var val = descInput.value.trim();
                var len = val.length;
                if (counter) counter.innerText = len + " characters";
                if (previewDesc) {
                    previewDesc.innerText = len > 0 ? val : "बचत तथा ऋण सहकारी संस्थाहरूको साझा सञ्जाल...";
                }
                if (socialPreviewDesc) {
                    socialPreviewDesc.innerText = len > 0 ? (val.length > 100 ? val.substring(0, 100) + '...' : val) : "";
                }
            }
            descInput.addEventListener("input", updateMeta);
            updateMeta();
        }
    });
</script>
