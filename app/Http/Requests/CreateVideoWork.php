<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVideoWork extends ValidationWithFailedValidationMethod
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:30',
            'subject' => 'required|string|max:50',
            'year' => 'required|max:10',
            'client' => 'nullable|string|max:50',
            'website' => 'nullable|string|url|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'video' => 'required|mimes:mp4,x-flv,x-mpegURL,MP2T,3gpp,quicktime,x-msvideo,x-ms-wmv',
        ];
    }
}
