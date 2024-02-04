<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class AdobeServerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'server_address' => ['url','required'],
            'username' => ['string','required'],
            'password' => ['string','required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
