<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     */
    public function test_user_can_register(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/api/auth/register') // Change this to your actual registration page
                ->type('name', 'Test User') // The name of the input field
                ->type('email', 'testuser@example.com') // The name of the input field
                ->type('password', 'password') // The name of the input field
                ->type('password_confirmation', 'password') // The name of the input field
                ->type('type', 'user') // The name of the input field
                ->press('Register') // The text on the button
                ->waitForText('User registered successfully, Please verify Your Email', 10) // Wait for success message
                ->assertSee('User registered successfully, Please verify Your Email'); // Assert the message
        });
    }
}
