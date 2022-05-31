<?php

namespace App\Http\Requests;

class AddTagToPost extends ValidationWithFailedValidationMethod
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'tag' => 'required|string|max:255',
            'post_id' => 'required|bail|integer|exists:\App\Models\Post,id'
        ];
    }
}
