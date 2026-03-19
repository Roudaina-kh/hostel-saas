@php $input = "w-full rounded-xl px-4 py-3 text-sm outline-none transition-all"; $style = "border:1.5px solid #D8E8F0;background:#F8FBFD;color:#1A2B3C;"; @endphp

<div>
    <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Nom de l'espace *</label>
    <input type="text" name="name" value="{{ old('name', $tentSpace->name ?? '') }}" required
           class="{{ $input }}" style="{{ $style }}"
           onfocus="this.style.borderColor='#2C6E8A';this.style.background='#fff'"
           onblur="this.style.borderColor='#D8E8F0';this.style.background='#F8FBFD'">
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Capacité max tentes *</label>
        <input type="number" name="max_tents" value="{{ old('max_tents', $tentSpace->max_tents ?? 1) }}" min="1" required
               class="{{ $input }}" style="{{ $style }}"
               onfocus="this.style.borderColor='#2C6E8A';this.style.background='#fff'"
               onblur="this.style.borderColor='#D8E8F0';this.style.background='#F8FBFD'">
    </div>
    <div>
        <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Statut *</label>
        <select name="status" class="{{ $input }}" style="{{ $style }}">
            <option value="active" {{ old('status', $tentSpace->status ?? 'active') === 'active' ? 'selected' : '' }}>✅ Active</option>
            <option value="maintenance" {{ old('status', $tentSpace->status ?? '') === 'maintenance' ? 'selected' : '' }}>🔧 Maintenance</option>
            <option value="inactive" {{ old('status', $tentSpace->status ?? '') === 'inactive' ? 'selected' : '' }}>❌ Inactive</option>
        </select>
    </div>
</div>
<div>
    <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Description</label>
    <textarea name="description" rows="3" class="{{ $input }}" style="{{ $style }}"
              onfocus="this.style.borderColor='#2C6E8A';this.style.background='#fff'"
              onblur="this.style.borderColor='#D8E8F0';this.style.background='#F8FBFD'">{{ old('description', $tentSpace->description ?? '') }}</textarea>
</div>