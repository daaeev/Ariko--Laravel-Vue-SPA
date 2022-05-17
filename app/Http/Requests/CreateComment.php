<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CreateComment extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

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

    public function failedValidation(Validator $validator)
    {
        throw new HttpException(422, 'Bad request params');
    }
}
