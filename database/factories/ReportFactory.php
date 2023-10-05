<?php

namespace Database\Factories;

use App\Enums\ReportStatus;
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
    protected array $statusArr = ['complete', 'sent', 'process', 'ignore'];
    public function definition(): array
    {
        return [
            'title' => fake()->text(100),
            'description' => fake()->text(),
            'location_api' => 'location_api_from_phone',
            'location_text' => fake()->streetAddress(),
            'status' => 'sent',
            'users_id' => User::factory()->create()->id,
        ];
    }
}
