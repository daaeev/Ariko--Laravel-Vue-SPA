<?php

namespace App\Http\Requests;

class AddImagesToWork extends ValidationWithFailedValidationMethod
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'images' => 'required|array',
            'images.*' => 'image',
            'work_id' => 'required|bail|integer|exists:\App\Models\PhotoWork,id'
        ];
    }
}
