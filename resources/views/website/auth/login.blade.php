@extends('website.common.layout')
@section('title', 'Sign In')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center py-5">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-xl" style="border-radius: 16px; overflow: hidden;">
                <div class="card-header text-white text-center py-4" style="background: linear-gradient(135deg, var(--brand-green), var(--brand-green-hover)); border-bottom: none;">
                    <h3 class="mb-0 font-weight-bold"><i class="fas fa-user-circle mr-2"></i> Sign In / Register</h3>
                    <p class="mb-0 mt-2 text-white-50">Access Event Booking & Dashboard</p>
                </div>
                <div class="card-body p-4">
                    <!-- Global Alert Messages -->
                    <div id="auth-alert" class="alert d-none py-2 font-weight-bold" role="alert"></div>

                    <!-- Tabs for Auth Mode -->
                    <ul class="nav nav-pills nav-fill mb-4 p-1 bg-light rounded-pill" id="authTab" role="tablist" style="border: 1px solid #e9ecef;">
                        <li class="nav-item">
                            <a class="nav-link active rounded-pill font-weight-bold py-2 text-success" id="otp-tab" data-toggle="pill" href="#otp-pane" role="tab" style="transition: all 0.3s;">
                                <i class="fas fa-comment-dots mr-1"></i> OTP Code
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill font-weight-bold py-2 text-secondary" id="password-tab" data-toggle="pill" href="#password-pane" role="tab" style="transition: all 0.3s;">
                                <i class="fas fa-lock mr-1"></i> Password
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="authTabContent">
                        <!-- Pane 1: OTP Login Form -->
                        <div class="tab-pane fade show active" id="otp-pane" role="tabpanel" aria-labelledby="otp-tab">
                            <form id="otp-login-form">
                                @csrf
                                <div id="recaptcha-container"></div>
                                
                                <!-- Identifier Input Group -->
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark" id="identifier-label">Enter Mobile Number or Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend d-none" id="phone-prefix-addon">
                                            <span class="input-group-text font-weight-bold bg-light" style="font-size: 1.1rem; border-right: none;">+977</span>
                                        </div>
                                        <div class="input-group-prepend" id="icon-addon">
                                            <span class="input-group-text bg-white" style="border-right: none;"><i class="fas fa-envelope" id="identifier-icon"></i></span>
                                        </div>
                                        <input type="text" id="identifier-input" class="form-control form-control-lg font-weight-bold" style="border-left: none;" placeholder="e.g. 98XXXXXXXX or user@email.com" required autocomplete="off">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-success font-weight-bold" type="button" id="send-otp-btn">Send OTP</button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted" id="identifier-help">We will send a 6-digit verification code via SMS or Email.</small>
                                </div>

                                <!-- OTP Verification Input Group -->
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark">Enter 6-Digit Verification Code</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white"><i class="fas fa-key"></i></span>
                                        </div>
                                        <input type="text" id="otp-code-input" class="form-control form-control-lg text-center font-weight-bold" placeholder="------" maxlength="6" disabled style="letter-spacing: 5px;">
                                    </div>
                                    <small class="text-success font-weight-bold d-none" id="otp-success-msg"><i class="fas fa-check-circle"></i> Verification code matched!</small>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-success btn-lg btn-block font-weight-bold shadow-sm py-3" id="submit-auth-btn" disabled style="background: linear-gradient(135deg, var(--brand-green), var(--brand-green-hover)); border: none; border-radius: 30px;">
                                    Verify & Sign In <i class="fas fa-sign-in-alt ml-2"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Pane 2: Password Login Form -->
                        <div class="tab-pane fade" id="password-pane" role="tabpanel" aria-labelledby="password-tab">
                            <form id="password-login-form">
                                @csrf
                                
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark">Mobile Number or Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" id="password-identifier" name="identifier" class="form-control form-control-lg font-weight-bold" placeholder="e.g. +97798XXXXXXXX or user@email.com" required autocomplete="off">
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark">Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <input type="password" id="password-input" name="password" class="form-control form-control-lg font-weight-bold" placeholder="Enter password" required>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-success btn-lg btn-block font-weight-bold shadow-sm py-3" id="submit-password-btn" style="background: linear-gradient(135deg, var(--brand-green), var(--brand-green-hover)); border: none; border-radius: 30px;">
                                    Sign In with Password <i class="fas fa-sign-in-alt ml-2"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light text-center py-3">
                    <span class="text-muted">Need help? Contact support at bagmati.proscun@gmail.com</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // Tab switching styles
    $('#otp-tab').on('shown.bs.tab', function (e) {
        $(this).removeClass('text-secondary').addClass('text-success');
        $('#password-tab').removeClass('text-success').addClass('text-secondary');
        hideAlert();
    });
    $('#password-tab').on('shown.bs.tab', function (e) {
        $(this).removeClass('text-secondary').addClass('text-success');
        $('#otp-tab').removeClass('text-success').addClass('text-secondary');
        hideAlert();
    });

    const identifierInput = document.getElementById('identifier-input');
    const sendOtpBtn = document.getElementById('send-otp-btn');
    const otpCodeInput = document.getElementById('otp-code-input');
    const submitAuthBtn = document.getElementById('submit-auth-btn');
    const phonePrefix = document.getElementById('phone-prefix-addon');
    const iconAddon = document.getElementById('icon-addon');
    const identifierIcon = document.getElementById('identifier-icon');
    const identifierLabel = document.getElementById('identifier-label');
    const alertDiv = document.getElementById('auth-alert');

    let activeIdentifier = "";
    let activeType = "";

    // Real-time phone vs email input switching logic
    identifierInput.addEventListener('input', function(e) {
        let val = e.target.value.trim();
        
        // Check if value is pure numbers or looks like a phone number
        if (/^\d*$/.test(val) && val.length > 0) {
            // Show +977 prefix
            phonePrefix.classList.remove('d-none');
            iconAddon.classList.add('d-none');
            identifierLabel.innerText = "Enter 10-Digit Mobile Number";
            identifierInput.maxLength = 10;
        } else {
            // Show email envelope icon
            phonePrefix.classList.add('d-none');
            iconAddon.classList.remove('d-none');
            identifierIcon.className = "fas fa-envelope";
            identifierLabel.innerText = "Enter Mobile Number or Email";
            identifierInput.removeAttribute('maxlength');
        }
    });

    // Set up Firebase Recaptcha
    let recaptchaVerifier;
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
                
                recaptchaVerifier = new RecaptchaVerifier(auth, 'recaptcha-container', {
                    'size': 'invisible',
                    'callback': (response) => {
                        // reCAPTCHA solved
                    }
                });
                // Render it immediately to prepare it
                recaptchaVerifier.render();
            }
        } catch(e) {
            console.error("Firebase init failed:", e);
        }
    };
    
    // Call init
    initFirebaseRecaptcha();

    // Send OTP button logic
    sendOtpBtn.addEventListener('click', function(e) {
        e.preventDefault();
        let val = identifierInput.value.trim();
        if (!val) {
            showAlert("Please enter your Mobile Number or Email Address.", "alert-danger");
            return;
        }

        sendOtpBtn.disabled = true;
        sendOtpBtn.innerText = "Verifying Security...";
        hideAlert();

        const triggerAjax = (captchaToken = null) => {
            sendOtpBtn.innerText = "Sending...";
            
            // AJAX POST to send-otp
            fetch("{{ route('login.send-otp') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ identifier: val, captcha_token: captchaToken })
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(res => {
                if (res.status === 200) {
                    showAlert(res.body.message + " Check your " + (res.body.type === 'email' ? 'inbox' : 'messages') + ".", "alert-success");
                    activeIdentifier = res.body.identifier;
                    activeType = res.body.type;
                    
                    // Unlock OTP field and submit button
                    otpCodeInput.removeAttribute('disabled');
                    submitAuthBtn.removeAttribute('disabled');
                    otpCodeInput.focus();
                    
                    sendOtpBtn.innerText = "Resend OTP";
                    sendOtpBtn.disabled = false;
                } else {
                    showAlert(res.body.message || "Failed to request code.", "alert-danger");
                    sendOtpBtn.innerText = "Send OTP";
                    sendOtpBtn.disabled = false;
                }
            })
            .catch(err => {
                console.error("Auth Request Error:", err);
                showAlert("An error occurred. Please try again later.", "alert-danger");
                sendOtpBtn.innerText = "Send OTP";
                sendOtpBtn.disabled = false;
            });
        };

        if (recaptchaVerifier) {
            recaptchaVerifier.verify().then((token) => {
                triggerAjax(token);
            }).catch((error) => {
                console.error("Recaptcha error:", error);
                showAlert("Security check failed. Please try again.", "alert-danger");
                sendOtpBtn.innerText = "Send OTP";
                sendOtpBtn.disabled = false;
                
                // Clear and re-initialize recaptcha for next attempt
                try {
                    recaptchaVerifier.clear();
                    initFirebaseRecaptcha();
                } catch(e) {}
            });
        } else {
            // Firebase not configured, proceed without it
            triggerAjax();
        }
    });

    // Form submit / verify OTP logic
    document.getElementById('otp-login-form').addEventListener('submit', function(e) {
        e.preventDefault();
        let code = otpCodeInput.value.trim();
        if (!code || code.length !== 6) {
            showAlert("Please enter the 6-digit verification code.", "alert-danger");
            return;
        }

        submitAuthBtn.disabled = true;
        submitAuthBtn.innerText = "Verifying...";
        hideAlert();

        fetch("{{ route('login.verify-otp') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                identifier: activeIdentifier,
                otp_code: code
            })
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            if (res.status === 200) {
                showAlert(res.body.message, "alert-success");
                // Redirect user to the intended step/page
                setTimeout(() => {
                    window.location.href = res.body.redirect;
                }, 1000);
            } else {
                showAlert(res.body.message || "Invalid or expired code.", "alert-danger");
                submitAuthBtn.disabled = false;
                submitAuthBtn.innerText = "Verify & Sign In";
            }
        })
        .catch(err => {
            console.error("Verification Request Error:", err);
            showAlert("An error occurred during verification. Please try again.", "alert-danger");
            submitAuthBtn.disabled = false;
            submitAuthBtn.innerText = "Verify & Sign In";
        });
    });

    // Password login form submit logic
    document.getElementById('password-login-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const identifier = document.getElementById('password-identifier').value.trim();
        const password = document.getElementById('password-input').value;
        const submitBtn = document.getElementById('submit-password-btn');

        if (!identifier || !password) {
            showAlert("Please enter both identifier and password.", "alert-danger");
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerText = "Signing in...";
        hideAlert();

        fetch("{{ route('login.password') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                identifier: identifier,
                password: password
            })
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            if (res.status === 200) {
                showAlert(res.body.message, "alert-success");
                setTimeout(() => {
                    window.location.href = res.body.redirect;
                }, 1000);
            } else {
                showAlert(res.body.message || "Invalid credentials.", "alert-danger");
                submitBtn.disabled = false;
                submitBtn.innerText = "Sign In with Password";
            }
        })
        .catch(err => {
            console.error("Password Auth Request Error:", err);
            showAlert("An error occurred. Please try again.", "alert-danger");
            submitBtn.disabled = false;
            submitBtn.innerText = "Sign In with Password";
        });
    });

    function showAlert(msg, className) {
        alertDiv.innerText = msg;
        alertDiv.className = "alert py-2 font-weight-bold " + className;
        alertDiv.classList.remove('d-none');
    }

    function hideAlert() {
        alertDiv.classList.add('d-none');
    }
</script>
@endsection
