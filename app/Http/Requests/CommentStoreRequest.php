<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (! auth()->check()) {
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
            'blog_id' => 'required|exists:blogs,id',
            'comment' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'comment.required' => 'Comment is required',
            'comment.string' => 'Comment must be string',
            'blog_id.required' => 'Blog id is required',
            'blog_id.exists' => 'Blog id does not exist',
        ];
    }
}
