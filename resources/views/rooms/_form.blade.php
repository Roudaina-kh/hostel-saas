@php $input = "w-full rounded-xl px-4 py-3 text-sm outline-none transition-all"; $style = "border:1.5px solid #D8E8F0;background:#F8FBFD;color:#1A2B3C;"; @endphp

<div>
    <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Nom de la chambre *</label>
    <input type="text" name="name" value="{{ old('name', $room->name ?? '') }}" required
           class="{{ $input }}" style="{{ $style }}"
           onfocus="this.style.borderColor='#2C6E8A';this.style.background='#fff';this.style.boxShadow='0 0 0 3px rgba(44,110,138,0.1)'"
           onblur="this.style.borderColor='#D8E8F0';this.style.background='#F8FBFD';this.style.boxShadow='none'">
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Type *</label>
        <select name="type" class="{{ $input }}" style="{{ $style }}">
            <option value="private" {{ old('type', $room->type ?? '') === 'private' ? 'selected' : '' }}>🔒 Private</option>
            <option value="dormitory" {{ old('type', $room->type ?? '') === 'dormitory' ? 'selected' : '' }}>🛏️ Dormitory</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Statut *</label>
        <select name="status" class="{{ $input }}" style="{{ $style }}">
            <option value="active" {{ old('status', $room->status ?? 'active') === 'active' ? 'selected' : '' }}>✅ Active</option>
            <option value="maintenance" {{ old('status', $room->status ?? '') === 'maintenance' ? 'selected' : '' }}>🔧 Maintenance</option>
            <option value="inactive" {{ old('status', $room->status ?? '') === 'inactive' ? 'selected' : '' }}>❌ Inactive</option>
        </select>
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Capacité min *</label>
        <input type="number" name="min_capacity" value="{{ old('min_capacity', $room->min_capacity ?? 1) }}" min="1" required
               class="{{ $input }}" style="{{ $style }}"
               onfocus="this.style.borderColor='#2C6E8A';this.style.background='#fff'"
               onblur="this.style.borderColor='#D8E8F0';this.style.background='#F8FBFD'">
    </div>
    <div>
        <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Capacité max *</label>
        <input type="number" name="max_capacity" value="{{ old('max_capacity', $room->max_capacity ?? 2) }}" min="1" required
               class="{{ $input }}" style="{{ $style }}"
               onfocus="this.style.borderColor='#2C6E8A';this.style.background='#fff'"
               onblur="this.style.borderColor='#D8E8F0';this.style.background='#F8FBFD'">
    </div>
</div>

<div>
    <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Description</label>
    <textarea name="description" rows="3"
              class="{{ $input }}" style="{{ $style }}"
              onfocus="this.style.borderColor='#2C6E8A';this.style.background='#fff'"
              onblur="this.style.borderColor='#D8E8F0';this.style.background='#F8FBFD'">{{ old('description', $room->description ?? '') }}</textarea>
</div>