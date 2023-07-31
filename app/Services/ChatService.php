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

    public function getList()
    {
        return $this->chatRepository->getList();
    }

    public function store( array $data)
    {
        return $this->chatRepository->store($data);
    }

}
