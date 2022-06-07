<?php

namespace App\Http\Requests;

use App\Constants;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends StoreProjectRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['status'] = ['required', Rule::in([Constants::STATUS_ACTIVE, Constants::STATUS_INACTIVE])];
        return $rules;
    }
}
