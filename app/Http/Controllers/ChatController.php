<?php

namespace App\Http\Controllers;

use App\Events\Message;
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
    public function message(Request $request)
    {
        // event(new Message($request->input(key:'username'),$request->input(key:'message')));
        event(new Message($request->input('username'), $request->input('message')));
        return [$request->input('username'), $request->input('message')];
    }

    public function store(StoreRequest $request)
    {
        return $this->chatService->store($request->all());
    }
}
