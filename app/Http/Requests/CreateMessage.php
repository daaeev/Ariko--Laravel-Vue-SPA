<?php

namespace App\Http\Requests;

class CreateMessage extends ValidationWithFailedValidationMethod
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'email' => 'required|email:filter',
            'message' => 'required|string',
        ];
    }
}
