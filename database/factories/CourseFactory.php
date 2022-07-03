<?php

namespace Database\Factories;

use App\Models\CourseCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title'              => $this->faker->sentence,
            'description'        => $this->faker->paragraph,
            'course_category_id' => CourseCategory::inRandomOrder()->first()->id,
            'price'              => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}
