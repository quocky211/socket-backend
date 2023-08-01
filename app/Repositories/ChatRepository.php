<?php

namespace App\Repositories;

use App\Models\Chat;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class ChatRepository
{
    /**
     * function get messages with a user
     * @param int $id
     * @return 
     */
    public function getMessages(int $id)
    {
        $userId1 = Auth::user()->id;
        $userId2 = $id;
        return Chat::query()
            ->select(['from_user_id', 'to_user_id', 'message', 'message_status'])
            ->where(function ($query) use ($userId1, $userId2) {
                $query->where('from_user_id', $userId1)->where('to_user_id', $userId2);
            })
            ->orWhere(function ($query) use ($userId1, $userId2) {
                $query->where('from_user_id', $userId2)->where('to_user_id', $userId1);
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * function get conversation id
     * @param int $id // to_user_id
     * @return number
     */
    public function getConversationId(int $id)
    {
        $userId1 = Auth::user()->id;
        $userId2 =  $id;
        $conversation = Conversation::query()
            ->select(['id'])
            ->where(function ($query) use ($userId1, $userId2) {
                $query->where('user1', $userId1)->where('user2', $userId2);
            })
            ->orWhere(function ($query) use ($userId1, $userId2) {
                $query->where('user1', $userId2)->where('user2', $userId1);
            })
            ->get();
        return $conversation->first()->id;
    }

    /**
     * @param array $data
     * @return Chat
     */
    public function store(array $data)
    {
        // get param array with Chat field
        $data['from_user_id'] = Auth::user()->id;
        $param = Arr::only($data, ['from_user_id', 'to_user_id', 'message', 'message_status']);

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

        // output of message
        $message = [
            'to_user_id' => $data['to_user_id'],
            'message' => $data['message'],
        ];

        // conversationId
        $userId1 = Auth::user()->id;
        $userId2 =  $data['to_user_id'];
        $conversation = Conversation::query()
            ->select(['id'])
            ->where(function ($query) use ($userId1, $userId2) {
                $query->where('user1', $userId1)->where('user2', $userId2);
            })
            ->orWhere(function ($query) use ($userId1, $userId2) {
                $query->where('user1', $userId2)->where('user2', $userId1);
            })
            ->get();
        $conversationId = $conversation->first()->id;

        // create chat channel 
        $pusher->trigger('Chat-Conversation-' . $conversationId, 'message', $message);

        // channel notification
        $user = User::findOrFail($data['from_user_id']);
        $notification = [
            'from_user_id' => $data['from_user_id'],
            'body' => "{$user->name} vừa nhắn tin cho bạn",
        ];
        $pusher->trigger('Notification-User-' . $data['to_user_id'], 'notification', $notification);

        // store chat 
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
