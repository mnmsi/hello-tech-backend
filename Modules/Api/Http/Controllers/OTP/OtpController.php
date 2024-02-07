<?php

namespace Modules\Api\Http\Controllers\OTP;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\OtpValidateRequest;
use App\Http\Requests\Auth\SendOtpRequest;
use App\Mail\OtpMail;
use App\Models\User\PhoneVerification;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Api\Http\Traits\OTP\OtpTrait;

class OtpController extends Controller
{
    // Use OtpTrait for generate and send otp
    use OtpTrait;

    public function sendOtp(Request $request)
    {
        $last_sent = PhoneVerification::where('phone', $request->phone)->orderBy('id', 'desc')->first();
        if ($last_sent && now() < $last_sent->expires_at) {
            return $this->respondWithSuccessWithData([
                'message' => 'OTP already sent! Please wait for 5 minutes.',
            ]);
        }
        $otp = $this->generateOtp();
        $message = "Your One-Time-Password for Hello Tech is: $otp" . "  It will expire after 5 minutes"; // Message to send with OTP
        if ($isSendSms = $this->sendSms($request->phone, $message)) {
            PhoneVerification::updateOrCreate([
                'phone' => $request->phone
            ], [
                'phone' => $request->phone,
                'otp' => $otp,
                'expires_at' => now()->addMinutes(5),
            ]);
        }

        // Return response with success status according to send sms
        return $this->respondWithSuccessWithData([
            'message' => "OTP sent successfully.",
            'is_send_sms' => $isSendSms,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(5)->format('Y-m-d H:i:s'),
        ]);
    }

    function verifyOtp(OtpValidateRequest $request)
    {
        // Find phone verification record by phone and otp
        $phoneVerification = PhoneVerification::where('otp', $request->otp)
            ->where(function ($query) use ($request) {
                $query->where('phone', $request->user)
                    ->orWhere('email', $request->user);
            })
            ->first();


        // If phone verification record found then check if it is expired or not
        if ($phoneVerification) {
            if (now() > $phoneVerification->expires_at) {
                // If expired then return response with error status
                return $this->respondError('OTP is expired');
            }

            // If not expired then return response with success status
//            $phoneVerification->delete();
            return $this->respondWithSuccessWithData([
                'message' => 'OTP verified successfully',
            ]);

        }

        // If phone verification record not found then return response with error status
        return $this->respondError('OTP is invalid');
    }

}
