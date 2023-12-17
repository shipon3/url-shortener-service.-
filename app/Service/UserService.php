<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * user registration 
     */
    public function registration(array $data): mixed
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * user login
     */
    public function login(array $request)
    {
        $user = User::where('email', $request['email'])->firstOrFail();
        $data['token'] = $user->createToken('auth_token')->plainTextToken;
        return $data;
    }

    /**
     * user logout
     */
    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response()->json(['status' => 'success', 'massage' => 'User Logout Successfully'], 200);
    }

    /**
     * user profile
     */
    public function profile(int $id): object
    {
        return User::findOrFail($id);
    }
}
