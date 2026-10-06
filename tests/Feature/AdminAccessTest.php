<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_export_requires_authentication(): void
    {
        $this->get(route('admin.exportDatabase'))
            ->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_export_database_or_manage_users(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('admin.exportDatabase'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('users.index'))
            ->assertForbidden();
    }

    public function test_admin_can_access_user_management(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('users.index'))
            ->assertOk();
    }
}
