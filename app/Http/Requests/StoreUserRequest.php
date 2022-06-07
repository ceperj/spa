<?php

namespace App\Http\Requests;

class StoreUserRequest extends BaseUserRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->getRules('required');
    }
}
