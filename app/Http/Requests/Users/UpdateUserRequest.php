<?php

namespace App\Http\Requests\Users;

use App\Rules\CanManageRoleRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()?->can('users.user.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'phone'      => 'required|numeric|regex:/09[0-9]{9}/|unique:users,phone,' . $this->id,
            'email'      => 'required|string|email|max:255|unique:users,email,' . $this->id,
            'roles'      => ['required', 'array', new CanManageRoleRule()],
            'password'   => ['nullable', 'confirmed'],
        ];
    }
}
