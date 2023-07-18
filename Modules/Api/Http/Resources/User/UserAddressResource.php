<?php

    namespace Modules\Api\Http\Resources\User;

    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\JsonResource;
    use Modules\Api\Http\Traits\Product\FeatureTrait;

    class UserAddressResource extends JsonResource
    {
        /**
         * Transform the resource into an array.
         *
         * @param Request $request
         * @return array
         */
        public function toArray($request)
        {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'type' => $this->type,
                'email' => $this->email ?? '',
                'zip_code' => $this->zip_code,
                'phone' => $this->phone,
                'address_line' => $this->address_line,
                'division' => $this->division->name,
                'division_id' => $this->division->id,
                'is_default' => $this->is_default,
            ];
        }
    }
