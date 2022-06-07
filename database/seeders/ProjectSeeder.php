<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::create([
            'projectName' => 'MyProject',
            'sector' => 'MySector',
            'process' => 'MyProcess',
            'projectManager' => 'MyManager',
            'startDate' => now(),
            'endDate' => now(),
            'status' => 1,
            'user_id' => 1,
        ]);
    }
}
