<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_page_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Daftar Sebagai Peran (Role)');
    }

    public function test_user_can_register_as_regular_user_and_redirect_to_user_dashboard(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'user@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'user',
            'terms'                 => 'on',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        $user = User::where('email', 'user@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->isUser());
    }

    public function test_user_can_register_as_admin_and_redirect_to_admin_dashboard(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Test Admin',
            'email'                 => 'admin@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'admin',
            'terms'                 => 'on',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticated();

        $user = User::where('email', 'admin@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->isAdmin());
    }

    public function test_registration_fails_with_invalid_role(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Test Hacker',
            'email'                 => 'hacker@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'super_god_mode',
            'terms'                 => 'on',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertGuest();
    }
}
