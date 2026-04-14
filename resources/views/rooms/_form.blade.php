@php
$input = 'width:100%; border-radius:0.75rem; padding:0.75rem 1rem; font-size:0.875rem;
          outline:none; border:1.5px solid #D8E8F0; background:#F8FBFD;
          color:#1A2B3C; box-sizing:border-box;';
@endphp

<div style="margin-bottom:1rem;">
    <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Nom *</label>
    <input type="text" name="name" value="{{ old('name', $room->name ?? '') }}" required style="{{ $input }}">
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
    <div>
        <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Type *</label>
        <select name="type" required style="{{ $input }}">
            <option value="private" {{ old('type', $room->type ?? '') === 'private' ? 'selected' : '' }}>🔒 Private</option>
            <option value="dormitory" {{ old('type', $room->type ?? '') === 'dormitory' ? 'selected' : '' }}>🛏️ Dormitory</option>
        </select>
    </div>
    <div>
        <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Capacité max *</label>
        <input type="number" name="max_capacity" value="{{ old('max_capacity', $room->max_capacity ?? 2) }}" min="1" required style="{{ $input }}">
    </div>
</div>

<div style="margin-bottom:1rem;">
    <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Description</label>
    <textarea name="description" rows="3" style="{{ $input }}">{{ old('description', $room->description ?? '') }}</textarea>
</div>

<div style="display:flex; align-items:center; gap:0.75rem; padding:1rem; border-radius:0.75rem; background:#F8FBFD; border:1px solid #E8EEF2;">
    <input type="checkbox" name="is_enabled" value="1" id="is_enabled"
           {{ old('is_enabled', $room->is_enabled ?? true) ? 'checked' : '' }}
           style="accent-color:#2C6E8A; width:16px; height:16px;">
    <label for="is_enabled" style="font-size:0.875rem; font-weight:600; color:#1A2B3C; cursor:pointer;">
        Chambre active (exploitable)
    </label>
</div>