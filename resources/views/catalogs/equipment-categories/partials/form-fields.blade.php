<div>
    <label for="category_name">Nombre</label>
    <input id="category_name" name="name" type="text" value="{{ old('name', $category?->name) }}" required>
</div>

<div class="field-grid">
    <div>
        <label for="category_slug">Slug</label>
        <input id="category_slug" name="slug" type="text" value="{{ old('slug', $category?->slug) }}" placeholder="Se genera automatico si lo dejas vacio">
    </div>
    <div>
        <label for="category_status">Estado</label>
        <select id="category_status" name="is_active">
            <option value="1" @selected((string) old('is_active', (int) ($category?->is_active ?? 1)) === '1')>Activa</option>
            <option value="0" @selected((string) old('is_active', (int) ($category?->is_active ?? 1)) === '0')>Inactiva</option>
        </select>
    </div>
</div>

<button type="submit"><i class="fa-solid fa-floppy-disk"></i> {{ $submitLabel }}</button>
