<?php

namespace App\Repositories;

use App\Models\Chat;
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
        $users = User::query()
            ->select('id', 'name', 'avatar')
            ->users()
            ->where('id', '<>', Auth::user()->id)
            ->get();

        foreach ($users as $user) {
            $userId1 = Auth::user()->id;
            $userId2 = $user->id;
            $messages = Chat::withTrashed()
                ->where(function ($query) use ($userId1, $userId2) {
                    $query->where('from_user_id', $userId1)->where('to_user_id', $userId2);
                })
                ->orWhere(function ($query) use ($userId1, $userId2) {
                    $query->where('from_user_id', $userId2)->where('to_user_id', $userId1);
                })
                ->orderBy('created_at', 'desc')
                ->get();
            if (count($messages) > 0 && $messages->first()->deleted_by_user_id !== $userId1) {
                if ($messages->first()->message) {
                    $user['lastMessage'] = $messages->first()->message;
                } else {
                    $user['lastMessage'] = 'File or Image';
                }
            } else {
                $user['lastMessage'] = 'Never contact';
            }
        }

        return $users;
    }

    /**
     * function show user by Id
     * @param int $id
     * @return 
     */
    public function show(int $id)
    {
        return User::query()->select('id', 'name', 'email')->find($id);
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

    /**
     * function update avatar
     * @param array $data
     * @param int $id
     */
    public function updateAvatar(array $data, int $id)
    {
        $user = User::findOrFail($id);
        if (isset($data['avatar'])) {
            $user->clearMediaCollection('avatars');
            $user->addMediaFromRequest("avatar")
                ->usingName($user->name)
                ->toMediaCollection('avatars');
        }

        $media = $user->getFirstMediaUrl('avatars');
        return User::query()->where('id', '=', $id)->update(['avatar' => strval($media)]);
    }
}
