<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'fullname' => 'string',
            'phone' => 'numeric|digits_between:11,14',
            'date_of_birth' => 'date',
            'gender' => 'string|in:male,female',
        ];
    }

    public function messages(): array
    {
        return [
            'fullname.string' => 'Fullname field must be string',
            'phone.numeric' => 'Phone field must be numeric',
            'phone.digits_between' => 'Phone length must be >= 11 and <= 14',
            'date_of_birth.date' => 'Date of birth must be a date',
            'gender.string' => 'Gender must be string',
            'gender.in' => 'Gender must be male or female'
        ];
    }
}
