<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RatingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!auth()->check()) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rating' => 'required|integer|between:1,5',
            'blog_id' => 'required|integer|exists:blogs,id',
            'user_id' => 'required|integer|exists:users,id',
        ];
    }
    public function messages(): array
    {
        return [
            'rating.required' => 'Rating is required',
            'rating.integer' => 'Rating must be an integer',
            'rating.between' => 'Rating must be between 1 and 5',
            'blog_id.required' => 'Blog id is required',
            'blog_id.integer' => 'Blog id must be an integer',
            'blog_id.exists' => 'Blog id does not exist',
            'user_id.required' => 'User id is required',
            'user_id.integer' => 'User id must be an integer',
            'user_id.exists' => 'User id does not exist',
        ];
    }
}
