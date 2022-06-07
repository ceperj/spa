<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            $this->hashid,
            $this->getCodeAndName($this),
        ];
    }

    function getCodeAndName($model){
        return $this->getPaddedCode($model->code) . ' - ' . $model->name;
    }

    function getPaddedCode($code)
    {
        return str_pad($code, 3, '0', STR_PAD_LEFT);
    }
}
