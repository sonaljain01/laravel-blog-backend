<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            "name" => "nullable|string|max:255",
            "profile_image" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            "email" => "nullable|email|max:255|unique:users,email," . auth()->user()->id,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'name.max' => 'Name is too long',
            'profile_image.mimes' => 'image must be in form of jpeg,png,jpg,gif',
            'profile_image.max' => 'image is too large',
            'profile_image.image' => 'image must be an image',
        ];
    }
}
