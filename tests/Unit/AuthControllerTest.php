<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;
class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'type' => 'user',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'User registered successfully, Please verify Your Email',
                 ]);
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

        $response = $this->getJson('/api/verify/email/' . $user->id . '/randomToken');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Email verified successfully',
                 ]);


        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $token = JWTAuth::fromUser($user);
        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->getJson('api/user/logout');

        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'status' => 'success',
                    'message' => 'Successfully logged out',
                ],
            ]);
    }
}
