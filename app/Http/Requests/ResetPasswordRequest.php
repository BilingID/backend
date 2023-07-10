<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'password' => 'required|min:8|max:16',
            'password_confirmation' => 'required|same:password|min:8|max:16'
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Password couldn\'t be empty string',
            'password.min' => 'Password must be at least 8 characters',
            'password.max' => 'Password must be at most 16 characters',
            'password_confirmation.required' => 'Password confirmation couldn\'t be empty string',
            'password_confirmation.same' => 'Password confirmation must be same as password'
        ];
    }
}
