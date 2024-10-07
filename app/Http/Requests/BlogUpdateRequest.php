<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class BlogUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    protected $error = '';

    public function authorize(): bool
    {
        if (! auth()->check()) {
            $this->error = 'Please login first';

            return false;
        }

        return true;
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException($this->error);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048|nullable',
            'parent_category' => 'nullable|string|exists:parent_categories,id',
            'tag' => 'nullable|string|exists:tags,id',
            'child_category' => 'nullable|string|exists:child_categories,id',
            'slug' => 'nullable|unique:blogs,slug|string|max:255',
            'type' => 'nullable|string',
        ];
    }

    public function message(): array
    {
        return [

            'title.max' => 'Title is too long',
            'title.string' => 'Title must be a string',
            'description.string' => 'Description must be a string',
            'image.image' => 'Image must be an image',
            'image.mimes' => 'Image must be a jpeg,png,jpg,gif',
            'image.max' => 'Image is too large',
            'category.string' => 'Category must be a string',
            'tag.string' => 'Tag must be a string',
            'sub_category.string' => 'Sub category must be a string',
            'slug.string' => 'Slug must be a string',
            'slug.max' => 'Slug is too long',
            'type.string' => 'Type must be a string',
        ];
    }
}
