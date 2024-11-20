<?php

namespace Tests\Unit;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic unit test example.
     */
    public function test_user_has_many_todo_lists(): void
    {
        $user = $this->createUser();
        $list = $this->createTodoList(['user_id' => $user->id]);

        $this->assertInstanceOf(TodoList::class, $user->todoLists->first());
        
    }
}
