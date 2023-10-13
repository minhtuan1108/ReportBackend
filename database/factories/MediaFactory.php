<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected array $imageRatio = [
        [1, 1],
        [6, 13],
        [9, 16],
        [3, 5],
        [2, 3],
        [19, 16],
        [5, 4],
        [4, 3],
        [11, 8],
        [3, 2],
        [14, 9],
        [8, 5],
    ];

    public function definition(): array
    {
//        $ratio = $this->imageRatio[array_rand($this->imageRatio)];
//        $rotation = rand(0, 1);
//        $width = $rotation ? $ratio[0] : $ratio[1];
//        $height = $rotation ? $ratio[1] : $ratio[0];
//        $scale = 100;
//        $width *= $scale;
//        $height *= $scale;
//        if ($width > 1000 || $height > 1000){
//            $width /= 2;
//            $height /= 2;
//        }
        return [
            'media_link' => fake()->imageUrl(),
            'local_file' => null,
        ];
    }
}
