<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserService
{
    /** @var userRepository*/
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * function get list users
     * @return array
     */
    public function getList()
    {
        return $this->userRepository->getList();
    }

    /**
     * function show user
     * @param int $id
     * @return 
     */
    public function show(int $id)
    {
        return $this->userRepository->show($id);
    }

    /**
     * function store user
     * @param array $params
     * @return array
     */
    public function store(array $params)
    {
        try {
            $data = Arr::only($params, ['name', 'email', 'password']);
            $result = $this->userRepository->store($data);
        } catch (\Exception $ex) {
            Log::error($ex);
            DB::rollBack();
            throw $ex;
        }
        return ['id' => $result['id']];
    }

    /**
     * function update user
     * @param array $data
     * @param int $id
     */
    public function update(array $params, int $id)
    {
        try {
            $data = Arr::only($params, ['name', 'email', 'password']);
            $this->userRepository->update($data, $id);
        } catch ( \Exception $ex ) {
            Log::error($ex);
            DB::rollBack();
            throw $ex;
        }
        return ['id'=> $id];
    }

    /**
     * function delete user
     * @param int $id
     */
    public function destroy(int $id)
    {
        return $this->userRepository->destroy($id);
    }

     /**
     * function update avatar
     * @param array $data
     * @param int $id
     */    
    public function updateAvatar(array $data, int $id)
    {
        return $this->userRepository->updateAvatar($data, $id);
    }
}
