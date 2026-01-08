<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLayoutRequest;
use App\Models\Layout;
use App\Models\LayoutCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class LibraryController extends Controller
{
    public function index(Request $request, ?string $category = null): Response|RedirectResponse
    {
        $organizationId = $request->user()?->currentOrganizationId();

        $categories = LayoutCategory::query()
            ->where('organization_id', $organizationId)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        if ($categories->isEmpty()) {
            $emptyPaginator = new LengthAwarePaginator([], 0, 30, 1, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);

            return Inertia::render('Library/Index', [
                'categories' => [],
                'category' => null,
                'layouts' => $emptyPaginator,
            ]);
        }

        $showAll = $category === null || $category === 'all';
        $selected = $showAll ? null : $categories->firstWhere('slug', $category);

        if (! $showAll && ! $selected) {
            return redirect()->route('library.index');
        }

        $layoutsQuery = Layout::query()
            ->where('organization_id', $organizationId);

        if ($selected) {
            $layoutsQuery->where('layout_category_id', $selected->id);
        }

        $layouts = $layoutsQuery
            ->orderBy('position')
            ->paginate(30, ['id', 'name', 'slug', 'image_url', 'position'])
            ->withQueryString();

        return Inertia::render('Library/Index', [
            'categories' => $categories,
            'category' => $selected,
            'layouts' => $layouts,
        ]);
    }

    public function store(StoreLayoutRequest $request): RedirectResponse
    {
        $organizationId = $request->user()->currentOrganizationId();

        DB::transaction(function () use ($request, $organizationId): void {
            $category = LayoutCategory::query()
                ->where('organization_id', $organizationId)
                ->where('id', $request->integer('layout_category_id'))
                ->lockForUpdate()
                ->firstOrFail();

            $baseSlug = Str::slug($request->input('name'));
            if ($baseSlug === '') {
                $baseSlug = 'layout';
            }

            $slug = $this->generateUniqueSlug($organizationId, $baseSlug);

            $position = (int) (Layout::query()
                ->where('organization_id', $organizationId)
                ->where('layout_category_id', $category->id)
                ->lockForUpdate()
                ->orderByDesc('position')
                ->value('position') ?? 0);
            $position++;

            $screenshot = $request->file('screenshot');
            $extension = $screenshot->getClientOriginalExtension() ?: $screenshot->extension() ?: 'jpg';
            $filename = sprintf('%s-%s.%s', $slug, now()->format('YmdHis'), $extension);
            $path = $screenshot->storeAs('layout-previews/'.$organizationId, $filename, 'public');

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

            Layout::query()->create([
                'organization_id' => $organizationId,
                'layout_category_id' => $category->id,
                'name' => $request->input('name'),
                'slug' => $slug,
                'image_url' => $path,
                'payload' => $payload,
                'position' => $position,
            ]);
        });

        $category = LayoutCategory::query()
            ->where('organization_id', $organizationId)
            ->where('id', $request->integer('layout_category_id'))
            ->firstOrFail();

        return redirect()->route('library.index', ['category' => $category->slug]);
    }

    public function payload(Request $request, Layout $layout)
    {
        if ($layout->organization_id !== $request->user()->currentOrganizationId()) {
            abort(404);
        }

        return response()->json([
            'payload' => $layout->payload,
        ]);
    }

    public function destroy(Request $request, Layout $layout): RedirectResponse
    {
        Gate::authorize('library.manage');

        if ($layout->organization_id !== $request->user()->currentOrganizationId()) {
            abort(404);
        }

        $layout->load('category');

        $path = $layout->getRawOriginal('image_url');
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

        $layout->delete();

        return redirect()->route('library.index', [
            'category' => $layout->category?->slug,
            'page' => $request->query('page'),
        ]);
    }

    private function generateUniqueSlug(int $organizationId, string $baseSlug): string
    {
        $slug = $baseSlug;
        $attempt = 1;
        $maxAttempts = 20;

        while (Layout::query()
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
