<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComponentRequest;
use App\Models\Component;
use App\Models\ComponentCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class ComponentsController extends Controller
{
    public function index(Request $request, ?string $category = null): Response|RedirectResponse
    {
        $organizationId = $request->user()?->currentOrganizationId();

        $categories = ComponentCategory::query()
            ->where('organization_id', $organizationId)
            ->orderBy('position')
            ->get(['id', 'name', 'slug']);

        if ($categories->isEmpty()) {
            return Inertia::render('Components/Index', [
                'categories' => [],
                'category' => null,
                'components' => [],
            ]);
        }

        $selected = $category
            ? $categories->firstWhere('slug', $category)
            : $categories->first();

        if (! $selected) {
            return redirect()->route('components.index', ['category' => $categories->first()->slug]);
        }

        $components = Component::query()
            ->where('organization_id', $organizationId)
            ->where('component_category_id', $selected->id)
            ->orderBy('position')
            ->get(['id', 'name', 'slug', 'image_url', 'payload']);

        return Inertia::render('Components/Index', [
            'categories' => $categories,
            'category' => $selected,
            'components' => $components,
        ]);
    }

    public function store(StoreComponentRequest $request): RedirectResponse
    {
        $organizationId = $request->user()->currentOrganizationId();

        $category = ComponentCategory::query()
            ->where('organization_id', $organizationId)
            ->where('id', $request->integer('component_category_id'))
            ->firstOrFail();

        $baseSlug = Str::slug($request->input('name'));
        if ($baseSlug === '') {
            $baseSlug = 'component';
        }

        $slug = $baseSlug;
        $suffix = 2;
        while (Component::query()
            ->where('organization_id', $organizationId)
            ->where('slug', $slug)
            ->exists()) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        $position = (int) Component::query()
            ->where('organization_id', $organizationId)
            ->where('component_category_id', $category->id)
            ->max('position');
        $position++;

        $screenshot = $request->file('screenshot');
        $extension = $screenshot->getClientOriginalExtension() ?: $screenshot->extension() ?: 'jpg';
        $filename = sprintf('%s-%s.%s', $slug, now()->format('YmdHis'), $extension);
        $path = $screenshot->storeAs('component-previews/'.$organizationId, $filename, 'public');
        $imageUrl = Storage::url($path);

        try {
            $payload = json_decode($request->input('payload'), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw ValidationException::withMessages([
                'payload' => 'Payload must be valid JSON.',
            ]);
        }

        if (! is_array($payload)) {
            throw ValidationException::withMessages([
                'payload' => 'Payload must be a JSON object or array.',
            ]);
        }

        Component::query()->create([
            'organization_id' => $organizationId,
            'component_category_id' => $category->id,
            'name' => $request->input('name'),
            'slug' => $slug,
            'image_url' => $imageUrl,
            'payload' => $payload,
            'position' => $position,
        ]);

        return redirect()->route('components.index', ['category' => $category->slug]);
    }
}
