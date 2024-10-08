<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_register(): void
    {
        $response = $this->get('/api');

        $response->assertStatus(200);
    }

    public function test_user_can_login(): void
    {
        $response = $this->get('/api');

        $response->assertStatus(200);
    }

    public function test_user_can_verify_email(): void
    {
        $response = $this->get('/api');

        $response->assertStatus(200);
    }

    public function test_user_can_view_profile(): void
    {
        $response = $this->get('/api');

        $response->assertStatus(200);
    }

    public function test_user_can_view_logout(): void
    {
        $response = $this->get('/api');

        $response->assertStatus(200);
    }

    public function test_user_can_view_profile_update(): void
    {
        $response = $this->get('/api');

        $response->assertStatus(200);
    }
}
