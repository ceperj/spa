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
            'irpf.*.min_cents' => 'required|regex:/^\d+$/',
            'irpf.*.max_cents' => 'required|regex:/^\d+$/',
            'irpf.*.aliquot' => 'required|regex:/^\d+$/',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'O campo é obrigatório.',
            'array' => 'O formulário está enviando dados corrompidos (deveria enviar um arranjo de campos).',
            'regex' => 'O valor informado não é um número inteiro positivo.',
        ];
    }
}
