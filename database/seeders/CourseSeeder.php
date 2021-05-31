<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $courses = collect([
            'Object Oriented Programming',
            'Design Patterns',
            'Algorithms',
            'PHP',
            'Docker',
            'JavaScript',
            'NodeJs',
            'Machine Learning',
            'Accounting',
            'Finance Fundamentals',
            'Bookkeeping ',
        ]);

        $courses->each(function ($course) {
            Course::factory(1)->create([
                'name_en' => $course,
            ]);
        });
    }
}
