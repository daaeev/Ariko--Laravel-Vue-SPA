<?php

namespace App\Http\Requests;

class UserLogin extends ValidationWithFailedValidationMethod
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required|email:filter',
            'password' => 'required|string',
        ];
    }
}
