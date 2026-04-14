<div style="display:grid; gap:1rem;">

    <div>
        <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A4A6B; margin-bottom:0.4rem;">
            Nom de la taxe *
        </label>
        <input type="text" name="name" value="{{ old('name', $tax->name ?? '') }}"
               style="width:100%; padding:0.75rem 1rem; border-radius:0.75rem; border:1.5px solid #D0DDE8; font-size:0.9rem; outline:none;"
               placeholder="Ex: TVA Hébergement, Taxe de séjour..." required>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
        <div>
            <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A4A6B; margin-bottom:0.4rem;">
                Type *
            </label>
            {{-- Sprint 2 : 4 types obligatoires --}}
            <select name="type"
                    style="width:100%; padding:0.75rem 1rem; border-radius:0.75rem; border:1.5px solid #D0DDE8; font-size:0.9rem; outline:none; background:white;">
                <option value="percentage"
                    {{ old('type', $tax->type ?? '') === 'percentage' ? 'selected' : '' }}>
                    Pourcentage (%)
                </option>
                <option value="fixed_amount"
                    {{ old('type', $tax->type ?? '') === 'fixed_amount' ? 'selected' : '' }}>
                    Montant fixe
                </option>
                <option value="fixed_per_night"
                    {{ old('type', $tax->type ?? '') === 'fixed_per_night' ? 'selected' : '' }}>
                    Fixe par nuit
                </option>
                <option value="fixed_per_person_per_night"
                    {{ old('type', $tax->type ?? '') === 'fixed_per_person_per_night' ? 'selected' : '' }}>
                    Fixe par personne/nuit
                </option>
            </select>
        </div>

        <div>
            <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A4A6B; margin-bottom:0.4rem;">
                Montant *
            </label>
            <input type="number" name="amount" step="0.001" min="0"
                   value="{{ old('amount', $tax->amount ?? '') }}"
                   style="width:100%; padding:0.75rem 1rem; border-radius:0.75rem; border:1.5px solid #D0DDE8; font-size:0.9rem; outline:none;"
                   placeholder="Ex: 13.000" required>
        </div>
    </div>

    <div>
        <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A4A6B; margin-bottom:0.4rem;">
            Description
        </label>
        <textarea name="description" rows="2"
                  style="width:100%; padding:0.75rem 1rem; border-radius:0.75rem; border:1.5px solid #D0DDE8; font-size:0.9rem; outline:none; resize:vertical;"
                  placeholder="Description optionnelle...">{{ old('description', $tax->description ?? '') }}</textarea>
    </div>

    <div>
        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; font-size:0.9rem; font-weight:600; color:#1A4A6B;">
            <input type="hidden"   name="is_enabled" value="0">
            <input type="checkbox" name="is_enabled" value="1"
                   {{ old('is_enabled', $tax->is_enabled ?? true) ? 'checked' : '' }}
                   style="width:1.1rem; height:1.1rem; accent-color:#2C6E8A;">
            Taxe active
        </label>
    </div>

</div>