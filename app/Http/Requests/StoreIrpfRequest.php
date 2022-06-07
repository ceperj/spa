<?php

namespace App\Http\Requests;

class StoreIrpfRequest extends StandardFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'irpf' => 'required|array',
            'irpf.*.min_cents' => 'required|integer',
            'irpf.*.max_cents' => 'required|integer',
            'irpf.*.aliquot' => 'required|integer',
        ];
    }
}
