<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     */
    public function test_user_can_register()
    {
        $this->postJson(route('user.register'), [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ])->assertCreated();

        $this->assertDatabaseHas('users', ['name' => 'test']);
    }
}
