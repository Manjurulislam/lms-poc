<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthService
{
    public function validateLogin(Request $request)
    {
        return Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);
    }

    public function matchCredentials(Request $request): bool
    {
        return auth()->attempt($request->only(['email', 'password']));
    }

    public function validateRegister(Request $request)
    {
        return Validator::make($request->all(), [
            'name'                  => 'required|string|max:250',
            'email'                 => 'required|email|max:250|unique:users',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);
    }

    public function createUser(Request $request)
    {
        $formData             = $request->only(['email', 'name']);
        $formData['password'] = Hash::make($request->get('password'));
        return User::create($formData);
    }


}
