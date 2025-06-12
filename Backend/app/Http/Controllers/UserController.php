<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        // 验证请求参数
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $credentials['username'])->first();
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['error' => '用户名或密码不正确！'], 401);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 200);

    }

    public function profile(Request $request)
    {
        // 返回当前登录用户信息
        return response()->json($request->user(), 200);
    }

    public function logout(Request $request)
    {
        // 删除用户当前登录的token
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => '成功登出'], 200);
    }
}
