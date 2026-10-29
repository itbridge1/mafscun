@extends('website.common.layout')
@section('title', 'Set Your Password')

@section('css')
@parent
<style>
    .set-password-wrapper {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        padding: 40px 16px;
    }
    .set-password-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.10);
        padding: 44px 40px 36px;
        width: 100%;
        max-width: 460px;
    }
    .set-password-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, #16a34a, #22c55e);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    .set-password-card h3 {
        font-weight: 800;
        color: #14532d;
        font-size: 1.5rem;
        text-align: center;
        margin-bottom: 6px;
    }
    .set-password-card .subtitle {
        color: #6b7280;
        font-size: 0.9rem;
        text-align: center;
        margin-bottom: 28px;
        line-height: 1.5;
    }
    .set-password-card .form-control {
        border-radius: 8px;
        border: 1.5px solid #d1fae5;
        padding: 10px 14px;
        font-size: 0.95rem;
        transition: border-color 0.2s;
    }
    .set-password-card .form-control:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34,197,94,0.15);
        outline: none;
    }
    .btn-set-password {
        background: linear-gradient(135deg, #16a34a, #22c55e);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 12px;
        font-weight: 700;
        font-size: 1rem;
        width: 100%;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.1s;
    }
    .btn-set-password:hover {
        opacity: 0.92;
        transform: translateY(-1px);
        color: #fff;
    }
    .notice-banner {
        background: #fef3c7;
        border: 1.5px solid #fde68a;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 0.85rem;
        color: #92400e;
        margin-bottom: 22px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    .password-strength-bar {
        height: 4px;
        border-radius: 4px;
        margin-top: 6px;
        transition: all 0.3s;
        background: #e5e7eb;
    }
    .strength-weak   { background: #ef4444; width: 30%; }
    .strength-fair   { background: #f59e0b; width: 60%; }
    .strength-strong { background: #22c55e; width: 100%; }
</style>
@endsection

@section('content')
<div class="set-password-wrapper">
    <div class="set-password-card">
        <!-- Icon -->
        <div class="set-password-icon">
            <i class="fas fa-lock" style="color:#fff; font-size:1.6rem;"></i>
        </div>

        <h3>Set Your Password</h3>
        <p class="subtitle">You registered via OTP. Please set a password so you can also log in with it next time.</p>

        <div class="notice-banner">
            <i class="fas fa-info-circle mt-1"></i>
            <span>Your account is active and you can still log in via OTP after setting a password. This step is required to continue.</span>
        </div>

        @if(session('error_message'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error_message') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.set-password.store') }}" method="POST" id="setPasswordForm">
            @csrf

            <div class="form-group mb-3">
                <label class="font-weight-bold text-dark" for="name" style="font-size:0.9rem;">Full Name <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                    placeholder="Enter your full name" required value="{{ old('name', auth()->user()->name) }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label class="font-weight-bold text-dark" for="phone" style="font-size:0.9rem;">Phone Number <span class="text-danger">*</span></label>
                <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror"
                    placeholder="Enter your phone number" required value="{{ old('phone', auth()->user()->phone) }}"
                    {{ auth()->user()->phone ? 'readonly' : '' }}>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label class="font-weight-bold text-dark" for="email" style="font-size:0.9rem;">Email Address <span class="text-danger">*</span></label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    placeholder="Enter your email address" required value="{{ old('email', auth()->user()->email) }}"
                    {{ auth()->user()->email ? 'readonly' : '' }}>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label class="font-weight-bold text-dark" for="password" style="font-size:0.9rem;">New Password <span class="text-danger">*</span></label>
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="Minimum 8 characters" required minlength="8" autocomplete="new-password">
                <div class="password-strength-bar" id="strengthBar"></div>
                <small class="text-muted" id="strengthLabel"></small>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label class="font-weight-bold text-dark" for="password_confirmation" style="font-size:0.9rem;">Confirm Password <span class="text-danger">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                    placeholder="Re-enter your password" required minlength="8" autocomplete="new-password">
                <small class="text-muted" id="matchHint" style="display:none;"></small>
            </div>

            <button type="submit" class="btn-set-password">
                <i class="fas fa-check-circle mr-2"></i> Set Password &amp; Continue
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('logout') }}" class="text-muted" style="font-size:0.85rem;">
                <i class="fas fa-sign-out-alt mr-1"></i> Logout instead
            </a>
        </div>
    </div>
</div>
@endsection

@section('js')
@parent
<script>
    const pwInput  = document.getElementById('password');
    const confInput = document.getElementById('password_confirmation');
    const bar      = document.getElementById('strengthBar');
    const label    = document.getElementById('strengthLabel');
    const matchHint = document.getElementById('matchHint');

    pwInput.addEventListener('input', function () {
        const val = this.value;
        bar.className = 'password-strength-bar';
        if (val.length === 0) { label.textContent = ''; return; }
        if (val.length < 8) {
            bar.classList.add('strength-weak');
            label.textContent = 'Too short';
            label.style.color = '#ef4444';
        } else if (val.length < 12 || !/[^a-zA-Z0-9]/.test(val)) {
            bar.classList.add('strength-fair');
            label.textContent = 'Fair — add symbols or numbers to strengthen';
            label.style.color = '#f59e0b';
        } else {
            bar.classList.add('strength-strong');
            label.textContent = 'Strong password ✓';
            label.style.color = '#16a34a';
        }
    });

    confInput.addEventListener('input', function () {
        matchHint.style.display = 'inline';
        if (this.value === pwInput.value) {
            matchHint.textContent = '✓ Passwords match';
            matchHint.style.color = '#16a34a';
        } else {
            matchHint.textContent = '✗ Passwords do not match';
            matchHint.style.color = '#ef4444';
        }
    });
</script>
@endsection
