<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
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
            'name' => $this->name,
            'cpf' => $this->cpf,
            'rg' => $this->rg,
            'rgexp' => $this->rgexp,
            'pis' => $this->pis,
            'phone1' => $this->phone1,
            'phone2' => $this->phone2,
            'project_id' => $this->project->hashid,
            'bank_id' => $this->bank->hashid,
            'bank_agency' => $this->bank_agency,
            'bank_account' => $this->bank_account,
            'battery_id' => $this->battery->hashid,
            'email' => $this->email,
            'job_id' => $this->job->hashid,
            'status' => $this->status,
        ];
    }
}
