<?php

namespace Modules\Api\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Modules\Api\Http\Traits\Response\ApiResponseHelper;

class UserAddressRequest extends FormRequest
{
    use ApiResponseHelper;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // Set default value for is_default
        $this->merge([
            'is_default' => $this->is_default ?? 0,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'type' => 'required|string',
            'phone' => 'required|string',
            'address_line' => 'required|string',
            'division_id' => 'required|integer|exists:App\Models\System\Division,id',
            'city_id' => 'required|integer|exists:App\Models\System\City,id',
            'area_id' => 'required|integer|exists:App\Models\System\Area,id',
            'is_default' => 'nullable|in:0,1',
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
