<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Mockery;
use Http;
use  App\Models\User;
use Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    /**
     * A basic feature test example.
     */
    public function it_registers_a_user(): void
    {
        Http::fake();

        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'type' => 'user',
        ];

        User::shouldReceive('create')
            ->once()
            ->with(new User($userData));

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'User registered successfully, Please verify Your Email',
            ]);
    }

    public function a_user_can_login()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        JWTAuth::shouldReceive('attempt')
            ->once()
            ->with([
                'email' => $user->email,
                'password' => 'password',
            ])
            ->andReturn(true);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'User logged in succcessfully',
            ]);
    }

    public function it_verifies_email()
    {
        $user = User::factory()->create([
            'remember_token' => 'randomToken',
            'email_verified_at' => null,
        ]);

        $response = $this->get('/api/verify/email/'.$user->id.'/randomToken');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Email verified successfully',
            ]);
    }

    public function it_logs_out()
    {
        JWTAuth::shouldReceive('getToken')->andReturn('randomToken');
        JWTAuth::shouldReceive('invalidate')->once()->with('randomToken')->andReturn(true);

        $response = $this->get('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Successfully logged out',
                ],
                'data' => [],
            ]);
    }
}
