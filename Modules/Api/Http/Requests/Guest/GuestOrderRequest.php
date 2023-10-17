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
            'cart_id' => 'nullable|array|exists:carts,id',
            'product_id' => 'nullable|exists:products,id',
            'quantity' => 'nullable|numeric|min:1|max:5',
            'voucher_id' => 'nullable|exists:vouchers,id',
            'color_id' => 'nullable|exists:product_colors,id',
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'city_id' => 'required|exists:cities,id',
            'division_id' => 'required|exists:divisions,id',
            'area_id' => 'required|exists:areas,id',
            'address_line' => 'required|string',
            'delivery_option_id' => 'required|exists:delivery_options,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'order_note' => 'nullable|string',
            'voucher_code' => 'nullable|string',
            'discount_rate' => 'nullable',
            'shipping_amount' => 'required',
            'subtotal_price' => 'required',
            'total_price' => 'required',

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
