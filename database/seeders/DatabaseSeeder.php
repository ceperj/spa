<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->createTestSuperUser();

        $this->call([
            BanksSeeder::class,
            BatteriesSeeder::class,
            JobsSeeder::class,
            ProjectSeeder::class,
            PersonSeeder::class,
        ]);
    }

    public function createTestSuperUser()
    {
        User::create([
            'username' => 'ezaca',
            'name' => 'Eliakim Zacarias',
            'email' => 'eliakim.zacarias@gmail.com',
            'password' => '$2y$10$.d9lvMKWUKAi6VrZzCG3FuQtlhS6OrtJa/fFOSpPC4lY0lLH8ir2.',
            'role' => 2,
            'status' => 1,
        ]);
    }
}
