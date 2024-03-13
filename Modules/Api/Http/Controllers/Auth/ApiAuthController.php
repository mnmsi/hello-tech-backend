<?php

namespace Modules\Api\Http\Controllers\Auth;

use App\Mail\OtpMail;
use App\Http\Controllers\Controller;
use App\Models\User\PhoneVerification;
use App\Models\User\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Modules\Api\Http\Requests\Auth\AuthenticateUserRequest;
use Modules\Api\Http\Requests\Auth\RegisterUserRequest;
use Modules\Api\Http\Requests\Auth\ResetPasswordRequest;
use Modules\Api\Http\Requests\Auth\SocialSignInRequest;
use Modules\Api\Http\Services\FileService;
use Modules\Api\Http\Traits\OTP\OtpTrait;

class ApiAuthController extends Controller
{
    use OtpTrait;

    public function login(AuthenticateUserRequest $request)
    {
        // Authentication for requested phone and password
        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password])) {


            // Get user for current request which is authenticated
            $user = Auth::user();

            // Create token for current requested user
            $token = $user->createToken('user-auth');

            // Return response token and user with success status
            return $this->respondWithSuccess([
                'token' => $token->plainTextToken,
                'user' => $user,
            ]);
        } // If authentication fails
        else {
            return $this->respondUnAuthenticated();
        }
    }

    public function register(RegisterUserRequest $request)
    {

        $reqData = $request->all(); // Get request data

        // Check if request has file
        if ($request->hasFile('avatar')) {
            // Merge avatar with request
            $reqData['avatar'] = FileService::storeOrUpdateFile($request->avatar, 'avatar'); // Store file in public disk
        }

        // Create user
        $user = User::create($reqData);

        // Create token for current requested user
        $token = $user->createToken('user-auth');

        // Return response token and user with success status
        return $this->respondWithSuccess([
            'token' => $token->plainTextToken,
            'user' => $user,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        // Session flush for current user
        Session::flush();

        // Delete current token for current user
        Auth::user()->currentAccessToken()->delete();

        // Return response with success status
        return $this->respondWithSuccessStatusWithMsg('Logged out successfully');
    }

    public function forgotPassword(Request $request)
    {
        //        $type = filter_var($request->user, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $request->validate([
            'phone'    => 'required|string|exists:App\Models\User\User,phone',
        ]);
        $user = User::where('phone', $request->phone)->first();
        $otp = $this->generateOtp();
        if ($user) {
            $message = "Your One-Time-Password for Hello Tech is: $otp" . "  It will expire after 5 minutes";
            // Send otp to user phone if send sms is true then update or create phone verification record
            if ($isSendSms = $this->sendSms($request->phone, $message)) {
                PhoneVerification::updateOrCreate([
                    'phone' => $request->phone
                ], [
                    'phone' => $request->phone,
                    'otp' => $otp,
                    'expires_at' => now()->addMinutes(5),
                ]);
            }
            return $this->respondWithSuccessWithData([
                'message' => "OTP sent successfully.",
                'is_send_sms' => $isSendSms,
                'otp' => $otp,
                'expires_at' => now()->addMinutes(5)->format('Y-m-d H:i:s'),
            ]);
        }
        //            if ($type == 'phone') {

        //            } else {
        //                PhoneVerification::updateOrCreate([
        //                    'email' => $request->user
        //                ], [
        //                    'email' => $request->user,
        //                    'otp' => $otp,
        //                    'expires_at' => now()->addMinutes(30),
        //                ]);
        //                Mail::to($request->user)->send(new OtpMail($otp));
        //                return $this->respondWithSuccess([
        //                    'message' => 'OTP sent successfully',
        //                    'expires_at' => now()->addMinutes(10)->format('i'),
        //                    'otp' => $otp,
        //                ]);
        //            }
        //        }
        else {
            return $this->respondNotFound('User not found');
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        //        $type = filter_var($request->user, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $user = User::where('phone', $request->phone)->first();
        if ($user) {
            $user->update([
                'password' => $request->password,
            ]);
            return $this->respondWithSuccessStatusWithMsg(['message' => 'Password updated successfully']);
        } else {
            return $this->respondNotFound('User not found');
        }
    }

    public function googleLogin(SocialSignInRequest $request)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://identitytoolkit.googleapis.com/v1/accounts:lookup?key=AIzaSyDfyi8BTe1j4psDU8JN9IPDRYZaKPz3I_4', [
                'idToken' => $request->token,
            ]);
            $response = json_decode($response->body());
            if (isset($response->users[0]->localId)) {
                $user = User::where('uid', $request->uid)->first();
                if ($user) {
                    $token = $user->createToken('user-auth');
                    return $this->respondWithSuccess([
                        'token' => $token->plainTextToken,
                        'user' => $user,
                    ]);
                } else {
                    $user = User::create([
                        'name' => $request->name,
                        'uid' => $request->uid,
                        'email' => $request->email,
                        'password' => Hash::make(time()),
                        'avatar' => $request->avatar,
                    ]);
                    $token = $user->createToken('user-auth');
                    return $this->respondWithSuccess([
                        'token' => $token->plainTextToken,
                        'user' => $user,
                    ]);
                }
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
}
