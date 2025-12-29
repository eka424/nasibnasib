<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_routes(): void
    {
        $admin = User::factory()->role('admin')->create();

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertOk();
    }

    public function test_pengurus_cannot_manage_users(): void
    {
        $pengurus = User::factory()->role('pengurus')->create();

        $response = $this->actingAs($pengurus)->get('/admin/users');

        $response->assertForbidden();
    }

    public function test_jamaah_cannot_access_admin_area(): void
    {
        $jamaah = User::factory()->role('jamaah')->create();

        $response = $this->actingAs($jamaah)->get('/admin/kegiatans');

        $response->assertForbidden();
    }

    public function test_ustadz_area_requires_ustadz_role(): void
    {
        $ustadz = User::factory()->role('ustadz')->create();

        $response = $this->actingAs($ustadz)->get('/ustadz/pertanyaan');

        $response->assertOk();

        $jamaah = User::factory()->role('jamaah')->create();

        $this->actingAs($jamaah)->get('/ustadz/pertanyaan')->assertForbidden();
    }
}
