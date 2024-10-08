<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeoMetaRequest extends FormRequest
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
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'meta_title.max' => 'Meta name is too long',
            'meta_description.string' => 'Meta description must be a string',
            'meta_description.max' => 'Meta description is too long',
            'meta_title.string' => 'Meta name must be a string',
        ];
    }
}
