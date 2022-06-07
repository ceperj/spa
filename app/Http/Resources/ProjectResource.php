<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class ProjectResource extends JsonResource
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
            'projectName' => $this->projectName,
            'sector' => $this->sector,
            'process' => $this->process,
            'projectManager' => $this->projectManager,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'status' => $this->status,
            'updated_at' => $this->updatedAt,
        ];
    }
}
