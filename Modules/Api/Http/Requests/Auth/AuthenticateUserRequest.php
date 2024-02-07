<?php

namespace Modules\Api\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Modules\Api\Http\Traits\Response\ApiResponseHelper;

class AuthenticateUserRequest extends FormRequest
{
    use ApiResponseHelper;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

//    public function prepareForValidation()
//    {
//        //check phone or email
//        if (filter_var($this->user, FILTER_VALIDATE_EMAIL)) {
//            $this->merge([
//                'type' => 'email', // 'email' or 'phone
//            ]);
//        } else {
//            $this->merge([
//                'type' => 'phone', // 'email' or 'phone
//            ]);
//        }
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone'    => 'required|string|exists:App\Models\User\User,phone',
            'password' => 'required|string|min:6',
        ];
    }

    public function messages()
    {
        return [
            'phone.exists' => 'User with this phone number does not exist',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Throw response exception with failed validation message
        throw new HttpResponseException(
            $this->respondFailedValidation($validator->errors()->first())
        );
    }
}
