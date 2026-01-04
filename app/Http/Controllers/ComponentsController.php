<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\ComponentCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
}
