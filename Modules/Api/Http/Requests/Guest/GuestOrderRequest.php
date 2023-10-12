<?php

namespace Modules\Api\Http\Requests\Guest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Api\Http\Traits\Response\ApiResponseHelper;

class GuestOrderRequest extends FormRequest
{
    use ApiResponseHelper;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric',
            'voucher_id' => 'nullable|exists:vouchers,id',
            'color_id' => 'nullable|exists:product_colors,id',
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'city' => 'required|string',
            'division' => 'required|string',
            'area' => 'required|string',
            'address_line' => 'required|string',
            'delivery_option' => 'required|string',
            'payment_method' => 'required|string',
            'order_note' => 'nullable|string',
            'voucher_code' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'order_key' => 'nullable|string',
            'discount_rate' => 'nullable|string',
            'shipping_amount' => 'required|string',
            'subtotal_price' => 'required|string',
            'total_price' => 'required|string',

        ];
    }

//    add charge based on shipping address name

    public function withValidator($validator)
    {

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

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->respondFailedValidation($validator->errors()->first())
        );
    }
}
