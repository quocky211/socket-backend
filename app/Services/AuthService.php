<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class AuthService
{
    /** @var AuthRepository */
    protected $authrepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authrepository = $authRepository;
    }

    /**
     * function login
     * @param array $data
     */
    public function login(array $data)
    {
        $login = Arr::only($data, ['email', 'password']);

        return $this->authrepository->login($login);
    }

    /**
     * function logout
     */
    public function logout()
    {
        return $this->authrepository->logout();
    }
}
