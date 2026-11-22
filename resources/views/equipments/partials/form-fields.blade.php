<div class="field-grid">
    <div>
        <label for="equipment_category">Categoria</label>
        <select id="equipment_category" name="category" required data-equipment-category>
            @foreach ($categories as $value => $label)
                <option value="{{ $value }}" @selected(old('category', $equipment?->category) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="equipment_status">Estado</label>
        <select id="equipment_status" name="status" required data-equipment-status>
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $equipment?->status ?? 'available') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="field-grid">
    <div>
        <label for="equipment_brand">Marca</label>
        <input id="equipment_brand" name="brand" type="text" value="{{ old('brand', $equipment?->brand) }}">
    </div>
    <div>
        <label for="equipment_model">Modelo</label>
        <input id="equipment_model" name="model" type="text" value="{{ old('model', $equipment?->model) }}">
    </div>
</div>

<div class="field-grid">
    <div>
        <label for="equipment_serial_number">Serie</label>
        <input id="equipment_serial_number" name="serial_number" type="text" value="{{ old('serial_number', $equipment?->serial_number) }}">
    </div>
    <div></div>
</div>

<div class="field-grid">
    <div>
        <label for="equipment_asset_code">Codigo de activo</label>
        <input id="equipment_asset_code" name="asset_code" type="text" value="{{ old('asset_code', $equipment?->asset_code) }}">
    </div>
    <div>
        <label for="equipment_notes">Observaciones</label>
        <textarea id="equipment_notes" name="notes" placeholder="Garantia, accesorios, estado fisico, observaciones...">{{ old('notes', $equipment?->notes) }}</textarea>
    </div>
</div>

<div data-equipment-hardware-section>
    <div class="field-grid">
        <div>
            <label for="equipment_processor">Procesador</label>
            <input id="equipment_processor" name="processor" type="text" value="{{ old('processor', $equipment?->processor) }}" data-equipment-hardware-input>
        </div>
        <div>
            <label for="equipment_ram">RAM</label>
            <input id="equipment_ram" name="ram" type="text" value="{{ old('ram', $equipment?->ram) }}" data-equipment-hardware-input>
        </div>
    </div>

    <div class="field-grid">
        <div>
            <label for="equipment_storage">Almacenamiento</label>
            <input id="equipment_storage" name="storage" type="text" value="{{ old('storage', $equipment?->storage) }}" data-equipment-hardware-input>
        </div>
        <div>
            <label for="equipment_operating_system">Sistema operativo</label>
            <input id="equipment_operating_system" name="operating_system" type="text" value="{{ old('operating_system', $equipment?->operating_system) }}" data-equipment-hardware-input>
        </div>
    </div>
</div>

<button type="submit"><i class="fa-solid fa-floppy-disk"></i> {{ $submitLabel }}</button>
