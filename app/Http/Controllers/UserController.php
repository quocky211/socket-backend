<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ShowRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /** @var userService */
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * function get list users
     * @return array
     */
    public function index()
    {
        return $this->userService->getList();
    }

    /**
     * function show users with params
     * @param ShowRequest $request
     * @param int  $id
     * @return array
     */
    public function show(ShowRequest $request, $id)
    {
        return $this->userService->show($id);
    }

    /**
     * function store user
     * @param StoreRequest $request
     * @return 
     */
    public function store(StoreRequest $request)
    {
        return $this->userService->store($request->all());
    }

    /**
     * function update user
     * @param UpdateRequest $request
     * @param int $id
     * @return 
     */
    public function update(UpdateRequest $request, $id)
    {
        return $this->userService->update($request->all(), $id);
    }

    /**
     * fucntion delete user
     */
    public function destroy($id)
    {
        return $this->userService->destroy($id);
    }
}
