<?php

namespace App\Http\Requests;

class CreatePost extends ValidationWithFailedValidationMethod
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'main_image' => 'required|image',
            'preview_image' => 'nullable|image',
        ];
    }
}
