<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogDeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $user_id = auth()->user()->id;
        //only authorized user can update notes 
        // if (Blog::where('id', $this->blog_id)->where('user_id', $user_id)->exists()) {
        //     return true;
        // }

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
            'blog_id' => 'required|integer|exists:blogs,id',
        ];
    }
}
