<?php

namespace App\Http\Requests;

class CreateUser extends ValidationWithFailedValidationMethod
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'bail|required|string|max:255|email:filter|unique:\App\Models\User,email',
            'password' => 'bail|required|string',
        ];
    }
}
