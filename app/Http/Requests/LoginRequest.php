<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            // 'email' 必須被提供，且格式需符合電子郵件地址（使用 filter_var 函數進行驗證）
            'email' => ['required', 'email:filter'],
            // 'password' 必須被提供，且類型為字串
            'password' => ['required', 'string']
        ];
    }
}