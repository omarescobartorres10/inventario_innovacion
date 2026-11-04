<div>
    <label for="area_name">Area</label>
    <input id="area_name" name="name" type="text" value="{{ old('name', $area?->name) }}" required>
</div>

<div class="field-grid">
    <div>
        <label for="area_responsible_name">Responsable</label>
        <input id="area_responsible_name" name="responsible_name" type="text" value="{{ old('responsible_name', $area?->responsible_name) }}">
    </div>
    <div>
        <label for="area_email">Correo institucional</label>
        <input id="area_email" name="institutional_email" type="email" value="{{ old('institutional_email', $area?->institutional_email) }}">
    </div>
</div>

<div class="field-grid">
    <div>
        <label for="area_slug">Slug</label>
        <input id="area_slug" name="slug" type="text" value="{{ old('slug', $area?->slug) }}" placeholder="Se genera automatico si lo dejas vacio">
    </div>
    <div>
        <label for="area_status">Estado</label>
        <select id="area_status" name="is_active">
            <option value="1" @selected((string) old('is_active', (int) ($area?->is_active ?? 1)) === '1')>Activa</option>
            <option value="0" @selected((string) old('is_active', (int) ($area?->is_active ?? 1)) === '0')>Inactiva</option>
        </select>
    </div>
</div>

<button type="submit"><i class="fa-solid fa-floppy-disk"></i> {{ $submitLabel }}</button>
