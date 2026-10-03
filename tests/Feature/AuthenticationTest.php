<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        foreach (['/admin/dashboard', '/admin/categories', '/admin/products', '/admin/settings'] as $path) {
            $this->get($path)->assertRedirect(route('login'));
        }
    }

    public function test_standard_login_path_returns_not_found(): void
    {
        $this->get('/login')->assertNotFound();
    }

    public function test_login_page_renders_for_guests(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Auth/Login')
                ->where('submitUrl', route('login', absolute: false))
            );
    }

    public function test_user_can_log_in(): void
    {
        $user = User::factory()->create();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertRedirect('/admin/dashboard');

        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_credentials_do_not_authenticate_user(): void
    {
        $user = User::factory()->create();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'incorrect-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_authenticated_user_is_redirected_away_from_login(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('login'))
            ->assertRedirect('/admin/dashboard');
    }

    public function test_user_can_log_out(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/admin/logout')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_user_can_update_their_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('current-password'),
        ]);

        $this->actingAs($user)
            ->from('/admin/settings')
            ->put('/admin/settings/password', [
                'current_password' => 'current-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ])
            ->assertRedirect('/admin/settings')
            ->assertSessionHas('success', 'Password updated. Other devices have been signed out.');

        $this->assertCredentials([
            'email' => $user->email,
            'password' => 'new-password',
        ]);
        $this->assertAuthenticatedAs($user);
    }

    public function test_password_update_requires_current_password_and_confirmation(): void
    {
        $this->actingAs(User::factory()->create())
            ->from('/admin/settings')
            ->put('/admin/settings/password', [
                'current_password' => 'incorrect-password',
                'password' => 'new-password',
                'password_confirmation' => 'different-password',
            ])
            ->assertSessionHasErrors(['current_password', 'password']);
    }
}
