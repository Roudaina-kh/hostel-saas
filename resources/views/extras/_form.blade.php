<div style="display:grid; gap:1rem;">

    <div>
        <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A4A6B; margin-bottom:0.4rem;">
            Nom de l'extra *
        </label>
        <input type="text" name="name" value="{{ old('name', $extra->name ?? '') }}"
               style="width:100%; padding:0.75rem 1rem; border-radius:0.75rem; border:1.5px solid #D0DDE8; font-size:0.9rem; outline:none;"
               placeholder="Ex: Petit-déjeuner, Serviette..." required>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">

        <div>
            <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A4A6B; margin-bottom:0.4rem;">
                Mode de stock *
            </label>
            <select name="stock_mode"
                    style="width:100%; padding:0.75rem 1rem; border-radius:0.75rem; border:1.5px solid #D0DDE8; font-size:0.9rem; outline:none; background:white;">
                <option value="consumable" {{ old('stock_mode', $extra->stock_mode ?? '') === 'consumable' ? 'selected' : '' }}>Consommable</option>
                <option value="unlimited"  {{ old('stock_mode', $extra->stock_mode ?? '') === 'unlimited'  ? 'selected' : '' }}>Illimité</option>
                <option value="rentable"   {{ old('stock_mode', $extra->stock_mode ?? '') === 'rentable'   ? 'selected' : '' }}>Louable</option>
            </select>
        </div>

        <div>
            <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A4A6B; margin-bottom:0.4rem;">
                Stock disponible
            </label>
            <input type="number" name="stock_quantity" min="0"
                   value="{{ old('stock_quantity', $extra->stock_quantity ?? 0) }}"
                   style="width:100%; padding:0.75rem 1rem; border-radius:0.75rem; border:1.5px solid #D0DDE8; font-size:0.9rem; outline:none;"
                   placeholder="Ex: 50">
        </div>

    </div>

    <div>
        <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A4A6B; margin-bottom:0.4rem;">
            Seuil d'alerte stock
        </label>
        <input type="number" name="stock_alert_threshold" min="0"
               value="{{ old('stock_alert_threshold', $extra->stock_alert_threshold ?? 0) }}"
               style="width:100%; padding:0.75rem 1rem; border-radius:0.75rem; border:1.5px solid #D0DDE8; font-size:0.9rem; outline:none;"
               placeholder="Ex: 5">
    </div>

    <div>
        <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A4A6B; margin-bottom:0.4rem;">
            Description
        </label>
        <textarea name="description" rows="3"
                  style="width:100%; padding:0.75rem 1rem; border-radius:0.75rem; border:1.5px solid #D0DDE8; font-size:0.9rem; outline:none; resize:vertical;"
                  placeholder="Description optionnelle...">{{ old('description', $extra->description ?? '') }}</textarea>
    </div>

    <div>
        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; font-size:0.9rem; font-weight:600; color:#1A4A6B;">
            <input type="hidden"   name="is_enabled" value="0">
            <input type="checkbox" name="is_enabled" value="1"
                   {{ old('is_enabled', $extra->is_enabled ?? true) ? 'checked' : '' }}
                   style="width:1.1rem; height:1.1rem; accent-color:#2C6E8A;">
            Extra actif
        </label>
    </div>

</div>