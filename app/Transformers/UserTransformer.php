<?php

namespace App\Transformers;

use App\Models\User;
use Carbon\Carbon;

class UserTransformer
{
    public function transformLoginUser(User $user): array
    {
        $token     = $user->createToken('lms-poc-coupon-management')->plainTextToken;
        $expire_at = now()->addMinutes(config('sanctum.expiration'))->toDateTimeString();
        return [
            'user'       => [
                'name'  => data_get($user, 'name'),
                'email' => data_get($user, 'email'),
            ],
            'isAdmin'    => (bool)data_get($user, 'admin'),
            'token'      => $token,
            'expires_in' => now()->diffInSeconds(Carbon::parse($expire_at)),
        ];
    }
}
