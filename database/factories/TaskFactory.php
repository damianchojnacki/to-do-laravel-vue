<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(5),
            'status' => collect(TaskStatus::cases())->random(),
            'created_at' => now()->subHours(rand(0, 72)),
            'updated_at' => fn ($attributes) => $attributes['created_at']
        ];
    }
}
