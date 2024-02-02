<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name'       => 'required|string',
            'last_name'        => 'required|string',
            'email'            => 'required|string|email|max:255|unique:users,email,' . $this->user()->id,
            'current_password' => ['present',Rule::requiredIf(fn () => $this->user()->password && $this->password)],
            'password'         => ['required_with:current_password','confirmed'],
        ];
    }
}
