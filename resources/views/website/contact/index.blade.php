@extends('website.common.layout')
@section('title', 'Contact Us')
@section('meta')
    @include('website.common.meta', [
    '_title' => "Contact Us",
    '_detail' => ($_site_setting->location ?? '') . ' ' . ($_site_setting->phone ?? '') . ' ' . ($_site_setting->email ?? ''),
    '_folder' => 'siteSetting',
    '_photo' => $_site_setting->site_logo,
])
@endsection

@section('content')
    <!-- Unified Page Heading & Breadcrumb -->
    @include('website.common.page-heading')

    <div class="container-xl my-4 pb-5">
        <div class="bg-white rounded-4 border overflow-hidden shadow-sm">
            <div class="row g-0">
                <!-- Left: Contact Details Column -->
                <div class="col-lg-5 p-4 p-md-5 d-flex flex-column justify-content-between" style="background: linear-gradient(135deg, #075b3b, #08784b); color: #ffffff;">
                    <div>
                        <span class="badge badge-light px-3 py-1 rounded-pill small mb-3 text-success font-weight-bold" style="background: rgba(255,255,255,0.92);">
                            GET IN TOUCH
                        </span>
                        <h2 class="fw-bold mb-3 text-white" style="font-size: 1.75rem; letter-spacing: -.02em;">
                            We'd Love to Hear From You
                        </h2>
                        <p class="mb-4" style="color: rgba(255,255,255,0.85); font-size: 0.95rem; line-height: 1.7;">
                            Have questions about our cooperative services, membership, or events? Reach out to us through any of the channels below.
                        </p>

                        <div class="d-flex flex-column gap-3 mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0" style="width: 44px; height: 44px; background: rgba(255,255,255,0.18); color: #ffffff;">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <div class="small text-white-50">Our Office</div>
                                    <div class="font-weight-bold text-white">{{ $_site_setting->location ?? "Office Address" }}</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0" style="width: 44px; height: 44px; background: rgba(255,255,255,0.18); color: #ffffff;">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div>
                                    <div class="small text-white-50">Phone Number</div>
                                    <div class="font-weight-bold text-white">
                                        <a href="tel:{{ $_site_setting->phone }}" class="text-white text-decoration-none">
                                            {{ $_site_setting->phone ?? "N/A" }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0" style="width: 44px; height: 44px; background: rgba(255,255,255,0.18); color: #ffffff;">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <div class="small text-white-50">Email Address</div>
                                    <div class="font-weight-bold text-white">
                                        <a href="mailto:{{ $_site_setting->email }}" class="text-white text-decoration-none">
                                            {{ $_site_setting->email ?? "info@example.org" }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-top" style="border-color: rgba(255,255,255,0.2) !important;">
                        <span class="small text-white-50 d-block mb-2">Connect with our network</span>
                        <div class="d-flex gap-2">
                            @if(isset($_site_setting->social['facebook_link']) && !empty($_site_setting->social['facebook_link']))
                                <a href="{{ $_site_setting->social['facebook_link'] }}" target="_blank" class="btn btn-sm btn-light rounded-circle mr-2 d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; color: #08784b;" aria-label="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            @endif
                            @if(isset($_site_setting->social['youtube_link']) && !empty($_site_setting->social['youtube_link']))
                                <a href="{{ $_site_setting->social['youtube_link'] }}" target="_blank" class="btn btn-sm btn-light rounded-circle mr-2 d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; color: #08784b;" aria-label="YouTube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right: Contact Form Column -->
                <div class="col-lg-7 p-4 p-md-5 bg-white">
                    <span class="statistics-eyebrow">MESSAGE US</span>
                    <h3 class="fw-bold mb-3" style="color: var(--public-ink); font-size: 1.5rem;">Send a Message</h3>
                    <p class="text-muted small mb-4">Fill in your information and our team will get back to you promptly.</p>

                    <form action="{{ route('contact.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="font-weight-bold text-dark small">Your Name *</label>
                                <input type="text" class="form-control rounded-3 {{$errors->has('name')?'is-invalid':''}}" name="name"
                                       id="name" required value="{{ old('name') }}" placeholder="Full Name">
                                @error('name')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="font-weight-bold text-dark small">Phone Number *</label>
                                <input type="text" class="form-control rounded-3 {{$errors->has('phone')?'is-invalid':''}}" name="phone"
                                       id="phone" value="{{ old('phone') }}" placeholder="98XXXXXXXX" max="15" required>
                                @error('phone')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="font-weight-bold text-dark small">Email Address *</label>
                                <input type="email" class="form-control rounded-3 {{$errors->has('email')?'is-invalid':''}}"
                                       name="email" id="email" required value="{{ old('email') }}" placeholder="example@email.com">
                                @error('email')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="subject" class="font-weight-bold text-dark small">Subject</label>
                                <input type="text" class="form-control rounded-3 {{$errors->has('subject')?'is-invalid':''}}" name="subject"
                                       id="subject" value="{{ old('subject') }}" placeholder="Topic / Inquiry">
                                @error('subject')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="message" class="font-weight-bold text-dark small">Your Message *</label>
                                <input type="hidden" name="captcha" value="{{ old('captcha') }}">
                                <textarea class="form-control rounded-3 {{$errors->has('message')?'is-invalid':''}}"
                                          name="message" id="message" rows="4" placeholder="How can we help you?" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary px-5 py-2 font-weight-bold rounded-pill shadow-sm" style="background: var(--public-green); border-color: var(--public-green);">
                                <i class="fas fa-paper-plane mr-2"></i> Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('website.home.affiliation')
@endsection

@section('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(\Illuminate\Support\Facades\Session::has('success_message'))
            Swal.fire({
                icon: 'success',
                title: 'Thank you',
                text: '{{ \Illuminate\Support\Facades\Session::get('success_message') }}',
                footer: '<a href="{{ route('index') }}"><i class="fa fa-home"></i> Go to Home Page</a>'
            })
        @endif
        @if(\Illuminate\Support\Facades\Session::has('error_message'))
            Swal.fire({
                icon: 'error',
                title: 'Sorry',
                text: '{{ \Illuminate\Support\Facades\Session::get('error_message') }}',
                footer: '<a href="{{ route('index') }}"><i class="fa fa-home"></i> Go to Home Page</a>'
            })
        @endif
    </script>
@endsection
