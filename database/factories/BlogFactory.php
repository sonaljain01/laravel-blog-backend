<?php

namespace Database\Factories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BlogFactory extends Factory
{
    protected $model = Blog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'description' => fake()->text(),
            'user_id' => 1,
            'parent_category' => 1,
            'child_category' => 1,
            'tag' => 1,
            'slug' => fake()->name(),
            'type' => 'publish',
            'photo' => 'https://cdn-icons-png.flaticon.com/512/4123/4123763.png',
        ];
    }
}
