<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\EquipmentMovement;
use App\Models\Location;
use App\Models\MunicipalArea;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EquipmentController extends Controller
{
    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->string('search')->toString(),
            'category' => $request->string('category')->toString(),
            'status' => $request->string('status')->toString(),
        ];

        $query = Equipment::query()
            ->with(['location', 'assignedUser', 'assignedArea']);

        if ($filters['search']) {
            $search = trim($filters['search']);

            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('asset_code', 'like', "%{$search}%");
            });
        }

        if ($filters['category']) {
            $query->where('category', $filters['category']);
        }

        if ($filters['status']) {
            $query->where('status', $filters['status']);
        }

        return view('equipments.index', [
            'summary' => app(InventoryController::class)->summary(),
            'filters' => $filters,
            'equipments' => $query->orderByDesc('id')->paginate(20)->withQueryString(),
            'editingEquipment' => $request->query('edit')
                ? Equipment::query()->find($request->query('edit'))
                : null,
            'categories' => $this->categories(),
            'statuses' => $this->statuses(),
        ]);
    }

    public function assignments(Request $request): View
    {
        $filters = [
            'search' => $request->string('search')->toString(),
            'status' => $request->string('status')->toString(),
        ];

        $query = Equipment::query()
            ->with(['location', 'assignedUser', 'assignedArea']);

        if ($filters['search']) {
            $search = trim($filters['search']);

            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('asset_code', 'like', "%{$search}%")
                    ->orWhereHas('assignedArea', fn ($areaQuery) => $areaQuery->where('name', 'like', "%{$search}%"));
            });
        }

        if ($filters['status']) {
            $query->where('status', $filters['status']);
        }

        return view('equipments.assignments', [
            'summary' => app(InventoryController::class)->summary(),
            'filters' => $filters,
            'equipments' => $query->orderByDesc('id')->paginate(20)->withQueryString(),
            'editingEquipment' => $request->query('edit')
                ? Equipment::query()->find($request->query('edit'))
                : null,
            'users' => User::query()->where('is_active', true)->orderBy('name')->get(),
            'locations' => Location::query()->orderBy('name')->get(),
            'areas' => MunicipalArea::query()->where('is_active', true)->orderBy('name')->get(),
            'categories' => $this->categories(),
            'statuses' => $this->assignmentStatuses(),
        ]);
    }

    public function movements(Request $request): View
    {
        $filters = [
            'search' => $request->string('search')->toString(),
            'type' => $request->string('type')->toString(),
        ];

        $movementsQuery = $this->equipmentMovementsQuery($filters);

        return view('equipments.movements', [
            'summary' => app(InventoryController::class)->summary(),
            'filters' => $filters,
            'movements' => $movementsQuery->orderByDesc('id')->paginate(20)->withQueryString(),
            'users' => User::query()->where('is_active', true)->orderBy('name')->get(),
            'equipments' => Equipment::query()
                ->with(['location', 'assignedArea'])
                ->where('status', '!=', 'retired')
                ->orderByDesc('id')
                ->get(),
            'locations' => Location::query()->orderBy('name')->get(),
            'areas' => MunicipalArea::query()->where('is_active', true)->orderBy('name')->get(),
            'movementTypes' => $this->movementTypes(),
        ]);
    }

    public function exportMovementsPdf(Request $request)
    {
        $filters = [
            'search' => $request->string('search')->toString(),
            'type' => $request->string('type')->toString(),
        ];

        $movements = $this->equipmentMovementsQuery($filters)
            ->orderByDesc('id')
            ->get();

        $visibleMovements = $movements->take(5);

        $pdf = Pdf::loadView('equipments.exports.movements-pdf', [
            'movements' => $visibleMovements,
            'movementTypes' => $this->movementTypes(),
            'generatedAt' => now()->timezone(config('app.timezone'))->format('d/m/Y H:i'),
            'logoPath' => public_path('acta-logo.png'),
            'letterheadPath' => public_path('membrete.png'),
            'totalMovements' => $movements->count(),
            'omittedMovements' => max($movements->count() - $visibleMovements->count(), 0),
        ])->setPaper('letter', 'portrait');

        return $pdf->download('reporte_movimientos_equipos.pdf');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateEquipment($request);
        $validated['name'] = $this->buildEquipmentName(
            $validated['category'],
            $validated['brand'] ?? null,
            $validated['model'] ?? null
        );

        Equipment::query()->create($validated);

        return redirect()
            ->route('equipments.index')
            ->with('status', 'Equipo registrado correctamente.');
    }

    public function update(Request $request, Equipment $equipment): RedirectResponse
    {
        $validated = $this->validateEquipment($request, $equipment);
        $validated['name'] = $this->buildEquipmentName(
            $validated['category'],
            $validated['brand'] ?? null,
            $validated['model'] ?? null
        );

        $equipment->update($validated);

        return redirect()
            ->route('equipments.index')
            ->with('status', 'Equipo actualizado correctamente.');
    }

    public function destroy(Equipment $equipment): RedirectResponse
    {
        $equipment->delete();

        return redirect()
            ->route('equipments.index')
            ->with('status', 'Equipo eliminado correctamente.');
    }

    public function updateAssignment(Request $request, Equipment $equipment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys($this->assignmentStatuses()))],
            'location_id' => ['nullable', 'integer', Rule::exists('locations', 'id')],
            'assigned_user_id' => ['nullable', 'integer', Rule::exists('users', 'id')],
            'assigned_area_id' => ['nullable', 'integer', Rule::exists('municipal_areas', 'id')],
            'assigned_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $hasAssignment = filled($validated['assigned_user_id'] ?? null) || filled($validated['assigned_area_id'] ?? null);

        if ($validated['status'] === 'available') {
            $validated['assigned_user_id'] = null;
            $validated['assigned_area_id'] = null;
            $validated['assigned_area'] = null;
            $validated['assigned_at'] = null;
        }

        if ($validated['status'] === 'assigned' && ! $hasAssignment) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'assigned_user_id' => 'Cuando el estado es Asignado debes indicar un responsable, un area, o ambos.',
            ]);
        }

        if (! empty($validated['assigned_area_id'])) {
            $area = MunicipalArea::query()->find($validated['assigned_area_id']);
            $validated['assigned_area'] = $area?->name;
        } else {
            $validated['assigned_area'] = null;
        }

        $equipment->update($validated);

        return redirect()
            ->route('equipments.assignments')
            ->with('status', 'Asignacion actualizada correctamente.');
    }

    public function storeMovement(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'equipment_id' => ['required', 'integer', Rule::exists('equipments', 'id')],
            'type' => ['required', Rule::in(array_keys($this->movementTypes()))],
            'performed_by' => ['nullable', 'integer', Rule::exists('users', 'id')],
            'from_area_id' => ['nullable', 'integer', Rule::exists('municipal_areas', 'id')],
            'to_location_id' => ['nullable', 'integer', Rule::exists('locations', 'id')],
            'to_area_id' => ['nullable', 'integer', Rule::exists('municipal_areas', 'id')],
            'performed_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $equipment = Equipment::query()
            ->with(['location', 'assignedArea', 'assignedUser'])
            ->findOrFail($validated['equipment_id']);

        if ($validated['type'] === EquipmentMovement::TYPE_TRANSFER && ! filled($validated['to_location_id']) && ! filled($validated['to_area_id'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'to_location_id' => 'El traslado requiere una ubicacion, un area de destino, o ambas.',
            ]);
        }

        if ($validated['type'] === EquipmentMovement::TYPE_RETIREMENT && ! filled($validated['from_area_id'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'from_area_id' => 'La baja requiere indicar el area desde donde se retira el equipo.',
            ]);
        }

        DB::transaction(function () use ($validated, $equipment, $request) {
            $movement = EquipmentMovement::query()->create([
                'equipment_id' => $equipment->id,
                'type' => $validated['type'],
                'performed_by' => $request->user()?->isAdmin()
                    ? ($validated['performed_by'] ?? $request->user()?->id)
                    : $request->user()?->id,
                'from_location_id' => $equipment->location_id,
                'to_location_id' => $validated['type'] === EquipmentMovement::TYPE_TRANSFER ? ($validated['to_location_id'] ?? null) : null,
                'from_area_id' => $validated['type'] === EquipmentMovement::TYPE_RETIREMENT
                    ? ($validated['from_area_id'] ?? $equipment->assigned_area_id)
                    : $equipment->assigned_area_id,
                'to_area_id' => $validated['type'] === EquipmentMovement::TYPE_TRANSFER ? ($validated['to_area_id'] ?? null) : null,
                'assigned_user_id' => $equipment->assigned_user_id,
                'assigned_user_name' => $equipment->assignedUser?->name,
                'assigned_area_name' => $equipment->assignedArea?->name ?: $equipment->assigned_area,
                'notes' => $validated['notes'] ?? null,
                'performed_at' => $validated['performed_at'],
            ]);

            if ($movement->type === EquipmentMovement::TYPE_TRANSFER) {
                $destinationArea = filled($validated['to_area_id'])
                    ? MunicipalArea::query()->find($validated['to_area_id'])
                    : null;

                $equipment->update([
                    'location_id' => $validated['to_location_id'] ?? $equipment->location_id,
                    'assigned_area_id' => $destinationArea?->id,
                    'assigned_area' => $destinationArea?->name,
                    'assigned_user_id' => null,
                    'assigned_at' => $validated['performed_at'],
                    'status' => 'assigned',
                    'notes' => $validated['notes'] ?? $equipment->notes,
                ]);
            }

            if ($movement->type === EquipmentMovement::TYPE_WITHDRAWAL) {
                $equipment->update([
                    'status' => 'maintenance',
                    'assigned_user_id' => null,
                    'assigned_area_id' => null,
                    'assigned_area' => null,
                    'assigned_at' => null,
                    'notes' => $validated['notes'] ?? $equipment->notes,
                ]);
            }

            if ($movement->type === EquipmentMovement::TYPE_RETIREMENT) {
                $equipment->update([
                    'status' => 'retired',
                    'location_id' => null,
                    'assigned_user_id' => null,
                    'assigned_area_id' => null,
                    'assigned_area' => null,
                    'assigned_at' => null,
                    'notes' => $validated['notes'] ?? $equipment->notes,
                ]);
            }
        });

        return redirect()
            ->route('equipments.movements')
            ->with('status', 'Movimiento de equipo registrado correctamente.');
    }

    private function validateEquipment(Request $request, ?Equipment $equipment = null): array
    {
        $validated = $request->validate([
            'category' => ['required', Rule::in(array_keys($this->categories()))],
            'brand' => ['nullable', 'string', 'max:120'],
            'model' => ['nullable', 'string', 'max:120'],
            'serial_number' => ['nullable', 'string', 'max:150', Rule::unique('equipments', 'serial_number')->ignore($equipment?->id)],
            'asset_code' => ['nullable', 'string', 'max:120', Rule::unique('equipments', 'asset_code')->ignore($equipment?->id)],
            'processor' => ['nullable', 'string', 'max:255'],
            'ram' => ['nullable', 'string', 'max:80'],
            'storage' => ['nullable', 'string', 'max:80'],
            'operating_system' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['available', 'maintenance', 'inactive', 'retired'])],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        return $validated;
    }

    private function categories(): array
    {
        $categories = EquipmentCategory::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'slug')
            ->all();

        return $categories ?: [
            'otro' => 'Otro',
        ];
    }

    private function statuses(): array
    {
        return [
            'available' => 'Disponible',
            'maintenance' => 'Mantenimiento',
            'inactive' => 'Inactivo',
            'retired' => 'De baja',
        ];
    }

    private function assignmentStatuses(): array
    {
        return [
            'available' => 'Disponible',
            'assigned' => 'Asignado',
            'maintenance' => 'Mantenimiento',
            'inactive' => 'Inactivo',
            'retired' => 'De baja',
        ];
    }

    private function movementTypes(): array
    {
        return [
            EquipmentMovement::TYPE_WITHDRAWAL => 'Retiro',
            EquipmentMovement::TYPE_TRANSFER => 'Traslado',
            EquipmentMovement::TYPE_RETIREMENT => 'Baja',
        ];
    }

    private function equipmentMovementsQuery(array $filters)
    {
        $movementsQuery = EquipmentMovement::query()
            ->with(['equipment', 'performer', 'assignedUser', 'fromLocation', 'toLocation', 'fromArea', 'toArea']);

        if (! empty($filters['search'])) {
            $search = trim((string) $filters['search']);

            $movementsQuery->where(function ($builder) use ($search) {
                $builder
                    ->whereHas('equipment', function ($equipmentQuery) use ($search) {
                        $equipmentQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('brand', 'like', "%{$search}%")
                            ->orWhere('model', 'like', "%{$search}%")
                            ->orWhere('serial_number', 'like', "%{$search}%")
                            ->orWhere('asset_code', 'like', "%{$search}%");
                    })
                    ->orWhereHas('performer', fn ($userQuery) => $userQuery->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('assignedUser', fn ($userQuery) => $userQuery->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('fromArea', fn ($areaQuery) => $areaQuery->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('toArea', fn ($areaQuery) => $areaQuery->where('name', 'like', "%{$search}%"))
                    ->orWhere('assigned_user_name', 'like', "%{$search}%")
                    ->orWhere('assigned_area_name', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['type'])) {
            $movementsQuery->where('type', $filters['type']);
        }

        return $movementsQuery;
    }

    private function buildEquipmentName(string $category, ?string $brand, ?string $model): string
    {
        $parts = [
            $this->categories()[$category] ?? ucfirst($category),
            $brand,
            $model,
        ];

        return trim(collect($parts)
            ->filter(fn ($value) => filled($value))
            ->implode(' '));
    }
}
