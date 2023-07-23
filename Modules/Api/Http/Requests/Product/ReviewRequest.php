<?php

    namespace Modules\Api\Http\Requests\Product;

    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Http\Exceptions\HttpResponseException;
    use Modules\Api\Http\Traits\Response\ApiResponseHelper;

    class ReviewRequest extends FormRequest
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
                'product_id' => 'required|numeric|exists:products,id|unique:product_reviews,product_id,NULL,id,user_id,' . auth()->id(),
                'title' => 'required|string',
                'review' => 'required|string',
                'rate' => 'required|numeric|min:1|max:5',
            ];
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
            throw new HttpResponseException(
                $this->respondFailedValidation($validator->errors()->first())
            );
        }
    }
