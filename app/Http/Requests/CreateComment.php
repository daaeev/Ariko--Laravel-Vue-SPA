<?php

namespace App\Http\Requests;

class CreateComment extends ValidationWithFailedValidationMethod
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
            'email' => 'required|string|max:254|email:filter',
            'comment' => 'required|string',
            'post_id' => 'bail|required|exists:\App\Models\Post,id'
        ];
    }
}
