<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
        $this->markTestSkipped('Registration is disabled in this application (no /register route).');

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertNoContent();

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        $this->assertTrue(User::where('email', 'test@example.com')->exists());
    }
}