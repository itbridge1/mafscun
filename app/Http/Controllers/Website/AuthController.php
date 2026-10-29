<?php

namespace App\Http\Controllers\Website;

use App\Models\OtpRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends BaseController
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->intended('/home');
        }
        return view($this->loadCommonDataToView('website.auth.login'));
    }

    public function loginWithPassword(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string'],
            'password'   => ['required', 'string'],
        ]);

        $identifier = trim($request->identifier);
        $password = $request->password;

        // Try to match user by email or phone
        $user = User::where('email', $identifier)
            ->orWhere('phone', $identifier)
            ->orWhere('phone', '+977' . preg_replace('/[^0-9]/', '', $identifier))
            ->first();

        if (!$user || !\Hash::check($password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Invalid mobile number/email or password.'], 422);
        }

        // Log user in
        Auth::login($user, true);

        // Prefill session details
        session([
            'booking_contact_phone' => $user->phone,
            'booking_contact_email' => $user->email,
        ]);

        $defaultRoute = (empty($user->address) || empty($user->phone) || empty($user->email)) ? route('user.profile') : route('user.dashboard');
        $redirectUrl = session()->pull('url.intended', $defaultRoute);
        if (str_contains($redirectUrl, '/admin')) {
            $redirectUrl = $defaultRoute;
        }

        return response()->json([
            'success'  => true,
            'message'  => 'Login successful! Redirecting...',
            'redirect' => $redirectUrl,
        ]);
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string'],
        ]);

        $identifier = trim($request->identifier);
        $type = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if ($type === 'phone') {
            // Clean phone: ensure it is 10 digits
            $cleanPhone = preg_replace('/[^0-9]/', '', $identifier);
            if (strlen($cleanPhone) > 10) {
                $cleanPhone = substr($cleanPhone, -10);
            }
            if (strlen($cleanPhone) !== 10) {
                return response()->json(['success' => false, 'message' => 'Please enter a valid 10-digit mobile number.'], 422);
            }
            $identifier = '+977' . $cleanPhone;
        }

        // Enforce uniqueness across users only when adding to an existing logged-in profile
        if (auth()->check()) {
            $exists = User::where($type, $identifier)->where('id', '!=', auth()->id())->exists();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'This ' . $type . ' is already in use by another account.'
                ], 422);
            }
        }


        // Rate Limit Check
        $limitMinutes = $type === 'phone' ? 15 : 10;
        $limitCount = $type === 'phone' ? 5 : 3;

        $recentCount = OtpRequest::where('identifier', $identifier)
            ->where('created_at', '>', Carbon::now()->subMinutes($limitMinutes))
            ->count();
            
        if ($recentCount >= $limitCount) {
            return response()->json([
                'success' => false,
                'message' => "Too many verification attempts. Please wait {$limitMinutes} minutes before requesting a new code."
            ], 429);
        }

        // Generate 6 digit OTP
        $otp = sprintf('%06d', mt_rand(1, 999999));
        
        // Save to Database
        OtpRequest::create([
            'identifier' => $identifier,
            'otp_code'   => $otp,
            'type'       => $type,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Send OTP
        if ($type === 'phone') {
            $message = "Your verification code for Bagmati Proscun is: {$otp}. Valid for 10 mins.";
            try {
                $this->sendAakashSms($identifier, $message);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'SMS Error: ' . $e->getMessage()], 422);
            }
        } else {
            try {
                Mail::to($identifier)->send(new \App\Mail\UserOtpMail($otp));
            } catch (\Exception $e) {
                Log::error("Email OTP Send Error: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Failed to send email. Please try again.'], 500);
            }
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Verification code sent successfully.',
            'type'       => $type,
            'identifier' => $identifier,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string'],
            'otp_code'   => ['required', 'string', 'size:6'],
        ]);

        $identifier = trim($request->identifier);
        $otpCode = trim($request->otp_code);

        // Fetch latest active OTP request
        $otpRequest = OtpRequest::where('identifier', $identifier)
            ->where('otp_code', $otpCode)
            ->where('is_verified', false)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$otpRequest) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired verification code.'], 422);
        }

        // Mark OTP as verified
        $otpRequest->update(['is_verified' => true]);

        // Find or create User
        $type = $otpRequest->type;
        $user = null;

        if ($type === 'phone') {
            $user = User::where('phone', $identifier)->first();
            if (!$user) {
                $user = User::create([
                    'name'              => $request->get('name') ?: ('User_' . substr($identifier, -4)),
                    'phone'             => $identifier,
                    'password'          => bcrypt(Str::random(16)),
                    'must_set_password' => true,  // Force password setup after OTP registration
                ]);
            }
        } else {
            $user = User::where('email', $identifier)->first();
            if (!$user) {
                $user = User::create([
                    'name'              => $request->get('name') ?: ('User_' . substr(explode('@', $identifier)[0], 0, 8)),
                    'email'             => $identifier,
                    'password'          => bcrypt(Str::random(16)),
                    'must_set_password' => true,  // Force password setup after OTP registration
                ]);
            }
        }

        // Log the user in
        Auth::login($user, true);

        // Save phone/email verified state in session as fallback
        session([
            'booking_contact_phone' => $user->phone,
            'booking_contact_email' => $user->email,
        ]);

        // New OTP users must set a password before accessing anything else
        if ($user->must_set_password) {
            return response()->json([
                'success'  => true,
                'message'  => 'OTP verified! Please set your password to continue.',
                'redirect' => route('user.set-password'),
            ]);
        }

        $defaultRoute = (empty($user->address) || empty($user->phone) || empty($user->email)) ? route('user.profile') : route('user.dashboard');
        $redirectUrl = session()->pull('url.intended', $defaultRoute);
        if (str_contains($redirectUrl, '/admin')) {
            $redirectUrl = $defaultRoute;
        }

        return response()->json([
            'success'  => true,
            'message'  => 'Verification successful! Logging in...',
            'redirect' => $redirectUrl,
        ]);
    }

    /* ──────────────── Forced password setup (OTP registered users) ─── */

    public function showSetPassword()
    {
        if (!auth()->check() || !auth()->user()->must_set_password) {
            return redirect()->route('user.dashboard');
        }
        return view($this->loadCommonDataToView('website.auth.set_password'));
    }

    public function storeSetPassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'phone'                 => ['required', 'string', 'max:20', 'unique:users,phone,' . $user->id],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
        ]);

        if (!$user->must_set_password) {
            return redirect()->route('user.dashboard');
        }

        // If user already had a verified phone or email, do not allow changing them directly
        if ($user->phone && trim($request->phone) !== trim($user->phone)) {
            return redirect()->back()->withErrors(['phone' => 'You cannot change your verified phone number without OTP verification.'])->withInput();
        }
        if ($user->email && trim($request->email) !== trim($user->email)) {
            return redirect()->back()->withErrors(['email' => 'You cannot change your verified email address without OTP verification.'])->withInput();
        }

        $user->update([
            'name'              => $request->name,
            'phone'             => $user->phone ?: $request->phone,
            'email'             => $user->email ?: $request->email,
            'password'          => bcrypt($request->password),
            'must_set_password' => false,
        ]);

        // Sync with verified session states
        session([
            'booking_contact_phone' => $user->phone,
            'booking_contact_email' => $user->email,
        ]);

        $defaultRoute = (empty($user->address) || empty($user->phone) || empty($user->email)) ? route('user.profile') : route('user.dashboard');
        $redirectUrl = session()->pull('url.intended', $defaultRoute);
        if (str_contains($redirectUrl, '/admin')) {
            $redirectUrl = $defaultRoute;
        }

        return redirect()->to($redirectUrl)
            ->with('success_message', '🎉 Profile updated and password set successfully! Welcome.');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('events.index')->with('success_message', 'Logged out successfully.');
    }

    private function sendAakashSms(string $to, string $message): bool
    {
        $authToken = env('AAKASH_SMS_AUTH_TOKEN') ?: config('services.aakash_sms.auth_token');
        


        $cleanPhone = preg_replace('/[^0-9]/', '', $to);
        if (strlen($cleanPhone) > 10) {
            $cleanPhone = substr($cleanPhone, -10);
        }

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://sms.aakashsms.com/sms/v3/send');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'auth_token' => $authToken,
                'to'         => $cleanPhone,
                'text'       => $message,
            ]));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error    = curl_error($ch);
            curl_close($ch);
            
            if ($response === false) {
                throw new \Exception('CURL Error: ' . $error);
            }

            $result = json_decode($response, true);
            if ($httpcode != 200 || (isset($result['error']) && $result['error'] == true)) {
                $errorMsg = isset($result['message']) ? $result['message'] : 'API returned status code ' . $httpcode;
                throw new \Exception($errorMsg);
            }

            return true;
        } catch (\Exception $e) {
            Log::error("AAKASH SMS Send Exception: " . $e->getMessage());
            throw $e;
        }
    }
}
