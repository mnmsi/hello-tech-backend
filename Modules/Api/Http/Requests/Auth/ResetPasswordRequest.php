<?php

namespace Modules\Api\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Modules\Api\Http\Traits\OTP\OtpTrait;
use Modules\Api\Http\Traits\Response\ApiResponseHelper;

class ResetPasswordRequest extends FormRequest
{
    use OtpTrait, ApiResponseHelper;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function prepareForValidation()
    {
        //check phone or email
//        if (filter_var($this->user, FILTER_VALIDATE_EMAIL)) {
//            $this->merge([
//                'type' => 'email', // 'email' or 'phone
//            ]);
//        } else {
//            $this->merge([
//                'type' => 'phone', // 'email' or 'phone
//            ]);
//        }
    }
    public function rules()
    {
        return [
            'otp' => 'required|numeric|digits:6|exists:App\Models\User\PhoneVerification,otp',
            'phone' => 'required|string|exists:App\Models\User\PhoneVerification,phone',
            'password' => 'required|string|min:6',
        ];
    }

    protected function passedValidation()
    {
        // Verify OTP if it is not valid throw response exception
        if (!$this->verifyOtp($this->phone, $this->otp)) {
            throw new HttpResponseException(
                $this->respondFailedValidation('Invalid OTP')
            );
        }

        // Hash password and merge it with request
        $this->merge([
            'password' => Hash::make($this->password),
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw response exception with failed validation message
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            $this->respondFailedValidation($validator->errors()->first())
        );
    }
}
