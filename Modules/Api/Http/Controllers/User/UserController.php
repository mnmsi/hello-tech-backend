<?php

namespace Modules\Api\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Modules\Api\Http\Resources\User\UserResource;
use Modules\Api\Http\Services\FileService;

class UserController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function user(): JsonResponse
    {
        // Return response with user data
        return $this->respondWithSuccessWithData(
            new UserResource(Auth::user())
        );
    }

    /**
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $user    = Auth::user();                                                                           // Get current user
        $reqData = $request->only('name', 'email', 'phone',); // Get request data

        // Check if request has file
//        if ($request->hasFile('avatar')) {
//            $reqData['avatar'] = FileService::storeOrUpdateFile($request->avatar, 'avatar', $user->avatar);
//        }

        // Update user data
        $user->update($reqData);

        // Return response with user data
        return $this->respondWithSuccessWithData(
            new UserResource(Auth::user())
        );
    }

    public function changePassword(Request $request){
        $request->validate([
           'current_password' => 'required',
            'new_password' => 'required|string|min:6'
        ]);
        $user = Auth::user();
        if(!Hash::check($request->current_password,$user->password)){
            return $this->respondFailedValidation(
                'Password does not match'
            );
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        return $this->respondWithSuccess([
           'message'=>'Password updated successfully'
        ]);
    }
}
