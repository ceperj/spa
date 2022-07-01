<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IrpfResource extends JsonResource
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
            'id' => $this->hashid,
            'min_cents' => $this->min_cents,
            'max_cents' => $this->max_cents,
            'aliquot' => $this->aliquot,
        ];
    }
}
