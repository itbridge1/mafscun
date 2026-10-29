<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtpRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OtpRequestController extends BaseController
{
    protected $panel = 'OTP Requests';
    protected $base_route = 'admin.otp-requests';

    public function index(Request $request)
    {
        $type = $request->get('type');
        $identifier = $request->get('identifier');

        $query = OtpRequest::query();
        $topQuery = OtpRequest::select('identifier', 'type', DB::raw('count(*) as request_count'))
            ->groupBy('identifier', 'type');

        if ($type) {
            $query->where('type', $type);
            $topQuery->where('type', $type);
        }

        if ($identifier) {
            $query->where('identifier', 'like', '%' . $identifier . '%');
            $topQuery->where('identifier', 'like', '%' . $identifier . '%');
        }

        // Paginate all OTP requests
        $otpRequests = $query->orderBy('created_at', 'desc')->paginate(20)->appends($request->all());

        // Fetch top requesters
        $topRequesters = $topQuery->orderByDesc('request_count')->limit(10)->get();

        $stats = [
            'total_requests' => OtpRequest::count(),
            'phone_requests' => OtpRequest::where('type', 'phone')->count(),
            'email_requests' => OtpRequest::where('type', 'email')->count(),
            'verified_requests' => OtpRequest::where('is_verified', true)->count(),
        ];

        return view($this->loadCommonDataToView('admin.otpRequest.index'), compact('otpRequests', 'topRequesters', 'stats'));
    }

    public function adminSendOtp(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string'],
        ]);

        $identifier = trim($request->identifier);
        $type = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if ($type === 'phone') {
            $cleanPhone = preg_replace('/[^0-9]/', '', $identifier);
            if (strlen($cleanPhone) > 10) {
                $cleanPhone = substr($cleanPhone, -10);
            }
            if (strlen($cleanPhone) !== 10) {
                return response()->json(['success' => false, 'message' => 'Invalid phone number format. Must be 10 digits.'], 422);
            }
            $identifier = '+977' . $cleanPhone;
        }

        // Enforce uniqueness across users
        if (\App\Models\User::where($type, $identifier)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This ' . $type . ' is already in use by another account.'
            ], 422);
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

        // Generate 6-digit OTP code
        // Local bypass code is 123456 if APP_DEBUG=true
        $otp = (env('APP_DEBUG') === true) ? '123456' : sprintf("%06d", mt_rand(100000, 999999));

        // Save OTP request
        OtpRequest::create([
            'identifier' => $identifier,
            'otp_code'   => $otp,
            'type'       => $type,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Send OTP
        if ($type === 'phone') {
            $message = "Your  booking verification code is: {$otp}. Valid for 10 mins.";
            try {
                $this->sendAakashSms($identifier, $message);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'SMS Error: ' . $e->getMessage()], 422);
            }
        } else {
            try {
                Mail::to($identifier)->send(new \App\Mail\UserOtpMail($otp));
            } catch (\Exception $e) {
                Log::error("Admin Email OTP Send Error: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Failed to send email.'], 500);
            }
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Verification OTP sent successfully.',
            'type'       => $type,
            'identifier' => $identifier,
        ]);
    }

    public function adminVerifyAndRegister(Request $request)
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'identifier' => ['required', 'string'],
            'otp_code'   => ['required', 'string', 'size:6'],
        ]);

        $name = trim($request->name);
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
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP code.'], 422);
        }

        // Mark OTP as verified
        $otpRequest->update(['is_verified' => true]);

        // Check if user already exists, or create a new user
        $type = $otpRequest->type;
        $user = null;

        if ($type === 'phone') {
            $user = User::where('phone', $identifier)->first();
            if (!$user) {
                $user = User::create([
                    'name'     => $name,
                    'phone'    => $identifier,
                    'password' => bcrypt(Str::random(16)),
                ]);
            }
        } else {
            $user = User::where('email', $identifier)->first();
            if (!$user) {
                $user = User::create([
                    'name'     => $name,
                    'email'    => $identifier,
                    'password' => bcrypt(Str::random(16)),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully!',
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
            ],
        ]);
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
            Log::error("AAKASH SMS Send Exception (via Admin): " . $e->getMessage());
            throw $e;
        }
    }
}
