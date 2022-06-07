<?php

namespace App\Http\Requests;

class BatteryRequest extends StandardFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'battery' => 'required|array',
            'battery.*.date' => 'required|integer|min:1|max:31',
            'battery.*.status' => 'required|integer|min:0',
        ];
    }

    public function attributes()
    {
        return [
            'battery' => 'Bateria de Pagamento (conjunto de dados)',
            'battery.*.date' => 'Dia do Mês',
            'battery.*.status' => 'Situação do Item',
        ];
    }
}
