<?php

namespace App\Services;

use App\Repositories\ChatRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class ChatService
{
    /** @var ChatRepository */
    protected $chatRepository;

    public function __construct(ChatRepository $chatRepository)
    {
        $this->chatRepository = $chatRepository;
    }

    /**
     * function get messages with a user
     * @param int $id
     * @return array
     */
    public function getMessages( int $id)
    {
        return $this->chatRepository->getMessages($id);
    }

    /**
     * fucntion get conversation id
     * @param  int $id // to_user_id
     * @return number
     */
    public function getConversationId(int $id)
    {
        return $this->chatRepository->getConversationId($id);
    }

    /**
     * function store message
     * @param array $data
     * @return 
     */
    public function store( array $data)
    {
        return $this->chatRepository->store($data);
    }
    /**
     * function soft delete Chat
     *  @param int $id
     */
    public function destroy(int $id)
    {
        $this->chatRepository->destroy($id);
    }

}
