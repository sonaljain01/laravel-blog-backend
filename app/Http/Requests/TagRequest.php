<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
{
    protected string $err;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (! auth()->check()) {
            return false;
        }
        if (! auth()->user()->type === 'admin') {
            $this->err = 'You need to be admin to create tag';

            return false;
        }

        return true;
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException($this->err);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tag name is required',
        ];
    }
}
