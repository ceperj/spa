<?php

namespace App\Http\Requests;

class StoreInssRequest extends StandardFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'aliquot' => 'required|integer|regex:/^\d+$/',
            'ceil' => 'required|integer|regex:/^\d+$/',
        ];
    }

    public function attributes()
    {
        return [
            'aliquot' => 'alíquota',
            'ceil' => 'teto',
        ];
    }

    public function messages()
    {
        return [
            'integer' => 'O campo :attribute não está com um valor válido.',
            'regex' => 'O inteiro no campo :attribute deve ser positivo.',
        ];
    }
}
