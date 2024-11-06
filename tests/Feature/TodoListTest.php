<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_store_todo_list(): void
    {
        //prepare

        // perform
        $response = $this->getJson('api/todo-list');
        
        //predict
        $this->assertEquals(1, count($response->json()));
    }
}
