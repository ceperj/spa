<?php

namespace App\Http\Requests;

class StoreJobRequest extends AdminFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'status' => 'required|integer|in:0,1'
        ];
    }
}
