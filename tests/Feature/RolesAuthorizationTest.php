<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_with_only_roles_index_cannot_edit_or_delete_roles(): void
    {
        // Arrange: user with only roles.index permission
        $user = User::factory()->create();
        Permission::firstOrCreate(['name' => 'roles.index', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'roles.edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'roles.delete', 'guard_name' => 'web']);
        $user->givePermissionTo('roles.index');

        $role = Role::firstOrCreate(['name' => 'temporal', 'guard_name' => 'web']);

        // Act & Assert: index is allowed
        $this->actingAs($user)
            ->get(route('admin.roles.index'))
            ->assertStatus(200);

        // Edit page should be forbidden
        $this->actingAs($user)
            ->get(route('admin.roles.edit', $role))
            ->assertStatus(403);

        // Destroy should be forbidden
        $this->actingAs($user)
            ->delete(route('admin.roles.destroy', $role))
            ->assertStatus(403);
    }
}
