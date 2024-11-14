<?php

namespace Tests\Feature;

use App\Models\Tasks;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TasksTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     */
    public function test_fetch_all_tasks_of_a_todo_list(): void
    {
        // preparation
        $list = $this->createTodoList();
        $task = $this->createTasks();
        // action
        $response = $this->getJson(route('todo-list.tasks.index', $list->id))
            ->assertOk()
            ->json();
        // assertion
        $this->assertEquals(1, count($response));
        $this->assertEquals($task->title, $response[0]['title']);
    }

    public function test_store_a_task_for_a_todo_list()
    {
        $list = $this->createTodoList();
        $task = Tasks::factory()->make();
        $this->postJson(route('todo-list.tasks.store', $list->id), ['title' => $task->title])
            ->assertCreated();
        $this->assertDatabaseHas('tasks', ['title' => $task->title]);
    }

    public function test_delete_a_task_from_database()
    {
        $task = $this->createTasks();
        $this->deleteJson(route('tasks.destroy', $task->id))
            ->assertNoContent();
        $this->assertDatabaseMissing('tasks', ['title' => $task->title]);
    }

    public function test_update_a_task_of_a_todo_list()
    {
         // preparation
         $task = $this->createTasks();
         // action
        $this->patchJson(route('tasks.update', $task->id), ['title' => 'updated title'])
            ->assertOk();
         //assert
        $this->assertDatabaseHas('tasks', ['title' => 'updated title']);
    }
}
