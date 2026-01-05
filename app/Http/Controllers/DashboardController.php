<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateDashboardProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Dashboard/Profile');
    }

    public function update(UpdateDashboardProfileRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->update([
            'name' => $request->input('name'),
        ]);

        $this->handleAvatarUpload($user, $request->file('avatar'));

        return redirect()->route('dashboard.index');
    }

    private function handleAvatarUpload(\App\Models\User $user, ?\Illuminate\Http\UploadedFile $file): void
    {
        if (! $file) {
            return;
        }

        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $extension = $file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg';
        $filename = Str::uuid().'.'.$extension;
        $path = $file->storeAs('avatars/'.$user->id, $filename, 'public');

        $user->forceFill(['avatar_path' => $path])->save();
    }
}
