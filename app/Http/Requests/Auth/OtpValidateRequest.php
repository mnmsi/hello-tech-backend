<?php

    namespace App\Http\Requests\Auth;

    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Http\Exceptions\HttpResponseException;
    use Modules\Api\Http\Traits\Response\ApiResponseHelper;

    class OtpValidateRequest extends FormRequest
    {
        use ApiResponseHelper;
        public function prepareForValidation()
        {
            //check phone or email
            if (filter_var($this->user, FILTER_VALIDATE_EMAIL)) {
                $this->merge([
                    'type' => 'email', // 'email' or 'phone
                ]);
            } else {
                $this->merge([
                    'type' => 'phone', // 'email' or 'phone
                ]);
            }
        }

        /**
         * Determine if the user is authorized to make this request.
         */

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
         */
        public function rules(): array
        {
            return [
                'user' => 'required|string|exists:App\Models\User\PhoneVerification,' . $this->type,
                'otp' => 'required|numeric|digits:6|exists:App\Models\User\PhoneVerification,otp',
            ];
        }


        protected function failedValidation(Validator $validator)
        {
            throw new HttpResponseException(
                $this->respondFailedValidation($validator->errors()->first())
            );
        }



    }
