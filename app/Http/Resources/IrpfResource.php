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
            'd_min' => sprintf("%.2f", $this->min_cents / 100.0),
            'd_max' => sprintf("%.2f", $this->max_cents / 100.0),
            'd_aliquot' => sprintf("%.g%%", $this->aliquot / 100.0),
            'd_aliquot_f' => sprintf("%.4f", $this->aliquot / 10000.0),
        ];
    }
}
