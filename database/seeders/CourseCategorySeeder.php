<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Project Management',
            'Quality Assurance',
            'Mobile App Development',
            'Networking',
            'IT Executive',
            'HR Management',
        ];

        foreach ($categories as $category) {
            CourseCategory::create(['name' => $category]);
        }

    }
}
