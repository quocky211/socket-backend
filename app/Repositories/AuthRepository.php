<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthRepository
{

    /**
     * function login 
     * @param array $data
     * 
     */
    public function login(array $data)
    {
        // Auth::attempt to check user to user table
        if (Auth::attempt($data)) {
            /** @var \App\Models\User $user **/
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ], 200);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    /**
     * function logout
     */
    public function logout()
    {
        Log::info(Auth::user());
        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $user->tokens()->delete();
        return response()->json(['message' => 'Đăng xuất thành công'], 200);
    }
}
