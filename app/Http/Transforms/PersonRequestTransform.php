<?php

namespace App\Http\Transforms;

use App\Models\Bank;
use App\Models\Battery;
use App\Models\Job;
use App\Models\Project;

class PersonRequestTransform extends BaseTransform
{

    public function transform($input)
    {
        $notNull = array_filter($input, static fn($value) => $value !== null);

        return array_merge($notNull, [
            'project_id' => $this->modelId($input['project_id'], Project::class),
            'job_id' => $this->modelId($input['job_id'], Job::class),
            'battery_id' => $this->modelId($input['battery_id'], Battery::class),
            'bank_id' => $this->modelId($input['bank_id'], Bank::class),
        ]);
    }

}