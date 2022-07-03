<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validation($validator->errors()->toArray());
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return ApiResponse::validation([
                'email' => [
                    'The provided credentials are incorrect.'
                ],
            ]);
        }

        try {

            $token     = $user->createToken('lms-poc-coupon-management')->plainTextToken;
            $expire_at = now()->addMinutes(config('sanctum.expiration'))->toDateTimeString();

            $data = [
                'user'       => [
                    'name'  => data_get($user, 'name'),
                    'email' => data_get($user, 'email'),
                ],
                'isAdmin'    => (bool)data_get($user, 'admin'),
                'token'      => $token,
                'expires_in' => now()->diffInSeconds(Carbon::parse($expire_at)),
            ];
            return ApiResponse::success($data, 'Successfully login');
        } catch (\Exception $e) {
            Log::error('Exception', [$e->getMessage()]);
            return ApiResponse::error('Something went wrong, Please try again !!');
        }
    }


    public function register(Request $request) : JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string|max:250',
            'email'                 => 'required|email|max:250|unique:users',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validation($validator->errors()->toArray());
        }

        try {
            $data             = $request->only(['email', 'name']);
            $data['password'] = Hash::make($request->get('password'));
            User::create($data);
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
