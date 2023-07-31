<?php 

namespace App\Repositories;

use App\Models\Chat;
use Illuminate\Support\Arr;


class ChatRepository
{   
    /**
     * function get list Chats
     * @return 
     */
    public function getList()
    {
        return Chat::query()->get();
    }

    /**
     * @param array $data
     * @return Chat
     */
    public function store(array $data)
    {       
        // get param array with Chat field
        $param = Arr::only($data, ['username', 'message']);
        $chat = Chat::query()->create($param);
        return $chat;
    }

    /**
     * function delete Chat
     *  @param int $id
     */
    public function destroy(int $id)
    {
        Chat::destroy($id);
    }
}