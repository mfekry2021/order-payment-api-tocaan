<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\Api\User\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends BaseApiController
{
    /**
     * @param RegisterRequest $request
     * @param UserService $userService
     * @return JsonResponse
     */
    public function register(RegisterRequest $request, UserService $userService): JsonResponse
    {
        $user = $userService->save($request->validated());
        $user->jwt_token = JWTAuth::fromUser($user);
        return $this->successResponse(new UserResource($user), "registered successfully");
    }
}
