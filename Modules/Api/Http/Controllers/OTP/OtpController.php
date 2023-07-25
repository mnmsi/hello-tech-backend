<?php

namespace Modules\Api\Http\Controllers\OTP;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\OtpValidateRequest;
use App\Http\Requests\Auth\SendOtpRequest;
use App\Mail\OtpMail;
use App\Models\User\PhoneVerification;
use App\Models\User\User;
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
        $request->validate([
            'user' => 'required',
        ]);
        $otp = $this->generateOtp();
        $message = "This is your IOTAIT otp: $otp"; // Message to send with OTP
        if ($request->user == "phone") {
            if ($isSendSms = $this->sendSms($request->phone, $message)) {
                PhoneVerification::updateOrCreate([
                    'phone' => $request->phone
                ], [
                    'phone' => $request->phone,
                    'otp' => $otp,
                    'expires_at' => now()->addMinutes(10),
                ]);
            }
        } else {
            try {
                PhoneVerification::updateOrCreate([
                    'email' => $request->user
                ], [
                    'email' => $request->user,
                    'otp' => $otp,
                    'expires_at' => now()->addMinutes(10),
                ]);
                Mail::to($request->user)->send(new OtpMail($otp));
                return $this->respondWithSuccess([
                    'message' => 'OTP sent successfully',
                    'expires_at' => now()->addMinutes(10)->format('i'),
                    'otp' => $otp,
                ]);
            } catch (\Exception $e) {
                return $this->respondError($e->getMessage());
            }
        }
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
            return $this->respondWithSuccessStatus();
        }

        // If phone verification record not found then return response with error status
        return $this->respondError('OTP is invalid');
    }

}
