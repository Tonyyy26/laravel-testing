<?php

namespace Tests\Feature;

use App\Models\WebService;
use Google\Client;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
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
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setScopes');
            $mock->shouldReceive('createAuthUrl')
                ->andReturn('http://127.0.0.1:8000/');
        });

        $response = $this->getJson(route('web-service.connect', 'google-drive'))
            ->assertOk()
            ->json();
        
        $this->assertEquals('http://127.0.0.1:8000/', $response['url']);
        $this->assertNotNull($response['url']);
    }

    public function test_service_callback_will_store_token()
    {
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetchAccessTokenWithAuthCode')
            ->andReturn('fake-token');
        });

        $this->postJson(route('web-service.callback'), [
            'code' => 'dummyCode',
        ])->assertCreated();

        $this->assertDatabaseHas('web_services', [
            'user_id' => $this->user->id,
            'token' => '"{\"access_token\":\"fake-token\"}"',
        ]);

    }

    public function test_data_of_a_week_can_be_stored_on_google_drive()
    {
        $this->createTasks(['created_at' => now()->subDays(2)]);
        $this->createTasks(['created_at' => now()->subDays(3)]);
        $this->createTasks(['created_at' => now()->subDays(4)]);
        $this->createTasks(['created_at' => now()->subDays(6)]);

        $this->createTasks(['created_at' => now()->subDays(10)]);
        
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setAccessToken');
            $mock->shouldReceive('getLogger->info');
            $mock->shouldReceive('getUniverseDomain');
            $mock->shouldReceive('shouldDefer');
            $mock->shouldReceive('execute');
        });

        $webService = $this->createWebService();
        $this->postJson(route('web-service.store', $webService->id))
            ->assertCreated();


    }
}
