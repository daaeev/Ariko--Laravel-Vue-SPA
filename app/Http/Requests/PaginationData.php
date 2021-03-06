<?php

namespace App\Http\Requests;

class PaginationData extends ValidationWithFailedValidationMethod
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            '_limit' => 'nullable|numeric|integer'
        ];
    }
}
