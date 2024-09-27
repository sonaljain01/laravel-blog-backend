<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class ParentCatrgoryRequest extends FormRequest
{
    protected string $err;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!auth()->check()) {
            return false;
        }
        if (!auth()->user()->type === "admin") {
            $this->err = "You need to be admin to create category";
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
            "name" => "required|string|max:255",
            "image" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'name.max' => 'Name is too long',
            'image.mimes'=> 'image must be in form of jpeg,png,jpg,gif',
            'image.max' => 'image is too large',
            'image.image' => 'image must be an image',
        ];
    }
}
