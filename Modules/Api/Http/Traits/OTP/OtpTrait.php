<?php

namespace Modules\Api\Http\Traits\OTP;

use App\Models\User\PhoneVerification;

trait OtpTrait
{
    public function generateOTP()
    {
        return rand(100000, 999999);
    }

    public function sendSms($phone, $message)
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
//
            CURLOPT_URL => 'https://api.smsq.global/api/v2/SendSMS',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "senderId":"'.env('SMS_SENDER_ID').'",
            "message":"'.$message.'",
            "mobileNumbers":"'.$phone.'",
            "apiKey":"' . env('SMS_API_KEY') . '",
            "clientId":"'.env('SMS_CLIENT_ID').'",
            }',
            CURLOPT_HTTPHEADER => array(
                'accept: application/json',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        if ($response) {
            return true;
        } else {
            return false;
        }
    }

    public function verifyOtp($phone, $otp)
    {
        // Check if the phone number and otp is valid / it exists in the database
        $phoneVerification = PhoneVerification::where('phone', $phone)
                                              ->where('otp', $otp)
                                              ->first();

        // If the phone number and otp is not valid / not exists in the database then return false
        if (!$phoneVerification) {
            return false;
        }

        // Check if the otp is expired or not and return false if it is expired
        if (now() > $phoneVerification->expires_at) {
            return false;
        }

        // Delete the otp from the database after verification
        $phoneVerification->delete();
        return true;
    }
}
