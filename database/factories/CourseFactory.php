<?php

namespace Database\Factories;

use App\Enums\CourseStatus;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $user = User::role([UserRole::ADMIN, UserRole::TUTOR, UserRole::INSTITUTE])->inRandomOrder()->first();

        $category = Category::inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'name_en' => $this->faker->word,
            'description' => $this->faker->sentence(),
            'minimum_seats' => 2,
            'maximum_seats' => 20,
            'cost' => $this->faker->randomFloat(1, 0, 100),
            'status' => $this->faker->randomElement(CourseStatus::all()),
            'created_at' => $this->faker->dateTimeBetween('-5 years', 'now')
        ];
    }
}
