<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_last_admin_cannot_be_deleted(): void
    {
        $admin = User::factory()->role('admin')->create();

        $response = $this->actingAs($admin)->from('/admin/users')->delete("/admin/users/{$admin->id}");

        $response->assertSessionHasErrors('role');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_last_admin_cannot_be_demoted(): void
    {
        $admin = User::factory()->role('admin')->create();

        $response = $this->actingAs($admin)->from("/admin/users/{$admin->id}/edit")->put("/admin/users/{$admin->id}", [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'pengurus',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertEquals('admin', $admin->fresh()->role);
    }
}
