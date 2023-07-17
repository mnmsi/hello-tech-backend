<?php

namespace Modules\Api\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}
