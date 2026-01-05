<?php

namespace Tests\Feature;

use App\Models\Membership;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class DashboardUsersTest extends TestCase
{
    use RefreshDatabase;

    private function createOrganization(): Organization
    {
        return Organization::query()->create([
            'name' => 'Test Org',
            'slug' => Str::slug(Str::uuid()->toString()),
        ]);
    }

    private function createUserForOrg(Organization $organization, string $role): User
    {
        $user = User::factory()->create();

        Membership::query()->create([
            'user_id' => $user->id,
            'organization_id' => $organization->id,
            'role' => $role,
        ]);

        return $user;
    }

    public function test_viewer_cannot_access_users_dashboard(): void
    {
        $organization = $this->createOrganization();
        $viewer = $this->createUserForOrg($organization, Membership::ROLE_VIEWER);

        $response = $this->actingAs($viewer)->get('/dashboard/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_users_dashboard(): void
    {
        $organization = $this->createOrganization();
        $admin = $this->createUserForOrg($organization, Membership::ROLE_ADMIN);

        $response = $this->actingAs($admin)->get('/dashboard/users');

        $response->assertOk();
    }

    public function test_store_user_creates_membership_and_avatar(): void
    {
        Storage::fake('public');

        $organization = $this->createOrganization();
        $admin = $this->createUserForOrg($organization, Membership::ROLE_ADMIN);

        $response = $this->actingAs($admin)->post('/dashboard/users', [
            'name' => 'New User',
            'email' => 'new-user@example.com',
            'role' => Membership::ROLE_EDITOR,
            'password' => 'secretpass',
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $response->assertRedirect('/dashboard/users');

        $user = User::query()->where('email', 'new-user@example.com')->firstOrFail();

        $this->assertNotNull($user->avatar_path);
        Storage::disk('public')->assertExists($user->avatar_path);

        $this->assertDatabaseHas('memberships', [
            'user_id' => $user->id,
            'organization_id' => $organization->id,
            'role' => Membership::ROLE_EDITOR,
        ]);
    }

    public function test_update_user_replaces_avatar_and_updates_role(): void
    {
        Storage::fake('public');

        $organization = $this->createOrganization();
        $admin = $this->createUserForOrg($organization, Membership::ROLE_ADMIN);
        $user = $this->createUserForOrg($organization, Membership::ROLE_VIEWER);

        $oldPath = 'avatars/'.$user->id.'/old-avatar.jpg';
        Storage::disk('public')->put($oldPath, 'old');
        $user->forceFill(['avatar_path' => $oldPath])->save();

        $response = $this->actingAs($admin)->patch("/dashboard/users/{$user->id}", [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => Membership::ROLE_EDITOR,
            'avatar' => UploadedFile::fake()->image('new-avatar.jpg'),
        ]);

        $response->assertRedirect('/dashboard/users');

        $user->refresh();
        $this->assertSame('Updated Name', $user->name);
        $this->assertSame('updated@example.com', $user->email);

        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($user->avatar_path);

        $this->assertDatabaseHas('memberships', [
            'user_id' => $user->id,
            'organization_id' => $organization->id,
            'role' => Membership::ROLE_EDITOR,
        ]);
    }

    public function test_delete_user_removes_avatar_and_prevents_self_delete(): void
    {
        Storage::fake('public');

        $organization = $this->createOrganization();
        $admin = $this->createUserForOrg($organization, Membership::ROLE_ADMIN);
        $user = $this->createUserForOrg($organization, Membership::ROLE_VIEWER);

        $path = 'avatars/'.$user->id.'/avatar.jpg';
        Storage::disk('public')->put($path, 'content');
        $user->forceFill(['avatar_path' => $path])->save();

        $response = $this->actingAs($admin)->delete("/dashboard/users/{$user->id}");
        $response->assertRedirect('/dashboard/users');

        Storage::disk('public')->assertMissing($path);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);

        $selfDelete = $this->actingAs($admin)->delete("/dashboard/users/{$admin->id}");
        $selfDelete->assertStatus(403);
    }
}
