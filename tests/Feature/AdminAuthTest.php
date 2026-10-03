<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\AdminUserSeeder::class);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
        $response->assertSee('LS Tech Administration');
    }

    public function test_admin_can_authenticate_and_access_dashboard(): void
    {
        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@lstech.com',
            'password' => 'Admin@123456',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard'));

        $dashboardResponse = $this->get(route('admin.dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Executive Dashboard');
    }

    public function test_unauthenticated_users_are_redirected_to_login(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_inactive_users_cannot_login(): void
    {
        User::create([
            'name' => 'Disabled Admin',
            'email' => 'disabled@lstech.com',
            'password' => Hash::make('Admin@123456'),
            'role' => 'admin',
            'status' => 'inactive',
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'disabled@lstech.com',
            'password' => 'Admin@123456',
        ]);

        $this->assertGuest();
    }
}
