<?php

namespace App\Http\Requests;

use App\Constants;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class BaseUserRequest extends AdminFormRequest
{
    public function getRules($requirementLevel, $ignoreId = -1)
    {
        return [
            'username' => [
                $requirementLevel,
                'string',
                'between:3,192',
                Rule::unique(User::class, 'username')->ignore($ignoreId),
            ],
            'email' => [
                $requirementLevel,
                'string',
                'between:5,192',
                Rule::unique(User::class, 'email')->ignore($ignoreId),
            ],
            'password' => [
                $requirementLevel,
                'string',
                'confirmed',
                Password::min(8),
            ],
            'role' => [
                $requirementLevel,
                'integer',
                Rule::in([Constants::USER_ROLE_STANDARD, Constants::USER_ROLE_ADMIN]),
            ],
            'status' => [
                $requirementLevel,
                'integer',
                Rule::in([Constants::STATUS_ACTIVE, Constants::STATUS_INACTIVE]),
            ],
            'password_confirmation' => 'required_with:password',
            'name' => $requirementLevel.'|string|between:2,192',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nome Completo',
        ];
    }
}