<?php

namespace App\Http\Resources;

use App\Constants;
use Illuminate\Http\Resources\Json\JsonResource;

class ListableProjectResource extends JsonResource
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
            'statusText' => Constants::getStatusText($this->status),
        ];
    }
}
