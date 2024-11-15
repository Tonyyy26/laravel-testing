<?php

namespace Database\Factories;

use App\Models\TodoList;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tasks>
 */
class TasksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $todoListIds = TodoList::pluck('id')->toArray();

        return [
            'title'       => $this->faker->sentence(),
            'todo_list_id' => function() use ($todoListIds) {
                return $todoListIds[array_rand($todoListIds)];
            }
        ];
    }
}
