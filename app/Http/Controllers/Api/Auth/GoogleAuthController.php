<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Google\Client as GoogleClient; // Import the Google\Client class

class GoogleAuthController extends Controller
{
    public function callbackFromGoogle(Request $request)
    {
        $token = $request->input('token'); // 從請求中獲取 token

        try {
            $client = new GoogleClient(['client_id' => env('GOOGLE_CLIENT_ID')]); // 從 .env 檔案中獲取 Google 客戶端 ID
            $payload = $client->verifyIdToken($token);

            if ($payload) {
                $googleUserId = $payload['sub']; // Google 用戶唯一識別碼
                $email = $payload['email']; // 用戶的郵箱地址
                $name = $payload['name']; // 用戶名稱

                // 檢查是否已存在相同電子郵件的用戶，如果不存在則創建一個新的
                // 使用 email 查找或創建用戶
                $user = User::firstOrCreate([
                    'email' => $email,
                ]);

                // 檢查用戶是不是新創建的，或者是否需要更新名字
                if (!$user->wasRecentlyCreated && is_null($user->name)) {
                    $user->name = $name;
                    $user->password = Hash::make(Str::random(8)); // 為新用戶創建隨機密碼
                    $user->save();
                }

                $googleAccount = Google::firstOrCreate([
                    'email' => $email,
                ], [
                    'account' => $googleUserId,
                ]);

                UserGoogle::updateOrCreate([
                    'user_id' => $user->id,
                ], [
                    'google_id' => $googleAccount->id, // 使用從 ID token 中獲取的 googleUserId
                ]);

                // 為用戶生成 JWT
                $token = JWTAuth::fromUser($user);

                // 返回用戶信息和 JWT
                return response()->json([
                    'token' => $token,
                    'user' => $user,
                    'payload' => $payload,
                    'name' => $name,
                ]);
            } else {
                // ID token 驗證失敗
                return response()->json(['error' => 'Invalid Google token.'], 401);
            }
        } catch (\Exception $e) {
            // 記錄錯誤並返回 500 錯誤響應
            Log::error("Google login error: " . $e->getMessage(), [
                'exception' => $e,
            ]);
            return response()->json(['error' => 'An error occurred while processing your request.', 'details' => $e->getMessage()], 500);
        }
    }
}
