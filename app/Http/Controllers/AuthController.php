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
        Log::info($request->all());

        return $this->authService->login($request->all());
    }

    /**
     * function logout
     */
    public function logout()
    {
        return $this->authService->logout();
    }
}
