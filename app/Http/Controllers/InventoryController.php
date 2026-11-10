<?php

namespace App\Http\Controllers;

use App\Models\DeliveryRecord;
use App\Models\DeliveryRecordLine;
use App\Models\InventoryMovement;
use App\Models\Item;
use App\Models\Location;
use App\Models\Equipment;
use App\Models\MunicipalArea;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function dashboard(): View
    {
        return view('inventory.dashboard', [
            'summary' => $this->summary(),
            'recentMovements' => InventoryMovement::query()
                ->with(['item', 'fromLocation', 'toLocation'])
                ->orderByDesc('id')
                ->limit(5)
                ->get(),
            'lowStockItems' => $this->itemsWithLocationStockQuery()
                //->havingRaw('(bodega_stock + oficina_stock) <= 3')
                //->orderByRaw('(bodega_stock + oficina_stock) asc')
                ->limit(6)
                ->get(),
            'movementTypes' => $this->movementTypes(),
        ]);
    }

    public function items(): View
    {
        $filters = [
            'search' => request('search'),
        ];

        $itemsQuery = $this->itemsWithLocationStockQuery();

        if ($filters['search']) {
            $search = trim((string) $filters['search']);

            $itemsQuery->where(function ($query) use ($search) {
                $query
                    ->where('items.name', 'like', "%{$search}%")
                    ->orWhere('items.brand', 'like', "%{$search}%")
                    ->orWhere('items.model', 'like', "%{$search}%");
            });
        }

        return view('inventory.items.index', [
            'summary' => $this->summary(),
            'filters' => $filters,
            'items' => $itemsQuery
                ->orderByDesc('items.id')
                ->paginate(20)
                ->withQueryString(),
        ]);
    }

    public function stockLookup(): View
    {
        return view('inventory.stock-lookup.index', [
            'summary' => $this->summary(),
            'items' => $this->itemsWithLocationStockQuery()
                ->orderBy('items.name')
                ->get(),
        ]);
    }

    public function movements(): View
    {
        $filters = [
            'item_id' => request('item_id'),
            'type' => request('type'),
            'location_id' => request('location_id'),
            'date_from' => request('date_from'),
            'date_to' => request('date_to'),
        ];

        $items = $this->itemsWithLocationStockQuery()
            ->orderByDesc('items.id')
            ->get();

        $locations = Location::query()->orderBy('name')->get();

        $movementsQuery = InventoryMovement::query()
            ->with(['item', 'fromLocation', 'toLocation']);

        if ($filters['item_id']) {
            $movementsQuery->where('item_id', $filters['item_id']);
        }

        if ($filters['type']) {
            $movementsQuery->where('type', $filters['type']);
        }

        if ($filters['location_id']) {
            $movementsQuery->where(function ($query) use ($filters) {
                $query
                    ->where('from_location_id', $filters['location_id'])
                    ->orWhere('to_location_id', $filters['location_id']);
            });
        }

        if ($filters['date_from']) {
            $movementsQuery->whereDate('performed_at', '>=', $filters['date_from']);
        }

        if ($filters['date_to']) {
            $movementsQuery->whereDate('performed_at', '<=', $filters['date_to']);
        }

        return view('inventory.movements.index', [
            'summary' => $this->summary(),
            'items' => $items,
            'locations' => $locations,
            'filters' => $filters,
            'movements' => $movementsQuery
                ->orderByDesc('id')
                ->paginate(20)
                ->withQueryString(),
            'movementTypes' => $this->movementTypes(),
        ]);
    }

    public function deliveryActForm(): View
    {
        $items = $this->itemsWithLocationStockQuery()
            ->orderBy('items.name')
            ->get();

        $prefilledItemId = request()->integer('item_id');
        $prefilledItem = $prefilledItemId > 0
            ? $items->firstWhere('id', $prefilledItemId)
            : null;

        $defaultRows = old('rows');

        if (! $defaultRows) {
            $defaultRows = $prefilledItem
                ? [
                    ['quantity' => '1', 'item_id' => $prefilledItem->id],
                    ['quantity' => '', 'item_id' => ''],
                    ['quantity' => '', 'item_id' => ''],
                    ['quantity' => '', 'item_id' => ''],
                ]
                : [
                    ['quantity' => '', 'item_id' => ''],
                    ['quantity' => '', 'item_id' => ''],
                    ['quantity' => '', 'item_id' => ''],
                    ['quantity' => '', 'item_id' => ''],
                ];
        }

        return view('inventory.delivery-act.form', [
            'summary' => $this->summary(),
            'items' => $items,
            'areas' => MunicipalArea::query()->where('is_active', true)->orderBy('name')->get(),
            'locations' => Location::query()->orderBy('name')->get(),
            'prefilledItem' => $prefilledItem,
            'defaultRows' => $defaultRows,
        ]);
    }

    public function equipmentDeliveryActForm(): View
    {
        return view('inventory.delivery-act.equipments-form', [
            'summary' => $this->summary(),
            'equipments' => Equipment::query()
                ->with(['assignedArea', 'location'])
                ->where('status', '!=', 'retired')
                ->orderBy('name')
                ->get(),
            'areas' => MunicipalArea::query()->where('is_active', true)->orderBy('name')->get(),
            'locations' => Location::query()->orderBy('name')->get(),
            'defaultRows' => old('rows', [
                ['equipment_id' => ''],
                ['equipment_id' => ''],
                ['equipment_id' => ''],
                ['equipment_id' => ''],
            ]),
        ]);
    }

    public function deliveryActHistory(): View
    {
        $filters = [
            'type' => request('type'),
            'search' => request('search'),
        ];

        $recordsQuery = DeliveryRecord::query()
            ->with(['lines', 'creator', 'annulledBy'])
            ->orderByDesc('id');

        if ($filters['type']) {
            $recordsQuery->where('type', $filters['type']);
        }

        if ($filters['search']) {
            $search = trim((string) $filters['search']);

            $recordsQuery->where(function ($query) use ($search) {
                $query
                    ->where('district', 'like', "%{$search}%")
                    ->orWhere('area_name', 'like', "%{$search}%")
                    ->orWhere('responsible_name', 'like', "%{$search}%")
                    ->orWhere('receives', 'like', "%{$search}%");
            });
        }

        return view('inventory.delivery-act.history', [
            'summary' => $this->summary(),
            'filters' => $filters,
            'records' => $recordsQuery->paginate(15)->withQueryString(),
            'recordTypes' => $this->deliveryRecordTypes(),
        ]);
    }

    public function editDeliveryRecord(DeliveryRecord $record): View
    {
        $record->load(['lines', 'assignedArea', 'fromLocation', 'location']);

        if ($record->type === DeliveryRecord::TYPE_SUPPLIES) {
            $items = $this->itemsWithLocationStockQuery()
                ->orderBy('items.name')
                ->get();

            $rows = $record->lines->map(fn (DeliveryRecordLine $line) => [
                'quantity' => (string) ($line->quantity ?? ''),
                'item_id' => $line->item_id ?: '',
            ])->values()->all();

            while (count($rows) < 4) {
                $rows[] = ['quantity' => '', 'item_id' => ''];
            }

            return view('inventory.delivery-act.edit', [
                'summary' => $this->summary(),
                'record' => $record,
                'recordTypes' => $this->deliveryRecordTypes(),
                'items' => $items,
                'areas' => MunicipalArea::query()->where('is_active', true)->orderBy('name')->get(),
                'locations' => Location::query()->orderBy('name')->get(),
                'defaultRows' => old('rows', $rows),
            ]);
        }

        $selectedEquipmentIds = $record->lines->pluck('equipment_id')->filter()->all();
        $equipments = Equipment::query()
            ->with(['assignedArea', 'location'])
            ->where(function ($query) use ($selectedEquipmentIds) {
                $query->where('status', '!=', 'retired');

                if (! empty($selectedEquipmentIds)) {
                    $query->orWhereIn('id', $selectedEquipmentIds);
                }
            })
            ->orderBy('name')
            ->get();

        $rows = $record->lines->map(fn (DeliveryRecordLine $line) => [
            'equipment_id' => $line->equipment_id ?: '',
        ])->values()->all();

        while (count($rows) < 4) {
            $rows[] = ['equipment_id' => ''];
        }

        return view('inventory.delivery-act.edit', [
            'summary' => $this->summary(),
            'record' => $record,
            'recordTypes' => $this->deliveryRecordTypes(),
            'equipments' => $equipments,
            'areas' => MunicipalArea::query()->where('is_active', true)->orderBy('name')->get(),
            'locations' => Location::query()->orderBy('name')->get(),
            'defaultRows' => old('rows', $rows),
        ]);
    }

    public function updateDeliveryRecord(Request $request, DeliveryRecord $record): RedirectResponse
    {
        if ($record->annulled_at) {
            return back()->withErrors(['record' => 'No se puede corregir una acta anulada.']);
        }

        return $record->type === DeliveryRecord::TYPE_SUPPLIES
            ? $this->updateSuppliesDeliveryRecord($request, $record)
            : $this->updateEquipmentDeliveryRecord($request, $record);
    }

    public function annulDeliveryRecord(Request $request, DeliveryRecord $record): RedirectResponse
    {
        if ($record->annulled_at) {
            return back()->withErrors(['record' => 'Esta acta ya fue anulada.']);
        }

        if ($record->type !== DeliveryRecord::TYPE_SUPPLIES) {
            return back()->withErrors(['record' => 'Solo se pueden anular actas de insumos.']);
        }

        $record->loadMissing('lines');

        if ($record->lines->isEmpty()) {
            return back()->withErrors(['record' => 'No hay detalle para revertir en esta acta.']);
        }

        DB::transaction(function () use ($record, $request) {
            $performedAt = now()->timezone(config('app.timezone'))->format('Y-m-d H:i:s');
            $notes = 'Anulacion de acta de entrega a '.$record->receives.' - '.$record->area_name;

            foreach ($record->lines as $line) {
                if (! $line->item_id || ! $record->from_location_id) {
                    continue;
                }

                InventoryMovement::query()->create([
                    'item_id' => $line->item_id,
                    'delivery_record_id' => $record->id,
                    'type' => InventoryMovement::TYPE_ENTRY,
                    'from_location_id' => null,
                    'to_location_id' => $record->from_location_id,
                    'quantity' => (int) ($line->quantity ?: 0),
                    'notes' => $notes,
                    'performed_at' => $performedAt,
                ]);
            }

            $record->update([
                'annulled_at' => now(),
                'annulled_by' => $request->user()?->id,
            ]);
        });

        return redirect()
            ->route('inventory.delivery-act.history')
            ->with('status', 'Acta anulada correctamente y stock devuelto.');
    }

    public function generateDeliveryActPdf(Request $request): Response|RedirectResponse
    {
        $validated = $request->validate([
            'delivery_date' => ['required', 'date'],
            'district' => ['required', 'string', 'max:120'],
            'assigned_area_id' => ['required', 'integer', Rule::exists('municipal_areas', 'id')],
            'receives' => ['required', 'string', 'max:255'],
            'from_location_id' => ['required', 'integer', Rule::exists('locations', 'id')],
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.quantity' => ['nullable', 'integer', 'min:1'],
            'rows.*.item_id' => ['nullable', 'integer', Rule::exists('items', 'id')],
        ]);

        $rows = collect($validated['rows'])
            ->map(fn (array $row) => [
                'quantity' => (int) ($row['quantity'] ?? 0),
                'item_id' => $row['item_id'] ?? null,
            ])
            ->filter(fn (array $row) => $row['quantity'] > 0 || $row['item_id'])
            ->values();

        if ($rows->isEmpty()) {
            return back()
                ->withInput()
                ->withErrors(['rows' => 'Agrega al menos un insumo en el acta.']);
        }

        if ($rows->contains(fn (array $row) => $row['quantity'] <= 0 || !$row['item_id'])) {
            return back()
                ->withInput()
                ->withErrors(['rows' => 'Cada fila del acta debe llevar cantidad e insumo del inventario.']);
        }

        $itemIds = $rows->pluck('item_id')->all();
        $items = Item::query()->whereIn('id', $itemIds)->get()->keyBy('id');
        $fromLocation = Location::query()->findOrFail($validated['from_location_id']);
        $assignedArea = MunicipalArea::query()->findOrFail($validated['assigned_area_id']);
        $responsible = $assignedArea->responsible_name ?: 'Sin responsable asignado';
        $areaName = $assignedArea->name;

        $groupedQuantities = $rows->groupBy('item_id')->map(fn ($group) => $group->sum('quantity'));

        foreach ($groupedQuantities as $itemId => $quantity) {
            $availableStock = $this->stockForItemAtLocation((int) $itemId, $fromLocation->id);

            if ($quantity > $availableStock) {
                $item = $items->get((int) $itemId);
                $name = trim(($item?->name ?? '').' '.($item?->brand ?? '').' '.($item?->model ?? ''));

                return back()
                    ->withInput()
                    ->withErrors([
                        'rows' => "La salida solicitada para {$name} supera el stock disponible en {$fromLocation->name} ({$availableStock} unidades).",
                    ]);
            }
        }

        $pdfRows = $rows->map(function (array $row) use ($items) {
            $item = $items->get((int) $row['item_id']);

            return [
                'quantity' => $row['quantity'],
                'item' => trim(($item?->name ?? '').' '.($item?->brand ?? '').' '.($item?->model ?? '')),
                'item_id' => $row['item_id'],
            ];
        });

        DB::transaction(function () use ($validated, $rows, $fromLocation, $assignedArea, $areaName, $responsible, $pdfRows, $request) {
            $performedAt = $validated['delivery_date'].' '.now()->timezone(config('app.timezone'))->format('H:i:s');

            $record = DeliveryRecord::query()->create([
                'type' => DeliveryRecord::TYPE_SUPPLIES,
                'delivery_date' => $validated['delivery_date'],
                'district' => $validated['district'],
                'area_name' => $areaName,
                'responsible_name' => $responsible,
                'receives' => $validated['receives'],
                'from_location_name' => $fromLocation->name,
                'from_location_id' => $fromLocation->id,
                'to_location_name' => $areaName,
                'assigned_area_id' => $assignedArea->id,
                'total_lines' => $pdfRows->count(),
                'created_by' => $request->user()?->id,
            ]);

            foreach ($rows as $row) {
                InventoryMovement::query()->create([
                    'item_id' => $row['item_id'],
                    'delivery_record_id' => $record->id,
                    'type' => InventoryMovement::TYPE_EXIT,
                    'from_location_id' => $fromLocation->id,
                    'to_location_id' => null,
                    'quantity' => $row['quantity'],
                    'notes' => 'Acta de entrega a '.$validated['receives'].' - '.$areaName,
                    'performed_at' => $performedAt,
                ]);
            }

            foreach ($pdfRows as $row) {
                DeliveryRecordLine::query()->create([
                    'delivery_record_id' => $record->id,
                    'description' => $row['item'],
                    'item_id' => $row['item_id'],
                    'quantity' => $row['quantity'],
                ]);
            }
        });

        $pdf = Pdf::loadView('inventory.exports.delivery-act-pdf', [
            'deliveryDate' => \Carbon\Carbon::parse($validated['delivery_date'])->timezone(config('app.timezone')),
            'district' => $validated['district'],
            'responsible' => $responsible,
            'area' => $areaName,
            'receives' => $validated['receives'],
            'rows' => $pdfRows,
            'logoPath' => public_path('acta-logo.png'),
            'letterheadPath' => public_path('membrete.png'),
        ]);

        return $pdf->download('acta_entrega_insumos.pdf');
    }

    public function generateEquipmentDeliveryActPdf(Request $request): Response|RedirectResponse
    {
        $validated = $request->validate([
            'delivery_date' => ['required', 'date'],
            'district' => ['required', 'string', 'max:120'],
            'receives' => ['required', 'string', 'max:255'],
            'assigned_area_id' => ['required', 'integer', Rule::exists('municipal_areas', 'id')],
            'location_id' => ['nullable', 'integer', Rule::exists('locations', 'id')],
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.equipment_id' => ['nullable', 'integer', Rule::exists('equipments', 'id')],
        ]);

        $rows = collect($validated['rows'])
            ->map(fn (array $row) => [
                'equipment_id' => $row['equipment_id'] ?? null,
            ])
            ->filter(fn (array $row) => ! empty($row['equipment_id']))
            ->unique('equipment_id')
            ->values();

        if ($rows->isEmpty()) {
            return back()
                ->withInput()
                ->withErrors(['rows' => 'Agrega al menos un equipo en el acta.']);
        }

        $equipmentIds = $rows->pluck('equipment_id')->all();
        $equipments = Equipment::query()
            ->with(['assignedArea', 'location'])
            ->whereIn('id', $equipmentIds)
            ->get()
            ->keyBy('id');

        $assignedArea = MunicipalArea::query()->findOrFail($validated['assigned_area_id']);
        $responsible = $assignedArea->responsible_name ?: 'Sin responsable asignado';
        $areaName = $assignedArea->name;

        DB::transaction(function () use ($validated, $equipments, $assignedArea, $areaName, $responsible, $rows, $request) {
            foreach ($equipments as $equipment) {
                $equipment->update([
                    'status' => 'assigned',
                    'assigned_area_id' => $assignedArea?->id,
                    'assigned_area' => $assignedArea?->name,
                    'location_id' => $validated['location_id'] ?? null,
                    'assigned_at' => $validated['delivery_date'],
                    'notes' => 'Acta de entrega de equipo a '.$validated['receives'].' - '.$areaName,
                ]);
            }

            $record = DeliveryRecord::query()->create([
                'type' => DeliveryRecord::TYPE_EQUIPMENTS,
                'delivery_date' => $validated['delivery_date'],
                'district' => $validated['district'],
                'area_name' => $areaName,
                'responsible_name' => $responsible,
                'receives' => $validated['receives'],
                'from_location_name' => null,
                'to_location_name' => $areaName,
                'assigned_area_id' => $assignedArea->id,
                'location_id' => $validated['location_id'] ?? null,
                'total_lines' => $rows->count(),
                'created_by' => $request->user()?->id,
            ]);

            foreach ($equipments as $equipment) {
                DeliveryRecordLine::query()->create([
                    'delivery_record_id' => $record->id,
                    'description' => $equipment->name,
                    'equipment_id' => $equipment->id,
                    'quantity' => 1,
                    'asset_code' => $equipment->asset_code,
                    'serial_number' => $equipment->serial_number,
                ]);
            }
        });

        $pdfRows = $rows->map(function (array $row) use ($equipments) {
            $equipment = $equipments->get((int) $row['equipment_id']);

            return [
                'equipment' => trim(($equipment?->name ?? '').' '.($equipment?->serial_number ? ' / Serie: '.$equipment->serial_number : '')),
                'asset_code' => $equipment?->asset_code ?: '-',
                'serial_number' => $equipment?->serial_number ?: '-',
            ];
        });

        $pdf = Pdf::loadView('inventory.exports.delivery-act-equipment-pdf', [
            'deliveryDate' => \Carbon\Carbon::parse($validated['delivery_date'])->timezone(config('app.timezone')),
            'district' => $validated['district'],
            'responsible' => $responsible,
            'area' => $areaName,
            'receives' => $validated['receives'],
            'rows' => $pdfRows,
            'logoPath' => public_path('acta-logo.png'),
            'letterheadPath' => public_path('membrete.png'),
        ]);

        return $pdf->download('acta_entrega_equipos.pdf');
    }

    public function exportSummaryPdf(): Response
    {
        $summary = $this->summary();
        $items = $this->itemsWithLocationStockQuery()->orderBy('items.name')->get();
        $movements = InventoryMovement::query()
            ->with(['item', 'fromLocation', 'toLocation', 'performer'])
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        $pdf = Pdf::loadView('inventory.exports.summary-pdf', [
            'summary' => $summary,
            'items' => $items,
            'movements' => $movements,
            'movementTypes' => $this->movementTypes(),
            'generatedAt' => now()->timezone(config('app.timezone'))->format('d/m/Y H:i'),
        ]);

        return $pdf->download('resumen_inventario_innovacion.pdf');
    }

    public function exportSummaryExcel(): Response
    {
        $summary = $this->summary();
        $items = $this->itemsWithLocationStockQuery()->orderBy('items.name')->get();
        $movements = InventoryMovement::query()
            ->with(['item', 'fromLocation', 'toLocation', 'performer'])
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        $lines = [
            ['Resumen de inventario por ubicacion'],
            ['Generado', now()->timezone(config('app.timezone'))->format('d/m/Y H:i')],
            [],
            ['Indicador', 'Valor'],
            ['Stock total', $summary['stock']],
            ['Total de insumos', $summary['items']],
            ['Entradas', $summary['entries']],
            ['Salidas', $summary['exits']],
            ['Transferencias', $summary['transfers']],
            ['Stock en bodega', $summary['bodega_stock']],
            ['Stock en oficina', $summary['oficina_stock']],
            [],
            ['Insumos'],
            ['Nombre', 'Marca', 'Modelo', 'Bodega', 'Oficina', 'Total'],
        ];

        foreach ($items as $item) {
            $lines[] = [$item->name, $item->brand, $item->model, $item->bodega_stock, $item->oficina_stock, $item->total_stock];
        }

        $lines[] = [];
        $lines[] = ['Ultimos movimientos'];
        $lines[] = ['Fecha', 'Insumo', 'Tipo', 'Origen', 'Destino', 'Cantidad', 'Observaciones', 'Registrado por'];

        foreach ($movements as $movement) {
            $lines[] = [
                optional($movement->performed_at)?->timezone(config('app.timezone'))->format('d/m/Y H:i'),
                trim(($movement->item?->name ?? '').' '.($movement->item?->brand ?? '').' '.($movement->item?->model ?? '')),
                $this->movementTypes()[$movement->type] ?? $movement->type,
                $movement->fromLocation?->name ?? '-',
                $movement->toLocation?->name ?? '-',
                $movement->quantity,
                $movement->notes ?? '',
                $movement->performer?->name ?? 'Sistema', 
            ];
        }

        return $this->excelResponse($lines, 'resumen_inventario_innovacion.xls');
    }

    public function exportItemsSummaryPdf(): Response
    {
        $items = $this->itemsWithLocationStockQuery()->orderBy('items.name')->get();

        $pdf = Pdf::loadView('inventory.exports.items-summary-pdf', [
            'items' => $items,
            'summary' => $this->summary(),
            'generatedAt' => now()->timezone(config('app.timezone'))->format('d/m/Y H:i'),
        ]);

        return $pdf->download('resumen_insumos_inventario.pdf');
    }

    public function exportItemsSummaryExcel(): Response
    {
        $items = $this->itemsWithLocationStockQuery()->orderBy('items.name')->get();

        $lines = [
            ['Resumen total por insumo y ubicacion'],
            ['Generado', now()->timezone(config('app.timezone'))->format('d/m/Y H:i')],
            [],
            ['Nombre', 'Marca', 'Modelo', 'Unidad', 'Bodega', 'Oficina', 'Total actual'],
        ];

        foreach ($items as $item) {
            $lines[] = [$item->name, $item->brand, $item->model, $item->unit, $item->bodega_stock, $item->oficina_stock, $item->total_stock];
        }

        $lines[] = [];
        $lines[] = ['Total de insumos', $items->count()];
        $lines[] = ['Stock global', $items->sum('total_stock')];

        return $this->excelResponse($lines, 'resumen_total_por_insumo.xls');
    }

    public function storeItem(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'brand' => ['required', 'string', 'max:120'],
            'model' => ['required', 'string', 'max:120'],
            'unit' => ['required', 'string', 'max:40'],
        ]);

        $duplicateExists = Item::query()
            ->where('name', $validated['name'])
            ->where('brand', $validated['brand'])
            ->where('model', $validated['model'])
            ->exists();

        if ($duplicateExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => 'Este insumo ya existe. Usa movimientos para registrar nuevas entradas o salidas.',
                ]);
        }

        Item::create($validated);

        return redirect()->route('inventory.items.index')->with('status', 'Insumo registrado correctamente.');
    }

    public function updateItem(Request $request, Item $item): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'brand' => ['required', 'string', 'max:120'],
            'model' => ['required', 'string', 'max:120'],
            'unit' => ['required', 'string', 'max:40'],
        ]);

        $duplicateExists = Item::query()
            ->where('name', $validated['name'])
            ->where('brand', $validated['brand'])
            ->where('model', $validated['model'])
            ->where('id', '!=', $item->id)
            ->exists();

        if ($duplicateExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => 'Ya existe otro insumo con la misma combinacion de nombre, marca y modelo.',
                ]);
        }

        $item->update($validated);

        return redirect()
            ->route('inventory.items.index')
            ->with('status', 'Insumo actualizado correctamente.');
    }

    public function destroyItem(Item $item): RedirectResponse
    {
        if ($item->movements()->exists()) {
            return redirect()
                ->route('inventory.items.index')
                ->withErrors([
                    'item' => 'No se puede eliminar el insumo porque ya tiene movimientos registrados.',
                ]);
        }

        $item->delete();

        return redirect()
            ->route('inventory.items.index')
            ->with('status', 'Insumo eliminado correctamente.');
    }

        public function storeMovement(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_id' => ['required', 'integer', Rule::exists('items', 'id')],
            'type' => ['required', Rule::in(array_keys($this->movementTypes()))],
            'from_location_id' => ['nullable', 'integer', Rule::exists('locations', 'id')],
            'to_location_id' => ['nullable', 'integer', Rule::exists('locations', 'id')],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:255'],
            'performed_at' => ['required', 'date'],
        ]);

        $item = Item::query()->findOrFail($validated['item_id']);
        $type = $validated['type'];

        if ($type === InventoryMovement::TYPE_ENTRY) {
            $validated['from_location_id'] = null;
        }

        if ($type === InventoryMovement::TYPE_EXIT) {
            $validated['to_location_id'] = null;
        }

        $fromLocationId = $validated['from_location_id'] ?? null;
        $toLocationId = $validated['to_location_id'] ?? null;

        if ($type === InventoryMovement::TYPE_ENTRY && !$toLocationId) {
            return back()->withInput()->withErrors(['to_location_id' => 'Selecciona la ubicacion de destino para la entrada.']);
        }

        if ($type === InventoryMovement::TYPE_EXIT && !$fromLocationId) {
            return back()->withInput()->withErrors(['from_location_id' => 'Selecciona la ubicacion de origen para la salida.']);
        }

        if ($type === InventoryMovement::TYPE_TRANSFER) {
            if (!$fromLocationId || !$toLocationId) {
                return back()->withInput()->withErrors(['from_location_id' => 'La transferencia requiere origen y destino.']);
            }

            if ((int) $fromLocationId === (int) $toLocationId) {
                return back()->withInput()->withErrors(['to_location_id' => 'La ubicacion de destino debe ser diferente al origen.']);
            }
        }

        // 🛡️ INICIO DE TRANSACCIÓN Y BLOQUEO (RACE CONDITION FIX)
        try {
            DB::transaction(function () use ($validated, $item, $type, $fromLocationId) {
                
                // 1. Bloqueamos la fila del ítem en la BD para evitar concurrencia
                Item::where('id', $item->id)->lockForUpdate()->first();

                // 2. Validamos el stock DENTRO de la transacción (nadie puede modificarlo mientras)
                if (in_array($type, [InventoryMovement::TYPE_EXIT, InventoryMovement::TYPE_TRANSFER], true)) {
                    $availableStock = $this->stockForItemAtLocation($item->id, (int) $fromLocationId);

                    if ($validated['quantity'] > $availableStock) {
                        // Lanzamos excepción para revertir todo
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'quantity' => "La cantidad supera el stock disponible en la ubicacion seleccionada ({$availableStock} unidades).",
                        ]);
                    }
                }

                // 3. Auditoría: Registramos qué usuario hizo el movimiento
                $validated['performed_by'] = Auth::id();

                // 4. Guardamos el movimiento
                InventoryMovement::create($validated);
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si falla el stock, devolvemos al usuario con el mensaje de error
            return back()->withInput()->withErrors($e->errors());
        }

        return redirect()->route('inventory.movements.index')->with('status', 'Movimiento guardado correctamente.');
    }

    private function itemsWithLocationStockQuery(): Builder
    {
        return Item::query()
            ->leftJoin('inventory_movements', 'items.id', '=', 'inventory_movements.item_id')
            ->select('items.*')
            ->selectRaw("
                COALESCE(SUM(CASE WHEN inventory_movements.to_location_id = (SELECT id FROM locations WHERE code = 'BODEGA' LIMIT 1) THEN inventory_movements.quantity ELSE 0 END), 0)
                -
                COALESCE(SUM(CASE WHEN inventory_movements.from_location_id = (SELECT id FROM locations WHERE code = 'BODEGA' LIMIT 1) THEN inventory_movements.quantity ELSE 0 END), 0)
                AS bodega_stock
            ")
            ->selectRaw("
                COALESCE(SUM(CASE WHEN inventory_movements.to_location_id = (SELECT id FROM locations WHERE code = 'OFICINA' LIMIT 1) THEN inventory_movements.quantity ELSE 0 END), 0)
                -
                COALESCE(SUM(CASE WHEN inventory_movements.from_location_id = (SELECT id FROM locations WHERE code = 'OFICINA' LIMIT 1) THEN inventory_movements.quantity ELSE 0 END), 0)
                AS oficina_stock
            ")
            ->selectRaw("
                COALESCE(SUM(CASE WHEN inventory_movements.to_location_id IS NOT NULL THEN inventory_movements.quantity ELSE 0 END), 0)
                -
                COALESCE(SUM(CASE WHEN inventory_movements.from_location_id IS NOT NULL THEN inventory_movements.quantity ELSE 0 END), 0)
                AS total_stock
            ")
            ->groupBy('items.id', 'items.name', 'items.brand', 'items.model', 'items.unit', 'items.created_at', 'items.updated_at');
    }

    public function summary(): array
    {
        $today = now()->timezone(config('app.timezone'))->toDateString();
        $itemsWithStock = $this->itemsWithLocationStockQuery()->get();

        return [
            'entries' => (int) InventoryMovement::query()->where('type', InventoryMovement::TYPE_ENTRY)->sum('quantity'),
            'exits' => (int) InventoryMovement::query()->where('type', InventoryMovement::TYPE_EXIT)->sum('quantity'),
            'transfers' => (int) InventoryMovement::query()->where('type', InventoryMovement::TYPE_TRANSFER)->sum('quantity'),
            'stock' => (int) $itemsWithStock->sum('total_stock'),
            'items' => (int) Item::query()->count(),
            'movements_today' => (int) InventoryMovement::query()->whereDate('performed_at', $today)->count(),
            'low_stock_items' => (int) $itemsWithStock->filter(fn ($item) => (int) $item->total_stock <= 3)->count(),
            'out_of_stock_items' => (int) $itemsWithStock->filter(fn ($item) => (int) $item->total_stock <= 0)->count(),
            'average_stock' => $itemsWithStock->count() > 0 ? round($itemsWithStock->avg(fn ($item) => (int) $item->total_stock), 1) : 0,
            'bodega_stock' => (int) $itemsWithStock->sum('bodega_stock'),
            'oficina_stock' => (int) $itemsWithStock->sum('oficina_stock'),
        ];
    }

    private function movementTypes(): array
    {
        return [
            InventoryMovement::TYPE_ENTRY => 'Entrada',
            InventoryMovement::TYPE_EXIT => 'Salida',
            InventoryMovement::TYPE_TRANSFER => 'Transferencia',
        ];
    }

    private function deliveryRecordTypes(): array
    {
        return [
            DeliveryRecord::TYPE_SUPPLIES => 'Acta de insumos',
            DeliveryRecord::TYPE_EQUIPMENTS => 'Acta de equipos',
        ];
    }

    private function stockForItemAtLocation(int $itemId, int $locationId): int
    {
        return (int) InventoryMovement::query()
            ->where('item_id', $itemId)
            ->selectRaw("
                COALESCE(SUM(CASE WHEN to_location_id = ? THEN quantity ELSE 0 END), 0)
                -
                COALESCE(SUM(CASE WHEN from_location_id = ? THEN quantity ELSE 0 END), 0)
                AS stock
            ", [$locationId, $locationId])
            ->value('stock');
    }

    private function updateSuppliesDeliveryRecord(Request $request, DeliveryRecord $record): RedirectResponse
    {
        $validated = $request->validate([
            'delivery_date' => ['required', 'date'],
            'district' => ['required', 'string', 'max:120'],
            'assigned_area_id' => ['required', 'integer', Rule::exists('municipal_areas', 'id')],
            'receives' => ['required', 'string', 'max:255'],
            'from_location_id' => ['required', 'integer', Rule::exists('locations', 'id')],
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.quantity' => ['nullable', 'integer', 'min:1'],
            'rows.*.item_id' => ['nullable', 'integer', Rule::exists('items', 'id')],
        ]);

        $rows = collect($validated['rows'])
            ->map(fn (array $row) => [
                'quantity' => (int) ($row['quantity'] ?? 0),
                'item_id' => $row['item_id'] ?? null,
            ])
            ->filter(fn (array $row) => $row['quantity'] > 0 || $row['item_id'])
            ->values();

        if ($rows->isEmpty() || $rows->contains(fn (array $row) => $row['quantity'] <= 0 || ! $row['item_id'])) {
            return back()
                ->withInput()
                ->withErrors(['rows' => 'Cada fila debe llevar cantidad e insumo validos.']);
        }

        $fromLocation = Location::query()->findOrFail($validated['from_location_id']);
        $assignedArea = MunicipalArea::query()->findOrFail($validated['assigned_area_id']);
        $responsible = $assignedArea->responsible_name ?: 'Sin responsable asignado';
        $items = Item::query()->whereIn('id', $rows->pluck('item_id')->all())->get()->keyBy('id');

        $currentLinkedQuantities = InventoryMovement::query()
            ->where('delivery_record_id', $record->id)
            ->selectRaw('item_id, SUM(quantity) as total_quantity')
            ->groupBy('item_id')
            ->pluck('total_quantity', 'item_id');

        $groupedQuantities = $rows->groupBy('item_id')->map(fn ($group) => $group->sum('quantity'));

        foreach ($groupedQuantities as $itemId => $quantity) {
            $availableStock = $this->stockForItemAtLocation((int) $itemId, $fromLocation->id)
                + (int) ($currentLinkedQuantities[(int) $itemId] ?? 0);

            if ($quantity > $availableStock) {
                $item = $items->get((int) $itemId);
                $name = trim(($item?->name ?? '').' '.($item?->brand ?? '').' '.($item?->model ?? ''));

                return back()
                    ->withInput()
                    ->withErrors([
                        'rows' => "La correccion solicitada para {$name} supera el stock disponible en {$fromLocation->name} ({$availableStock} unidades).",
                    ]);
            }
        }

        DB::transaction(function () use ($record, $validated, $rows, $fromLocation, $assignedArea, $responsible, $items) {
            InventoryMovement::query()->where('delivery_record_id', $record->id)->delete();
            $record->lines()->delete();

            $record->update([
                'delivery_date' => $validated['delivery_date'],
                'district' => $validated['district'],
                'area_name' => $assignedArea->name,
                'responsible_name' => $responsible,
                'receives' => $validated['receives'],
                'from_location_name' => $fromLocation->name,
                'from_location_id' => $fromLocation->id,
                'to_location_name' => $assignedArea->name,
                'assigned_area_id' => $assignedArea->id,
                'total_lines' => $rows->count(),
            ]);

            $performedAt = $validated['delivery_date'].' '.now()->timezone(config('app.timezone'))->format('H:i:s');

            foreach ($rows as $row) {
                $item = $items->get((int) $row['item_id']);

                InventoryMovement::query()->create([
                    'item_id' => $row['item_id'],
                    'delivery_record_id' => $record->id,
                    'type' => InventoryMovement::TYPE_EXIT,
                    'from_location_id' => $fromLocation->id,
                    'to_location_id' => null,
                    'quantity' => $row['quantity'],
                    'notes' => 'Correccion de acta de entrega a '.$validated['receives'].' - '.$assignedArea->name,
                    'performed_at' => $performedAt,
                ]);

                DeliveryRecordLine::query()->create([
                    'delivery_record_id' => $record->id,
                    'description' => trim(($item?->name ?? '').' '.($item?->brand ?? '').' '.($item?->model ?? '')),
                    'item_id' => $row['item_id'],
                    'quantity' => $row['quantity'],
                ]);
            }
        });

        return redirect()
            ->route('inventory.delivery-act.history')
            ->with('status', 'Acta de insumos corregida correctamente.');
    }

    private function updateEquipmentDeliveryRecord(Request $request, DeliveryRecord $record): RedirectResponse
    {
        $validated = $request->validate([
            'delivery_date' => ['required', 'date'],
            'district' => ['required', 'string', 'max:120'],
            'receives' => ['required', 'string', 'max:255'],
            'assigned_area_id' => ['required', 'integer', Rule::exists('municipal_areas', 'id')],
            'location_id' => ['nullable', 'integer', Rule::exists('locations', 'id')],
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.equipment_id' => ['nullable', 'integer', Rule::exists('equipments', 'id')],
        ]);

        $rows = collect($validated['rows'])
            ->map(fn (array $row) => ['equipment_id' => $row['equipment_id'] ?? null])
            ->filter(fn (array $row) => ! empty($row['equipment_id']))
            ->unique('equipment_id')
            ->values();

        if ($rows->isEmpty()) {
            return back()
                ->withInput()
                ->withErrors(['rows' => 'Agrega al menos un equipo en el acta.']);
        }

        $assignedArea = MunicipalArea::query()->findOrFail($validated['assigned_area_id']);
        $responsible = $assignedArea->responsible_name ?: 'Sin responsable asignado';
        $equipments = Equipment::query()->whereIn('id', $rows->pluck('equipment_id')->all())->get()->keyBy('id');

        DB::transaction(function () use ($record, $validated, $rows, $assignedArea, $responsible, $equipments) {
            $record->lines()->delete();

            $record->update([
                'delivery_date' => $validated['delivery_date'],
                'district' => $validated['district'],
                'area_name' => $assignedArea->name,
                'responsible_name' => $responsible,
                'receives' => $validated['receives'],
                'to_location_name' => $assignedArea->name,
                'assigned_area_id' => $assignedArea->id,
                'location_id' => $validated['location_id'] ?? null,
                'total_lines' => $rows->count(),
            ]);

            foreach ($rows as $row) {
                $equipment = $equipments->get((int) $row['equipment_id']);

                if ($equipment) {
                    $equipment->update([
                        'status' => 'assigned',
                        'assigned_area_id' => $assignedArea->id,
                        'assigned_area' => $assignedArea->name,
                        'location_id' => $validated['location_id'] ?? null,
                        'assigned_at' => $validated['delivery_date'],
                        'notes' => 'Acta corregida de entrega de equipo a '.$validated['receives'].' - '.$assignedArea->name,
                    ]);
                }

                DeliveryRecordLine::query()->create([
                    'delivery_record_id' => $record->id,
                    'description' => $equipment?->name ?? 'Equipo',
                    'equipment_id' => $row['equipment_id'],
                    'quantity' => 1,
                    'asset_code' => $equipment?->asset_code,
                    'serial_number' => $equipment?->serial_number,
                ]);
            }
        });

        return redirect()
            ->route('inventory.delivery-act.history')
            ->with('status', 'Acta de equipos corregida correctamente.');
    }

    private function csvResponse(array $lines, string $filename): Response
    {
        $content = collect($lines)
            ->map(fn (array $row) => collect($row)
                ->map(function ($value) {
                    $value = (string) $value;
                    $value = str_replace('"', '""', $value);

                    return '"'.$value.'"';
                })
                ->implode(','))
            ->implode("\n");

        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
            private function excelResponse(array $lines, string $filename)
    {
        // Estilos CSS para simular un Excel profesional
        $html = '<html><head><meta charset="utf-8"><style>
            table { font-family: Calibri, Arial, sans-serif; border-collapse: collapse; width: 100%; font-size: 11pt; }
            th, td { border: 1px solid #d1d5db; padding: 8px; }
            .title { font-size: 16pt; font-weight: bold; text-align: center; background-color: #1d2d42; color: #ffffff; padding: 12px; border: none; }
            .subtitle { font-size: 12pt; font-weight: bold; background-color: #e5e7eb; color: #111827; padding: 8px; }
            .header { background-color: #374151; color: #ffffff; font-weight: bold; text-align: center; }
            .data-row { background-color: #ffffff; }
            .data-row:nth-child(even) { background-color: #f9fafb; }
            .text-left { text-align: left; } 
            .text-center { text-align: center; } 
            .text-right { text-align: right; } 
        </style></head><body><table>';

        $maxCols = 7; // Columnas máximas (la tabla de movimientos tiene 7)
        $rowCount = 0;

        foreach ($lines as $line) {
            // Filas vacías de separación
            if (empty($line)) {
                $html .= '<tr><td colspan="' . $maxCols . '" style="border:none; height: 15px;"></td></tr>';
                continue;
            }

            // Detectar si es un título, subtítulo o encabezado
            $isTitle = (count($line) === 1 && stripos($line[0], 'resumen') !== false);
            $isSubtitle = (count($line) === 1 && !$isTitle);
            $isHeader = (in_array('Nombre', $line) || in_array('Indicador', $line) || in_array('Fecha', $line));

            $trClass = 'data-row';
            if ($isTitle) $trClass = 'title';
            elseif ($isSubtitle) $trClass = 'subtitle';
            elseif ($isHeader) $trClass = 'header';

            // Si la fila tiene solo 1 columna (títulos), la expandimos
            if (count($line) === 1) {
                $html .= '<tr class="' . $trClass . '"><td colspan="' . $maxCols . '" class="text-left">' . htmlspecialchars((string)$line[0]) . '</td></tr>';
                continue;
            }

            // Filas normales de datos
            $html .= '<tr class="' . $trClass . '">';
            foreach ($line as $cell) {
                $val = (string)($cell ?? '');
                if ($isHeader) {
                    $html .= '<td class="text-center">' . htmlspecialchars($val) . '</td>';
                } else {
                    // Si es un número, lo alineamos a la derecha, si es texto a la izquierda
                    $align = is_numeric($val) ? 'text-right' : 'text-left';
                    $html .= '<td class="' . $align . '">' . htmlspecialchars($val) . '</td>';
                }
            }
            $html .= '</tr>';
            $rowCount++;
        }

        $html .= '</table></body></html>';

        // Descargar el archivo con extensión .xls
        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
