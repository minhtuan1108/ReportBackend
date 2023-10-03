<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected array $statusArr = ['COMPLETE', 'SENT', 'DRAFT', 'PROCESS', 'IGNORE'];
    public function definition(): array
    {
        return [
            'title' => fake()->text(100),
            'description' => fake()->text(),
            'location_api' => 'current_location',
            'location_text' => fake()->streetAddress(),
            'status' => $this->statusArr[array_rand($this->statusArr)],
            'users_id' => User::factory()->create()->id,
        ];
    }
}
