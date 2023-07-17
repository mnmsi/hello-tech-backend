<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Modules\Api\Http\Traits\Response\ApiResponseHelper;

class SendOtpRequest extends FormRequest
{
    use ApiResponseHelper;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'user' => 'required|string|exists:App\Models\User\User,' . $this->type,
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->respondFailedValidation($validator->errors()->first())
        );
    }
}
