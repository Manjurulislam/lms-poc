<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use App\Transformers\UserTransformer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login(Request $request): JsonResponse
    {
        $validator = $this->authService->validateLogin($request);

        if ($validator->fails()) {
            return ApiResponse::validation($validator->errors()->toArray());
        }

        if (!$this->authService->matchCredentials($request)) {
            return ApiResponse::validation([
                'email' => [
                    'The provided credentials are incorrect.'
                ],
            ]);
        }

        try {
            $user = User::where('email', $request->email)->first();
            $data = app(UserTransformer::class)->transformLoginUser($user);
            return ApiResponse::success($data, 'Successfully login');
        } catch (\Exception $e) {
            Log::error(__CLASS__, [$e]);
            return ApiResponse::error('Something went wrong, Please try again !!');
        }
    }

    public function register(Request $request): JsonResponse
    {
        $validator = $this->authService->validateRegister($request);

        if ($validator->fails()) {
            return ApiResponse::validation($validator->errors()->toArray());
        }

        try {
            $create = $this->authService->createUser($request);
            return ApiResponse::success('', 'Successfully register');
        } catch (\Exception $e) {
            Log::error('Exception', [$e->getMessage()]);
            return ApiResponse::error('Something went wrong, Please try again !!');
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $delete = $request->user()->currentAccessToken()->delete();
            return ApiResponse::success($delete, 'Successfully logout');
        } catch (\Exception $e) {
            Log::error('Exception', [$e->getMessage()]);
            return ApiResponse::error('Something went wrong, Please try again !!');
        }
    }
}
