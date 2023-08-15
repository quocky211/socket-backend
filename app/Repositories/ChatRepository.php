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
        $chats = Chat::withTrashed()
            ->where(function ($query) use ($userId1, $userId2) {
                $query->where('from_user_id', $userId1)->where('to_user_id', $userId2);
            })
            ->orWhere(function ($query) use ($userId1, $userId2) {
                $query->where('from_user_id', $userId2)->where('to_user_id', $userId1);
            })
            ->orderBy('created_at', 'asc')
            ->get();
        
        foreach ($chats as $chat) {
            if ($chat['message']) {
                $chat['image'] = null;
            } else {
                $chat['image'] = $chat->getFirstMediaUrl('images');
            }

            if ($chat['deleted_by_user_id'] === $userId1) {
                $chatKey = $chats->search(function ($item) use ($chat) {
                    return $item->id === $chat['id'];
                });
                if ($chatKey !== false) {
                    $chats->forget($chatKey);
                }
            }
        }
        return $chats;
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

        // store chat 
        $chat = Chat::query()->create($param);
        // store image
        if (isset($data['image'])) {
            $chat->addMediaFromRequest('image')
                ->toMediaCollection('images');
        }
        $media = $chat->getFirstMediaUrl('images');
        // output of message
        $message = [
            'id' => $chat['id'],
            'to_user_id' => $data['to_user_id'],
            'message' => $data['message'],
            'image' =>  $media,
            'created_at' => now(),
        ];

        // create chat channel 
        $pusher->trigger('Chat-Conversation-' . $conversationId, 'message', $message);

        // channel notification
        $user = User::findOrFail($data['from_user_id']);
        $notification = [
            'from_user_id' => $data['from_user_id'],
            'body' => "{$user->name} vừa nhắn tin cho bạn",
        ];
        $pusher->trigger('Notification-User-' . $data['to_user_id'], 'notification', $notification);


        return $chat;
    }

    /**
     * function delete a message
     * @param int $id
     */
    public function destroy(int $id)
    {
        $chat = Chat::withTrashed()
        ->where('id', '=', $id)
        ->get()
        ->first();

        if(!is_null($chat['message']))
        {
            $chat->forceDelete();
        }else{
            $chat->clearMediaCollection('images');
            $chat->forceDelete();
        }

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
        // conversationId
        $userId1 = $chat['from_user_id'];
        $userId2 = $chat['to_user_id'];
        $conversation = Conversation::query()
            ->select(['id'])
            ->where(function ($query) use ($userId1, $userId2) {
                $query->where('user1', $userId1)->where('user2', $userId2);
            })
            ->orWhere(function ($query) use ($userId1, $userId2) {
                $query->where('user1', $userId2)->where('user2', $userId1);
            })
            ->get()
            ->first();
        $deleteMessage = [
            'body' => "Delete message in conversation-{$conversation->id}",
        ];
        $pusher->trigger('Delete-Message-' . $conversation->id, 'deleteMessage', $deleteMessage);

    }

    /**
     * function soft delete Chat
     *  @param int $userId
     */
    public function destroyConversation(int $userId)
    {
        $userId1 = Auth::user()->id;
        $userId2 =  $userId;
        $chats = Chat::withTrashed()
            ->where(function ($query) use ($userId1, $userId2) {
                $query->where('from_user_id', $userId1)->where('to_user_id', $userId2);
            })
            ->orWhere(function ($query) use ($userId1, $userId2) {
                $query->where('from_user_id', $userId2)->where('to_user_id', $userId1);
            })
            ->get();

        // soft delete
        foreach ($chats as $chat) {
            if (!is_null($chat['deleted_by_user_id'])) {
                $chat->forceDelete();
            } else {
                Log::info($chat);
                $chat['deleted_by_user_id'] = $userId1;
 
                $chat->save();
      
                $chat->delete();
            }
        }
    }  

    /**
     * function typing realime
     * @param array $data
     */
    public function typingStatus(array $data)
    {   

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
         $typing = [
            'isTyping' => $data['isTyping'],
            'from_user_id' => Auth::user()->id,
        ];

        // create chat channel 
        $pusher->trigger('Typing-Channel-' .$data['conversationId'], 'typing-event', $typing);
    }

}
