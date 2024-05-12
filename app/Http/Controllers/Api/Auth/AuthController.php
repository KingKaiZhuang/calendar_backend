<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;

use App\Http\Controllers\Api\Auth\GoogleAuthController;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['status' => 'failed', 'message' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['status' => 'failed', 'message' => 'Could not create token'], 500);
        }

        // 登入成功，使用 auth()->user() 獲取當前登入用戶
        return $this->responseWithToken($token, auth()->user());
    }


    // 註冊方法
    public function register(RegistrationRequest $request)
    {
        $userData = $request->only('email', 'name', 'password');
        $userData['password'] = Hash::make($userData['password']);

        $user = User::create($userData);

        if ($user) {
            $token = JWTAuth::fromUser($user);
            return $this->responseWithToken($token, $user);
            return response()->json([
                'token' => $token,
                'user' => $user,
                'name' => $user,
            ]);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'An error occurred while trying to create a new user'], 500);
        }
    }

    // 生成並返回帶有 JWT access token 的響應
    public function responseWithToken($token, $user)
    {
        return response()->json([
            'status' => 'success', // 登入狀態
            'user' => $user, // 用戶資訊
            'access_token' => $token, // JWT token
            'type' => 'bearer' // Token 類型
        ]);
    }

    public function callbackFromGoogle(Request $request)
    {
        $googleAuth = new GoogleAuthController();
        return $googleAuth->callbackFromGoogle($request);
    }
}
