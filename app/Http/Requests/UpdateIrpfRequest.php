<?php

namespace App\Http\Requests;

class UpdateIrpfRequest extends StandardFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'min_cents' => 'required|integer',
            'max_cents' => 'required|integer',
            'aliquot' => 'required|integer',
        ];
    }
}
