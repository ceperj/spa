<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DropdownProjectResource extends JsonResource
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
            $this->getProjectName($this),
        ];
    }

    function getProjectName($model){
        $startDate = $this->getFormattedDate($this->startDate);
        $endDate = $this->getFormattedDate($this->endDate);
        return $model->projectName . ' (' . $startDate . ' - ' . $endDate . ')';
    }

    function getFormattedDate($date){
        return date('m/Y', strtotime($date));
    }
}
