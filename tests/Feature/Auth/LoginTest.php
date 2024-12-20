<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     */
    public function test_a_user_can_login_with_email_and_password(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('user.login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
        ->assertOk();

        $this->assertArrayHasKey('token', $response->json());
    }

    public function test_if_user_email_is_not_available_then_it_return_error()
    {
        $this->postJson(route('user.login'), [
            'email' => 'test@gmail.com',
            'password' => 'secret',
        ])->assertUnauthorized();

    }
    
    public function test_it_raise_error_if_password_is_incorrect()
    {
        $user = User::factory()->create();

        $this->postJson(route('user.login'), [
            'email' => $user->email,
            'password' => 'random',
        ])->assertUnauthorized();

    }
}
