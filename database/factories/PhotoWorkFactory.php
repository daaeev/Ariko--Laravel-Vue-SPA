<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PhotoWork>
 */
class PhotoWorkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text(30),
            'subject' => $this->faker->text(50),
            'year' => $this->faker->year,
            'client' => $this->faker->text(50),
            'website' => 'http://test.ua',
            'title' => $this->faker->text(50),
            'description' => $this->faker->text(),
        ];
    }
}
