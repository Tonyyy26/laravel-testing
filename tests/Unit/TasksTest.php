<?php

namespace Tests\Unit;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TasksTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic unit test example.
     */
    public function test_a_task_belongs_to_a_todo_list(): void
    {
        $list = $this->createTodoList();
        $task = $this->createTasks(['todo_list_id' => $list->id]);

        $this->assertInstanceOf(TodoList::class, $task->todoList);
    }
}
