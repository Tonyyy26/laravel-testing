<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use DatabaseTransactions;
    protected $user;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->authUser();
    }

    public function test_a_user_can_connect_to_a_service_and_token_is_stored(): void
    {
        $response = $this->getJson(route('web-service.connect', 'google-drive'))
            ->assertOk()
            ->json();
        
        $this->assertNotNull($response['url']);
    }

    public function test_service_callback_will_store_token()
    {
        $this->postJson(route('web-service.callback'), [
            'code' => 'dummyCode',
        ])
            ->assertCreated();

        $this->assertDatabaseHas('web_services', [
            'user_id' => $this->user->id
        ]);
    }
}
