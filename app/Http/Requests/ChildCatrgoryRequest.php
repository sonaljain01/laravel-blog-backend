<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChildCatrgoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (! auth()->check()) {
            return false;
        }
        if (! auth()->user()->type === 'admin') {
            $this->err = 'You need to be admin to create child category';

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
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:parent_categories,id',
        ];
    }
}
