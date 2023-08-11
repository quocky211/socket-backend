<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chat\StoreRequest;
use App\Services\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    //
    /** @var ChatService */

    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * fucntion show message with a user
     * @param int $id
     * @return mixed
     */
    public function show($id)
    {
        return $this->chatService->getMessages($id);
    }

    /**
     * function get conversation id
     * @param int $id // to_user_id
     * @return number
     */
    public function getConversation($id)
    {
        return $this->chatService->getConversationId($id);
    }

    /**
     * function store message
     * @param StoreRequest $request
     * @return 
     */
    public function store(StoreRequest $request)
    {
        // event(new Message($request->input('username'), $request->input('message')));
        return $this->chatService->store($request->all());
    }

    /**
     * function destroy message
     * @param int $id
     */
    public function destroy($id)
    {
        $this->chatService->destroy($id);
    }

    /**
     * function delete conversation 
     * @param int $userId
     */
    public function destroyConversation($userId)
    {   
        $this->chatService->destroyConversation($userId);
    }
}
