<?php

namespace App\Repositories;

use App\Events\UserLogged;
use App\Models\User;
use App\Models\UserIdLogged;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

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
            // options for pusher
            $options = [
                'cluster' => 'ap1',
                'encrypted' => true,
            ];

            // $pusher 
            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );

            $userLogged = [
                'id' => Auth::user()->id,
            ];

            $pusher->trigger('User-Logged', 'userlogged',  $userLogged);

            // store 
            $data = [
                'user_id_logged' => Auth::user()->id,
            ];
            UserIdLogged::query()->create($data);
            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $user->avatar,
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
        // delete userlogged in table
        UserIdLogged::query()
        ->where('user_id_logged','=',$user->id)
        ->delete();
        // logout realtime
        $options = [
            'cluster' => 'ap1',
            'encrypted' => true,
        ];

        // $pusher 
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $userLogout = [
            'id' => Auth::user()->id,
        ];

        $pusher->trigger('User-Logout', 'userlogout',  $userLogout);
        return response()->json(['message' => 'Đăng xuất thành công'], 200);

    }

    /**
     * function get all user id logged
     */
    public function getUserIdLogged()
    {
        return UserIdLogged::query()
        ->select('user_id_logged')
        ->get();
    }

}
