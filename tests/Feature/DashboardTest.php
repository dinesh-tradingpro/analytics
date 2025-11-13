<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_data_requires_authentication(): void
    {
        $response = $this->get('/api/dashboard/new-users');

        $response->assertRedirect('/login');
    }

    public function test_new_users_data_returns_json_when_authenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/api/dashboard/new-users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);
    }

    public function test_active_users_data_requires_authentication(): void
    {
        $response = $this->get('/api/dashboard/active-users');

        $response->assertRedirect('/login');
    }

    public function test_active_users_data_returns_json_when_authenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/api/dashboard/active-users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);
    }

    public function test_inactive_users_data_requires_authentication(): void
    {
        $response = $this->get('/api/dashboard/inactive-users');

        $response->assertRedirect('/login');
    }

    public function test_inactive_users_data_returns_json_when_authenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/api/dashboard/inactive-users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);
    }

    public function test_all_dashboard_data_requires_authentication(): void
    {
        $response = $this->get('/api/dashboard/all-data');

        $response->assertRedirect('/login');
    }

    public function test_all_dashboard_data_returns_json_when_authenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/api/dashboard/all-data');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'new_users',
                    'active_users',
                    'inactive_users',
                ],
                'summary',
            ]);
    }
}
