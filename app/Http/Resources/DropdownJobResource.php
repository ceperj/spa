<?php

namespace App\Http\Resources;

use App\Constants;
use Illuminate\Http\Resources\Json\JsonResource;

class DropdownJobResource extends JsonResource
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
            $this->name,
        ];
    }
}
