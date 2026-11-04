<?php

namespace App\Http\Controllers;

use App\Models\EquipmentCategory;
use App\Models\MunicipalArea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function equipmentCategories(): View
    {
        $filters = [
            'search' => request('search'),
        ];

        $categoriesQuery = EquipmentCategory::query()->orderBy('name');

        if ($filters['search']) {
            $search = trim((string) $filters['search']);

            $categoriesQuery->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        return view('catalogs.equipment-categories.index', [
            'summary' => app(InventoryController::class)->summary(),
            'filters' => $filters,
            'categories' => $categoriesQuery->paginate(20)->withQueryString(),
            'editingCategory' => request('edit')
                ? EquipmentCategory::query()->find(request('edit'))
                : null,
        ]);
    }

    public function storeEquipmentCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:equipment_categories,name'],
            'slug' => ['nullable', 'string', 'max:120', 'unique:equipment_categories,slug'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        EquipmentCategory::query()->create([
            'name' => $validated['name'],
            'slug' => $this->resolveSlug($validated['slug'] ?? null, $validated['name']),
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        return redirect()
            ->route('catalogs.equipment-categories.index')
            ->with('status', 'Categoria registrada correctamente.');
    }

    public function updateEquipmentCategory(Request $request, EquipmentCategory $equipmentCategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120', Rule::unique('equipment_categories', 'name')->ignore($equipmentCategory->id)],
            'slug' => ['nullable', 'string', 'max:120', Rule::unique('equipment_categories', 'slug')->ignore($equipmentCategory->id)],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $equipmentCategory->update([
            'name' => $validated['name'],
            'slug' => $this->resolveSlug($validated['slug'] ?? null, $validated['name']),
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()
            ->route('catalogs.equipment-categories.index')
            ->with('status', 'Categoria actualizada correctamente.');
    }

    public function destroyEquipmentCategory(EquipmentCategory $equipmentCategory): RedirectResponse
    {
        $equipmentCategory->delete();

        return redirect()
            ->route('catalogs.equipment-categories.index')
            ->with('status', 'Categoria eliminada correctamente.');
    }

    public function municipalAreas(): View
    {
        $filters = [
            'search' => request('search'),
        ];

        $areasQuery = MunicipalArea::query()->orderBy('name');

        if ($filters['search']) {
            $search = trim((string) $filters['search']);

            $areasQuery->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('responsible_name', 'like', "%{$search}%")
                    ->orWhere('institutional_email', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        return view('catalogs.municipal-areas.index', [
            'summary' => app(InventoryController::class)->summary(),
            'filters' => $filters,
            'areas' => $areasQuery->paginate(20)->withQueryString(),
            'editingArea' => request('edit')
                ? MunicipalArea::query()->find(request('edit'))
                : null,
        ]);
    }

    public function storeMunicipalArea(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:180', 'unique:municipal_areas,name'],
            'slug' => ['nullable', 'string', 'max:180', 'unique:municipal_areas,slug'],
            'responsible_name' => ['nullable', 'string', 'max:180'],
            'institutional_email' => ['nullable', 'email', 'max:180'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        MunicipalArea::query()->create([
            'name' => $validated['name'],
            'slug' => $this->resolveSlug($validated['slug'] ?? null, $validated['name']),
            'responsible_name' => $validated['responsible_name'] ?? null,
            'institutional_email' => $validated['institutional_email'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        return redirect()
            ->route('catalogs.municipal-areas.index')
            ->with('status', 'Area registrada correctamente.');
    }

    public function updateMunicipalArea(Request $request, MunicipalArea $municipalArea): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:180', Rule::unique('municipal_areas', 'name')->ignore($municipalArea->id)],
            'slug' => ['nullable', 'string', 'max:180', Rule::unique('municipal_areas', 'slug')->ignore($municipalArea->id)],
            'responsible_name' => ['nullable', 'string', 'max:180'],
            'institutional_email' => ['nullable', 'email', 'max:180'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $municipalArea->update([
            'name' => $validated['name'],
            'slug' => $this->resolveSlug($validated['slug'] ?? null, $validated['name']),
            'responsible_name' => $validated['responsible_name'] ?? null,
            'institutional_email' => $validated['institutional_email'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()
            ->route('catalogs.municipal-areas.index')
            ->with('status', 'Area actualizada correctamente.');
    }

    public function destroyMunicipalArea(MunicipalArea $municipalArea): RedirectResponse
    {
        $municipalArea->delete();

        return redirect()
            ->route('catalogs.municipal-areas.index')
            ->with('status', 'Area eliminada correctamente.');
    }

    private function resolveSlug(?string $slug, string $name): string
    {
        return Str::slug(filled($slug) ? $slug : $name);
    }
}
