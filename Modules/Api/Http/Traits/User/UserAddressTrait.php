<?php

namespace Modules\Api\Http\Traits\User;

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
        if ($data['is_default'] == 1)
            Auth::user()->addresses()->update(['is_default' => 0]);

        return Auth::user()
            ->addresses()
            ->create($data);
    }

    /**
     * @return mixed
     */

    public function editAddress($id){
        return Auth::user()->addresses()->where('id', $id)->first();
    }

    /**
     * @return mixed
     */
    public function updateAddress($id, $data)
    {
//        if ($data['is_default'] == 1){
//            Auth::user()->addresses()->update(['is_default' => 0]);
//        }

        return Auth::user()
            ->addresses()
            ->where('id', $id)
            ->first();

    }

// selected address

    public function selectedAddress($id = null)
    {
        if ($id == null) {
            return Auth::user()->addresses()->where('is_default', 1)->first();
        } else {
            return Auth::user()->addresses()->where('id', $id)->first();
        }

    }
}
