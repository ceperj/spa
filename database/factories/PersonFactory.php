<?php

namespace Database\Factories;

use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        
        return [
            'name' => $this->faker->name(),
            'cpf' => $this->faker->regexify('[0-9]{11}'),
            'pis' => $this->faker->regexify('[0-9]{11}'),
            'rg' => $this->faker->regexify('[0-9]{7}'),
            'rgexp' => Str::random(5),
            'bank_agency' => $this->faker->randomNumber(4),
            'bank_account' => $this->faker->randomNumber(5),
            'project_id' => 1,
            'bank_id' => 1,
            'job_id' => 1,
            'battery_id' => 1,
            'user_id' => 1,
            'status' => 1,
            'email' => $this->faker->unique()->safeEmail(),
            'salary' => $this->faker->numberBetween(121200, 1000000),
            'admission_date' => $this->faker->dateTimeBetween('-10 year', 'now'),
            'registration_number' => $this->faker->regexify('[0-9]{11}'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
