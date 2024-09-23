<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(auth()->check()) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048|nullable',
        ];
    }

    public function messages(): array {
        return [
            'title.required' => 'Title is required',
            'title.max' => 'Title is too long',
            'title.string' => 'Title must be a string',
            'description.required' => 'Description is required',
            'description.string' => 'Description must be a string',
        ];
    }
}
