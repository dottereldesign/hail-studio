<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDashboardUserRequest;
use App\Http\Requests\UpdateDashboardUserRequest;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class DashboardUsersController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize('dashboard.users.manage');

        $organizationId = $request->user()->currentOrganizationId();

        $memberships = Membership::query()
            ->with('user')
            ->where('organization_id', $organizationId)
            ->orderBy('created_at')
            ->get();

        $users = $memberships->map(function (Membership $membership) {
            $user = $membership->user;

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatarUrl' => $user->avatar_url,
                'role' => $membership->role,
            ];
        });

        return Inertia::render('Dashboard/Users', [
            'users' => $users,
        ]);
    }

    public function store(StoreDashboardUserRequest $request): RedirectResponse
    {
        $organizationId = $request->user()->currentOrganizationId();

        $user = User::query()->create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        Membership::query()->create([
            'user_id' => $user->id,
            'organization_id' => $organizationId,
            'role' => $request->input('role'),
        ]);

        $this->handleAvatarUpload($user, $request->file('avatar'));

        return redirect()->route('dashboard.users.index');
    }

    public function update(UpdateDashboardUserRequest $request, User $user): RedirectResponse
    {
        $organizationId = $request->user()->currentOrganizationId();

        $membership = Membership::query()
            ->where('organization_id', $organizationId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $user->fill([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);

        if ($request->filled('password')) {
            $user->password = $request->input('password');
        }

        $user->save();

        $membership->update([
            'role' => $request->input('role'),
        ]);

        $this->handleAvatarUpload($user, $request->file('avatar'));

        return redirect()->route('dashboard.users.index');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('dashboard.users.manage');

        if ($request->user()->id === $user->id) {
            abort(403, 'You cannot delete your own account.');
        }

        $organizationId = $request->user()->currentOrganizationId();

        Membership::query()
            ->where('organization_id', $organizationId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $this->deleteAvatar($user);

        $user->delete();

        return redirect()->route('dashboard.users.index');
    }

    private function handleAvatarUpload(User $user, ?\Illuminate\Http\UploadedFile $file): void
    {
        if (! $file) {
            return;
        }

        $this->deleteAvatar($user);

        $extension = $file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg';
        $filename = Str::uuid().'.'.$extension;
        $path = $file->storeAs('avatars/'.$user->id, $filename, 'public');

        $user->forceFill(['avatar_path' => $path])->save();
    }

    private function deleteAvatar(User $user): void
    {
        if (! $user->avatar_path) {
            return;
        }

        Storage::disk('public')->delete($user->avatar_path);
        $user->forceFill(['avatar_path' => null])->save();
    }
}
