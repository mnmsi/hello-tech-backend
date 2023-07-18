<?php

namespace Modules\Api\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Api\Http\Requests\User\UserAddressRequest;
use Modules\Api\Http\Resources\User\UserAddressResource;
use Modules\Api\Http\Resources\User\UserResource;
use Modules\Api\Http\Traits\User\UserAddressTrait;

class UserAddressController extends Controller
{
    use UserAddressTrait;

    /**
     * @return JsonResponse
     */
    public function addresses()
    {
        // Return response with user addresses
        return $this->respondWithSuccessWithData(
            UserAddressResource::collection($this->getAddresses())
        );
    }

    /**
     * @return JsonResponse
     */
    public function store(UserAddressRequest $request)
    {
        // Store new address
        $address = $this->storeAddress($request->validated());

        // Return response with user addresses
        return $this->respondWithSuccess(['message' => 'Address added successfully']);
    }

    public function edit($id)
    {
       $address = $this->editAddress($id);
         if (!$address) {
              return $this->respondNotFound('Address not found');
         }else{
            return $this->respondWithSuccessWithData(
                new UserAddressResource($address)
            );
         }
    }

    /**
     * @return JsonResponse
     */
    public function update(UserAddressRequest $request, $id)
    {
        return $this->respondWithSuccessWithData(
            $request->all()
        );

        // Update address
        $address = $this->updateAddress($id, $request->all());

        if ($address) {
            // Return response with user addresses
            return $this->respondWithSuccessWithData(
//                UserAddressResource::collection($this->getAddresses())
                $address->toArray()
            );
        }

        return $this->respondNotFound("Something went wrong!");
    }

    /**
     * @return JsonResponse
     */
    public function delete($id)
    {
        // Delete address
        $address = Auth::user()
            ->addresses()
            ->where('id', $id)
            ->delete();

        if (!$address) {
            return $this->respondNotFound('Address not found');
        }

        // Return response with user addresses
        return $this->respondWithSuccessWithData(
            UserAddressResource::collection($this->getAddresses())
        );
    }

    /**
     * @return JsonResponse
     */

    public function getSelectedAddress($id = null)
    {
        return $this->respondWithSuccessWithData(
            new UserAddressResource($this->selectedAddress($id))
        );
    }
}
