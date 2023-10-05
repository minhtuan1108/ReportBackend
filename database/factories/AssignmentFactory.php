<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assignment>
 */
class AssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reports_id' => Report::factory()->create()->id,
            'worker_id' => User::factory()->create()->id,
            'manager_id' => User::factory()->create()->id,
            'note' => fake()->text(100)
        ];
    }
}
