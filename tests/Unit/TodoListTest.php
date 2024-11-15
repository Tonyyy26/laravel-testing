<?php

namespace Tests\Unit;

use App\Models\Tasks;
use Illuminate\Console\View\Components\Task;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic unit test example.
     */
    public function test_a_todo_list_can_has_many_tasks(): void
    {
        $list = $this->createTodoList();
        $this->createTasks(['todo_list_id' => $list->id]);

        $this->assertInstanceOf(Tasks::class, $list->tasks->first());
    }
}
