<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        $user = $this->userService->save($request->all());  
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        
        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    public function login(Request $request)
    {

        if(!Auth::attempt($request->all())) {
            return response()->json([
                'message' => 'Unauthorized',                
            ], 401);
        }     

        $user = $request->user();
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;

        return response()->json([
            'access_token' => $token,    
            'token_type' => 'Bearer'            
        ], 200);
    }
}
