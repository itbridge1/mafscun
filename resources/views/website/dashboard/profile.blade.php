@extends('website.common.layout')
@section('title', 'Edit Profile')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Dashboard Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-body p-4 text-center">
                    <div class="mb-3 position-relative d-inline-block">
                        @if($user->photo)
                            <img src="{{ asset('images/user/' . $user->photo) }}" class="rounded-circle shadow-sm border" style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="bg-success text-white rounded-circle shadow-sm d-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px; font-size: 2.5rem; font-weight: bold;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <h5 class="font-weight-bold mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small mb-3">
                        @if($user->phone)
                            <i class="fas fa-phone-alt mr-1"></i> {{ $user->phone }}
                        @else
                            <i class="fas fa-envelope mr-1"></i> {{ $user->email }}
                        @endif
                    </p>
                    <hr>
                    <div class="list-group list-group-flush text-left font-weight-bold">
                        <a href="{{ route('user.dashboard') }}" class="list-group-item list-group-item-action border-0 py-3 text-dark">
                            <i class="fas fa-ticket-alt mr-2"></i> My Bookings
                        </a>
                        <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action border-0 py-3 text-success active" style="background-color: rgba(40,167,69,0.08); border-radius: 8px;">
                            <i class="fas fa-user-edit mr-2"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="col-lg-9">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-user-edit mr-1"></i> Edit Profile Details</h5>
                </div>
                <div class="card-body p-4">
                    @if(session('success_message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-1"></i> {{ session('success_message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 pl-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control form-control-lg" value="{{ old('first_name', $user->first_name) }}" required placeholder="First Name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark">Middle Name</label>
                                    <input type="text" name="middle_name" class="form-control form-control-lg" value="{{ old('middle_name', $user->middle_name) }}" placeholder="Middle Name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control form-control-lg" value="{{ old('last_name', $user->last_name) }}" required placeholder="Last Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark">Address</label>
                                    <input type="text" name="address" class="form-control form-control-lg" value="{{ old('address', $user->address) }}" placeholder="Enter address">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark">Date of Birth (BS)</label>
                                    <input type="text" name="dob_bs" class="form-control form-control-lg dob-bs-input" value="{{ old('dob_bs', $user->dob_bs) }}" placeholder="YYYY-MM-DD" maxlength="10">
                                    <small class="text-muted">Format: YYYY-MM-DD (Hyphens are auto-filled) &bull; Month 01–12, Day 01–32</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark">Email Address
                                        @if($user->email && !$user->phone)
                                            <span class="badge badge-success ml-1"><i class="fas fa-check-circle"></i> Verified via Login</span>
                                        @elseif($user->email)
                                            <span class="badge badge-success ml-1"><i class="fas fa-check-circle"></i> Verified</span>
                                        @endif
                                    </label>
                                    @if($user->email && !$user->phone)
                                        {{-- User logged in via email — email is the verified identifier, disable it --}}
                                        <input type="email" name="email" class="form-control form-control-lg bg-light" value="{{ $user->email }}" readonly>
                                        <small class="text-muted">Email is your login identifier and cannot be changed here.</small>
                                    @else
                                        {{-- User logged in via phone — email can be added/changed but must be verified --}}
                                        <div id="email-field-wrapper">
                                            @if($user->email)
                                                <div class="input-group">
                                                    <input type="email" name="email" id="profile-email-input" class="form-control form-control-lg bg-light" value="{{ $user->email }}" readonly>
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-outline-danger btn-sm" id="change-email-btn">
                                                            <i class="fas fa-edit"></i> Change
                                                        </button>
                                                    </div>
                                                </div>
                                                <small class="text-muted">Click "Change" to update your email address (requires OTP verification).</small>
                                            @else
                                                <input type="email" name="email" id="profile-email-input" class="form-control form-control-lg" value="{{ old('email') }}" placeholder="Enter email to verify">
                                                <small class="text-muted">Adding an email requires OTP verification.</small>
                                            @endif
                                            <div id="email-otp-section" class="mt-2 {{ $user->email ? 'd-none' : '' }}">
                                                <div id="email-recaptcha-container" class="mt-2 mb-2"></div>
                                                <div class="input-group mt-2">
                                                    <input type="text" id="email-otp-input" class="form-control" placeholder="Enter 6-digit OTP" maxlength="6" style="letter-spacing:4px; font-weight:bold;">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-success" id="send-email-otp-btn">Send OTP</button>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="email_verified" id="email-verified-flag" value="0">
                                                <div id="email-otp-alert" class="mt-1"></div>
                                                <button type="button" class="btn btn-primary btn-sm mt-2 d-none" id="verify-email-otp-btn">Verify OTP & Save Email</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark">Phone Number
                                        @if($user->phone && !$user->email)
                                            <span class="badge badge-success ml-1"><i class="fas fa-check-circle"></i> Verified via Login</span>
                                        @elseif($user->phone)
                                            <span class="badge badge-success ml-1"><i class="fas fa-check-circle"></i> Verified</span>
                                        @endif
                                    </label>
                                    @if($user->phone && !$user->email)
                                        {{-- User logged in via phone — phone is the verified identifier, disable it --}}
                                        <input type="text" name="phone" class="form-control form-control-lg bg-light" value="{{ $user->phone }}" readonly>
                                        <small class="text-muted">Phone is your login identifier and cannot be changed here.</small>
                                    @else
                                        {{-- User logged in via email — phone can be added/changed but must be verified --}}
                                        <div id="phone-field-wrapper">
                                            @if($user->phone)
                                                <div class="input-group">
                                                    <input type="text" name="phone" id="profile-phone-input" class="form-control form-control-lg bg-light" value="{{ $user->phone }}" readonly>
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-outline-danger btn-sm" id="change-phone-btn">
                                                            <i class="fas fa-edit"></i> Change
                                                        </button>
                                                    </div>
                                                </div>
                                                <small class="text-muted">Click "Change" to update your phone number (requires OTP verification).</small>
                                            @else
                                                <input type="text" name="phone" id="profile-phone-input" class="form-control form-control-lg" value="{{ old('phone') }}" placeholder="Enter phone number to verify">
                                                <small class="text-muted">Adding a phone number requires OTP verification.</small>
                                            @endif
                                            <div id="phone-otp-section" class="mt-2 {{ $user->phone ? 'd-none' : '' }}">
                                                <div id="phone-recaptcha-container" class="mt-2 mb-2"></div>
                                                <div class="input-group mt-2">
                                                    <input type="text" id="phone-otp-input" class="form-control" placeholder="Enter 6-digit OTP" maxlength="6" style="letter-spacing:4px; font-weight:bold;">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-success" id="send-phone-otp-btn">Send OTP</button>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="phone_verified" id="phone-verified-flag" value="0">
                                                <div id="phone-otp-alert" class="mt-1"></div>
                                                <button type="button" class="btn btn-primary btn-sm mt-2 d-none" id="verify-phone-otp-btn">Verify OTP & Save Phone</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark">Profile Photo</label>
                                    <div class="custom-file">
                                        <input type="file" name="photo" class="custom-file-input" id="customFile" accept="image/*">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                    <small class="form-text text-muted">Leave empty to keep existing photo. Allowed types: jpeg, png, jpg, gif. Max: 2MB.</small>
                                </div>
                                <div id="photo-preview-container" class="mt-2 mb-4 d-none">
                                    <p class="font-weight-bold text-muted mb-1">Image Preview:</p>
                                    <img id="photo-preview" src="" alt="Preview" class="img-thumbnail shadow-sm" style="max-height: 150px; border-radius: 8px;">
                                </div>
                            </div>
                        </div>

                        <h5 class="mt-4 mb-3 border-bottom pb-2 font-weight-bold text-success"><i class="fas fa-key mr-1"></i> Change Password <small class="text-muted">(Optional)</small></h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark">New Password</label>
                                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Enter new password (min. 8 characters)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control form-control-lg" placeholder="Confirm new password">
                                </div>
                            </div>
                        </div>

                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-success btn-lg font-weight-bold shadow-sm px-5" style="border-radius: 30px;"><i class="fas fa-save mr-1"></i> Save Profile Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // Show selected file name in custom file inputs
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    // ---- Email OTP Verification Flow (for phone-login users) ----
    var otpSent = false;
    
    // Set up Firebase Recaptcha
    let emailRecaptchaVerifier, phoneRecaptchaVerifier;
    const initFirebaseRecaptcha = async () => {
        try {
            const { initializeApp } = await import("https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js");
            const { getAuth, RecaptchaVerifier } = await import("https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js");
            
            const firebaseConfig = {
                apiKey: "{{ env('FIREBASE_API_KEY') }}",
                authDomain: "{{ env('FIREBASE_AUTH_DOMAIN') }}",
                projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
                storageBucket: "{{ env('FIREBASE_STORAGE_BUCKET') }}",
                messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID') }}",
                appId: "{{ env('FIREBASE_APP_ID') }}"
            };
            
            if(firebaseConfig.apiKey) {
                const app = initializeApp(firebaseConfig);
                const auth = getAuth(app);
                
                // Visible normal widget requires user interaction before proceeding
                if ($('#email-recaptcha-container').length > 0) {
                    emailRecaptchaVerifier = new RecaptchaVerifier(auth, 'email-recaptcha-container', {
                        'size': 'invisible'
                    });
                    emailRecaptchaVerifier.render();
                }
                
                if ($('#phone-recaptcha-container').length > 0) {
                    phoneRecaptchaVerifier = new RecaptchaVerifier(auth, 'phone-recaptcha-container', {
                        'size': 'invisible'
                    });
                    phoneRecaptchaVerifier.render();
                }
            }
        } catch(e) {
            console.error("Firebase init failed:", e);
        }
    };
    
    // Call init
    initFirebaseRecaptcha();

    // "Change" button — unlock email field and show OTP section
    $('#change-email-btn').on('click', function() {
        $('#profile-email-input').prop('readonly', false).removeClass('bg-light').focus();
        $('#email-otp-section').removeClass('d-none');
        $(this).addClass('d-none');
    });

    // "Send OTP" button
    $('#send-email-otp-btn').on('click', function() {
        var emailVal = $('#profile-email-input').val().trim();
        var btn = $(this);

        if (!emailVal || !emailVal.includes('@')) {
            showEmailAlert('Please enter a valid email address first.', 'danger');
            return;
        }

        btn.prop('disabled', true).text('Verifying...');

        const triggerEmailAjax = (captchaToken = null) => {
            btn.text('Sending...');
            $.ajax({
                url: "{{ route('login.send-otp') }}",
                method: 'POST',
                data: { _token: "{{ csrf_token() }}", identifier: emailVal, captcha_token: captchaToken },
                success: function(res) {
                    if (res.success) {
                        showEmailAlert('OTP sent to ' + emailVal + '. Enter the code below.', 'success');
                        $('#verify-email-otp-btn').removeClass('d-none');
                        otpSent = true;
                        btn.prop('disabled', false).text('Resend OTP');
                    } else {
                        showEmailAlert(res.message || 'Failed to send OTP.', 'danger');
                        btn.prop('disabled', false).text('Send OTP');
                    }
                },
                error: function(xhr) {
                    var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error sending OTP.';
                    showEmailAlert(msg, 'danger');
                    btn.prop('disabled', false).text('Send OTP');
                }
            });
        };

        if (emailRecaptchaVerifier) {
            emailRecaptchaVerifier.verify().then((token) => {
                triggerEmailAjax(token);
            }).catch((error) => {
                console.error("Recaptcha error:", error);
                showEmailAlert("Security check failed. Please try again.", "danger");
                btn.prop('disabled', false).text('Send OTP');
            });
        } else {
            triggerEmailAjax();
        }
    });

    // "Verify OTP & Save Email" button
    $('#verify-email-otp-btn').on('click', function() {
        var otpCode = $('#email-otp-input').val().trim();
        var emailVal = $('#profile-email-input').val().trim();
        var btn = $(this);

        if (!otpCode || otpCode.length !== 6) {
            showEmailAlert('Please enter the 6-digit OTP code.', 'danger');
            return;
        }

        btn.prop('disabled', true).text('Verifying...');
        $.ajax({
            url: "{{ route('login.verify-otp') }}",
            method: 'POST',
            data: { _token: "{{ csrf_token() }}", identifier: emailVal, otp_code: otpCode },
            success: function(res) {
                if (res.success) {
                    showEmailAlert('<i class="fas fa-check-circle"></i> Email verified successfully! Save your profile to confirm.', 'success');
                    $('#email-verified-flag').val('1');
                    $('#profile-email-input').prop('readonly', true).addClass('bg-light');
                    $('#email-otp-input').prop('disabled', true);
                    btn.prop('disabled', true).text('Verified!');
                } else {
                    showEmailAlert(res.message || 'Invalid OTP. Please try again.', 'danger');
                    btn.prop('disabled', false).text('Verify OTP & Save Email');
                }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Verification failed.';
                showEmailAlert(msg, 'danger');
                btn.prop('disabled', false).text('Verify OTP & Save Email');
            }
        });
    });

    function showEmailAlert(msg, type) {
        $('#email-otp-alert').html('<div class="alert alert-' + type + ' py-2 mt-1 text-sm">' + msg + '</div>');
    }

    // ---- Phone OTP Verification Flow (for email-login users) ----
    // "Change" button — unlock phone field and show OTP section
    $('#change-phone-btn').on('click', function() {
        $('#profile-phone-input').prop('readonly', false).removeClass('bg-light').focus();
        $('#phone-otp-section').removeClass('d-none');
        $(this).addClass('d-none');
    });

    // "Send OTP" button for phone
    $('#send-phone-otp-btn').on('click', function() {
        var phoneVal = $('#profile-phone-input').val().trim();
        var btn = $(this);

        if (!phoneVal || phoneVal.length < 7) {
            showPhoneAlert('Please enter a valid phone number first.', 'danger');
            return;
        }

        btn.prop('disabled', true).text('Verifying...');

        const triggerPhoneAjax = (captchaToken = null) => {
            btn.text('Sending...');
            $.ajax({
                url: "{{ route('login.send-otp') }}",
                method: 'POST',
                data: { _token: "{{ csrf_token() }}", identifier: phoneVal, captcha_token: captchaToken },
                success: function(res) {
                    if (res.success) {
                        showPhoneAlert('OTP sent to ' + phoneVal + '. Enter the code below.', 'success');
                        $('#verify-phone-otp-btn').removeClass('d-none');
                        btn.prop('disabled', false).text('Resend OTP');
                    } else {
                        showPhoneAlert(res.message || 'Failed to send OTP.', 'danger');
                        btn.prop('disabled', false).text('Send OTP');
                    }
                },
                error: function(xhr) {
                    var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error sending OTP.';
                    showPhoneAlert(msg, 'danger');
                    btn.prop('disabled', false).text('Send OTP');
                }
            });
        };

        if (phoneRecaptchaVerifier) {
            phoneRecaptchaVerifier.verify().then((token) => {
                triggerPhoneAjax(token);
            }).catch((error) => {
                console.error("Recaptcha error:", error);
                showPhoneAlert("Security check failed. Please try again.", "danger");
                btn.prop('disabled', false).text('Send OTP');
            });
        } else {
            triggerPhoneAjax();
        }
    });

    // "Verify OTP & Save Phone" button
    $('#verify-phone-otp-btn').on('click', function() {
        var otpCode = $('#phone-otp-input').val().trim();
        var phoneVal = $('#profile-phone-input').val().trim();
        var btn = $(this);

        if (!otpCode || otpCode.length !== 6) {
            showPhoneAlert('Please enter the 6-digit OTP code.', 'danger');
            return;
        }

        btn.prop('disabled', true).text('Verifying...');
        $.ajax({
            url: "{{ route('login.verify-otp') }}",
            method: 'POST',
            data: { _token: "{{ csrf_token() }}", identifier: phoneVal, otp_code: otpCode },
            success: function(res) {
                if (res.success) {
                    showPhoneAlert('<i class="fas fa-check-circle"></i> Phone verified! Save your profile to confirm.', 'success');
                    $('#phone-verified-flag').val('1');
                    $('#profile-phone-input').prop('readonly', true).addClass('bg-light');
                    $('#phone-otp-input').prop('disabled', true);
                    btn.prop('disabled', true).text('Verified!');
                } else {
                    showPhoneAlert(res.message || 'Invalid OTP. Please try again.', 'danger');
                    btn.prop('disabled', false).text('Verify OTP & Save Phone');
                }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Verification failed.';
                showPhoneAlert(msg, 'danger');
                btn.prop('disabled', false).text('Verify OTP & Save Phone');
            }
        });
    });

    function showPhoneAlert(msg, type) {
        $('#phone-otp-alert').html('<div class="alert alert-' + type + ' py-2 mt-1 text-sm">' + msg + '</div>');
    }

    // Auto-format BS date: YYYY-MM-DD with month ≤12 and day ≤32
    function formatDobBs(input) {
        let raw = input.value.replace(/[^0-9]/g, '');
        if (raw.length > 8) raw = raw.slice(0, 8);
        let formatted = '';
        if (raw.length <= 4) {
            formatted = raw;
        } else if (raw.length <= 6) {
            let year  = raw.slice(0, 4);
            let month = raw.slice(4);
            if (parseInt(month) > 12) month = '12';
            formatted = year + '-' + month;
        } else {
            let year  = raw.slice(0, 4);
            let month = raw.slice(4, 6);
            let day   = raw.slice(6);
            if (parseInt(month) > 12) month = '12';
            if (parseInt(day) > 32) day = '32';
            formatted = year + '-' + month + '-' + day;
        }
        input.value = formatted;
    }

    document.addEventListener('input', function (e) {
        if (e.target && e.target.classList.contains('dob-bs-input')) {
            formatDobBs(e.target);
        }
    });
</script>
@endsection
