<?php

namespace Tests\Feature;

use App\Models\User;
use Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake(); // Fake the external email service API
    }

    /**
     * A basic feature test example.
     */
    public function test_user_can_register(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'type' => 'user',
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'User registered successfully, Please verify Your Email',
            ]);

        $this->assertDatabaseHas('users', ['email' => 'testuser@example.com']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

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

    public function test_email_verification()
    {
        $user = User::factory()->create([
            'remember_token' => 'randomToken',
            'email_verified_at' => null,
        ]);

        $response = $this->getJson('/api/verify/email/'.$user->id.'/randomToken');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Email verified successfully',
            ]);

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function user_can_access_profile()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->getJson('/api/user/profile');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ]);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->getJson('/api/user/logout');

        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'status' => 'success',
                    'message' => 'Successfully logged out',
                ],
            ]);
    }
}
