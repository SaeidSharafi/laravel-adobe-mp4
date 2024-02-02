<?php

namespace App\Http\Requests\Users;

use App\Rules\CanManageRoleRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'          => 'required|alpha_num',
            'label'         => 'required',
            'allowoverride' => ['required','array',new CanManageRoleRule()],
        ];
    }

    public function authorize(): bool
    {
        return auth()->user()?->can('users.role.create');
    }
}
