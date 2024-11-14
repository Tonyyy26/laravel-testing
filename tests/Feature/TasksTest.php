<?php

namespace Tests\Feature;

use App\Models\Tasks;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TasksTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_fetch_all_tasks_of_a_todo_list(): void
    {
        // preparation
        $task = Tasks::factory()->create();
        // action
        $response = $this->getJson(route('tasks.index'))
            ->assertOk()
            ->json();
        // assertion
        $this->assertEquals(1, count($response));
        $this->assertEquals($task->title, $response[0]['title']);
    }
}
