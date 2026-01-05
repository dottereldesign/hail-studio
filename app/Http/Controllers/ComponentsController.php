<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComponentRequest;
use App\Models\Component;
use App\Models\ComponentCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
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
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        if ($categories->isEmpty()) {
            $emptyPaginator = new LengthAwarePaginator([], 0, 30, 1, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);

            return Inertia::render('Components/Index', [
                'categories' => [],
                'category' => null,
                'components' => $emptyPaginator,
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
            ->paginate(30, ['id', 'name', 'slug', 'image_url', 'position'])
            ->withQueryString();

        return Inertia::render('Components/Index', [
            'categories' => $categories,
            'category' => $selected,
            'components' => $components,
        ]);
    }

    public function store(StoreComponentRequest $request): RedirectResponse
    {
        $organizationId = $request->user()->currentOrganizationId();

        DB::transaction(function () use ($request, $organizationId): void {
            $category = ComponentCategory::query()
                ->where('organization_id', $organizationId)
                ->where('id', $request->integer('component_category_id'))
                ->lockForUpdate()
                ->firstOrFail();

            $baseSlug = Str::slug($request->input('name'));
            if ($baseSlug === '') {
                $baseSlug = 'component';
            }

            $slug = $this->generateUniqueSlug($organizationId, $baseSlug);

            $position = (int) Component::query()
                ->where('organization_id', $organizationId)
                ->where('component_category_id', $category->id)
                ->lockForUpdate()
                ->max('position');
            $position++;

            $screenshot = $request->file('screenshot');
            $extension = $screenshot->getClientOriginalExtension() ?: $screenshot->extension() ?: 'jpg';
            $filename = sprintf('%s-%s.%s', $slug, now()->format('YmdHis'), $extension);
            $path = $screenshot->storeAs('component-previews/'.$organizationId, $filename, 'public');

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
                'image_url' => $path,
                'payload' => $payload,
                'position' => $position,
            ]);
        });

        $category = ComponentCategory::query()
            ->where('organization_id', $organizationId)
            ->where('id', $request->integer('component_category_id'))
            ->firstOrFail();

        return redirect()->route('components.index', ['category' => $category->slug]);
    }

    public function payload(Request $request, Component $component)
    {
        if ($component->organization_id !== $request->user()->currentOrganizationId()) {
            abort(404);
        }

        return response()->json([
            'payload' => $component->payload,
        ]);
    }

    public function destroy(Request $request, Component $component): RedirectResponse
    {
        Gate::authorize('components.manage');

        if ($component->organization_id !== $request->user()->currentOrganizationId()) {
            abort(404);
        }

        $component->load('category');

        $path = $component->getRawOriginal('image_url');
        if ($path) {
            if (filter_var($path, FILTER_VALIDATE_URL)) {
                $path = parse_url($path, PHP_URL_PATH) ?: '';
            }

            if (Str::startsWith($path, '/storage/')) {
                $path = Str::after($path, '/storage/');
            }

            $path = ltrim($path, '/');

            if ($path !== '') {
                Storage::disk('public')->delete($path);
            }
        }

        $component->delete();

        return redirect()->route('components.index', [
            'category' => $component->category?->slug,
            'page' => $request->query('page'),
        ]);
    }

    private function generateUniqueSlug(int $organizationId, string $baseSlug): string
    {
        $slug = $baseSlug;
        $attempt = 1;
        $maxAttempts = 20;

        while (Component::query()
            ->where('organization_id', $organizationId)
            ->where('slug', $slug)
            ->exists()) {
            $attempt++;
            if ($attempt > $maxAttempts) {
                $slug = $baseSlug.'-'.Str::lower(Str::random(6));
                $attempt = 1;
                continue;
            }

            $slug = $baseSlug.'-'.$attempt;
        }

        return $slug;
    }
}
