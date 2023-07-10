<?php

namespace Modules\Api\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Api\Http\Traits\Response\ApiResponseHelper;

class SocialSignInRequest extends FormRequest
{
    use ApiResponseHelper;

    public function rules()
    {
        return [
            'name' => 'required|string',
            'uid' => 'required|string',
            'token' => 'required|string',
            'email' => 'required|email',
            'avatar' => 'string',
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->respondFailedValidation($validator->errors()->first())
        );
    }
}
