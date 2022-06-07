<?php

namespace App\Http\Requests;

use App\Constants;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends BaseUserRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->route('user');
        return $this->getRules('sometimes', $user->id);
    }
}
