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
        if (auth()->check()) {
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
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048|required',
            "category" => "required|string|exists:parent_categories,id",
            "tag" => "required|string|exists:tags,id",
            "sub_category" => "required|string|exists:child_categories,id",
            "slug" => "nullable|unique:blogs,slug|string|max:255",
            "type" => "required|string"
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required',
            'title.max' => 'Title is too long',
            'title.string' => 'Title must be a string',
            'description.required' => 'Description is required',
            'description.string' => 'Description must be a string',
            'image.image' => 'Image must be an image',
            'image.mimes' => 'Image must be a jpeg,png,jpg,gif',
            'image.max' => 'Image is too large',
            'category.required' => 'Category is required',
            'category.string' => 'Category must be a string',
            'tag.required' => 'Tag is required',
            'tag.string' => 'Tag must be a string',
            'sub_category.required' => 'Sub category is required',
            'sub_category.string' => 'Sub category must be a string',
            'slug.string' => 'Slug must be a string',
            'slug.max' => 'Slug is too long',
            'type.required' => 'Type is required',
            'type.string' => 'Type must be a string',
        ];
    }
}
