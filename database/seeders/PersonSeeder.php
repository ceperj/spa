<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\Salary;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Person::factory()
            ->count(10)
            ->create(['status' => 0]);

        Person::factory()
            ->count(100)
            ->create();
    }
}
