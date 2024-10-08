<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterUserCommandTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_register_via_console(): void
    {
        $this->artisan('user:register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'type' => 'user',
        ])
            ->expectsOutput('User registered successfully')
            ->assertExitCode(0);

        // Check if user is created in the database
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }
}
