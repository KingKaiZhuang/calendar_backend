<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'name' 字段必須被提供，且必須是字符串類型，最小長度為2
            'name' => ['required', 'string', 'min:2'],
            // 'email' 字段必須被提供，必須符合電子郵件格式，且在 users 表中唯一
            'email' => ['required', 'email:filter', 'unique:users'],
            // 'password' 字段必須被提供，必須是字符串類型，最小長度為6，且請求中必須包含一致的確認密碼字段
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }
}
