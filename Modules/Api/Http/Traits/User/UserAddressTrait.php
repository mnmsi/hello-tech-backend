<?php

namespace Modules\Api\Http\Traits\User;

use App\Models\System\DeliveryOption;
use Illuminate\Support\Facades\Auth;

trait UserAddressTrait
{
    /**
     * @return mixed
     */
    public function getAddresses()
    {
        return Auth::user()->addresses;
    }


    /**
     * @return mixed
     */
    public function storeAddress($data)
    {
        $addressCount = Auth::user()->addresses()->count();
        if ($addressCount <= 1) {
            $data['is_default'] = 1;
        }

        return Auth::user()
            ->addresses()
            ->create($data);
    }

    /**
     * @return mixed
     */

    public function editAddress($id)
    {
        return Auth::user()->addresses()->where('id', $id)->first();
    }

    /**
     * @return mixed
     */
    public function updateAddress($id, $data)
    {

        $addressCount = Auth::user()->addresses()->count();
        if ($addressCount <= 1) {
            $data['is_default'] = 1;
        }

        return Auth::user()
            ->addresses()
            ->where('id', $id)
            ->update($data);


    }

// selected address

    public function selectedAddress($id = null)
    {
        if ($id == null) {
            $address = Auth::user()->addresses()->where('is_default', 1)->first();
        } else {
            $address = Auth::user()->addresses()->where('id', $id)->first();
        }
        $division_id = $address->division_id;
        if ($division_id === 3) {
            return [
                'address' => $address,
                'delivery_charge' => DeliveryOption::where('id', 1)->pluck('amount')->first()
            ];
        } else {
            return [
                'address' => $address,
                'delivery_charge' => DeliveryOption::where('id', 2)->pluck('amount')->first()
            ];
        }

    }
}
