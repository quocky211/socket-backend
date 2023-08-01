<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserRepository
{
    /**
     * function get list users
     * @return array
     */
    public function getList()
    {
        return User::query()
        ->select('id','name')
        ->users()
        ->where('id','<>',Auth::user()->id)
        ->get();
    }

    /**
     * function show user by Id
     * @param int $id
     * @return 
     */
    public function show(int $id)
    {   
        return User::query()->select('id','name','email')->find($id);
    }

    /**
     * function store user
     * @param array $data
     * 
     */
    public function store(array $data)
    {   
        return User::query()->create($data);
    }

    /**
     * function update user
     * @param array $data
     * @param int $id
     */
    public function update(array $data, int $id)
    {
        User::query()->where('id', '=', $id)->update($data);
    }

    /**
     * function delete post
     *  @param int $id
     */
    public function destroy(int $id)
    {
        User::destroy($id);
        return response()->json(['message' => 'Đã xóa user: ' . $id]);
    }
}
