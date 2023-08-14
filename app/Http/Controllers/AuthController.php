<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /** @var  AuthService*/
    protected $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * function login 
     * @param LoginRequest $request
     * 
     */
    public function login(LoginRequest $request)
    {
        return $this->authService->login($request->all());
    }

    /**
     * function logout
     */
    public function logout()
    {
        return $this->authService->logout();
    }

     /**
     * function get all user id logged
     */
    public function getUserIdLogged()
    {
        return $this->authService->getUserIdLogged();
    }
}
