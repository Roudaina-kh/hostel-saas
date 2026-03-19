@php $input = "w-full rounded-xl px-4 py-3 text-sm outline-none transition-all"; $style = "border:1.5px solid #D8E8F0;background:#F8FBFD;color:#1A2B3C;"; @endphp

<div>
    <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Chambre *</label>
    <select name="room_id" required class="{{ $input }}" style="{{ $style }}">
        <option value="">-- Sélectionner une chambre --</option>
        @foreach($rooms as $room)
        <option value="{{ $room->id }}" {{ old('room_id', $pricing->room_id ?? '') == $room->id ? 'selected' : '' }}>
            {{ $room->name }}
        </option>
        @endforeach
    </select>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Prix *</label>
        <input type="number" name="price_amount" step="0.001" min="0"
               value="{{ old('price_amount', $pricing->price_amount ?? '') }}" required
               class="{{ $input }}" style="{{ $style }}"
               onfocus="this.style.borderColor='#2C6E8A';this.style.background='#fff'"
               onblur="this.style.borderColor='#D8E8F0';this.style.background='#F8FBFD'">
    </div>
    <div>
        <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Devise *</label>
        <select name="currency" class="{{ $input }}" style="{{ $style }}">
            <option value="TND" {{ old('currency', $pricing->currency ?? 'TND') === 'TND' ? 'selected' : '' }}>TND</option>
            <option value="EUR" {{ old('currency', $pricing->currency ?? '') === 'EUR' ? 'selected' : '' }}>EUR</option>
            <option value="USD" {{ old('currency', $pricing->currency ?? '') === 'USD' ? 'selected' : '' }}>USD</option>
        </select>
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Valide du</label>
        <input type="date" name="valid_from"
               value="{{ old('valid_from', isset($pricing->valid_from) ? $pricing->valid_from->format('Y-m-d') : '') }}"
               class="{{ $input }}" style="{{ $style }}"
               onfocus="this.style.borderColor='#2C6E8A';this.style.background='#fff'"
               onblur="this.style.borderColor='#D8E8F0';this.style.background='#F8FBFD'">
    </div>
    <div>
        <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Au</label>
        <input type="date" name="valid_to"
               value="{{ old('valid_to', isset($pricing->valid_to) ? $pricing->valid_to->format('Y-m-d') : '') }}"
               class="{{ $input }}" style="{{ $style }}"
               onfocus="this.style.borderColor='#2C6E8A';this.style.background='#fff'"
               onblur="this.style.borderColor='#D8E8F0';this.style.background='#F8FBFD'">
    </div>
</div>

<div class="flex items-center gap-3 p-4 rounded-xl" style="background:#F8FBFD;border:1px solid #E8EEF2;">
    <input type="checkbox" name="is_active" id="is_active" value="1"
           {{ old('is_active', $pricing->is_active ?? true) ? 'checked' : '' }}
           style="accent-color:#2C6E8A;width:16px;height:16px;">
    <label for="is_active" class="text-sm font-semibold" style="color:#1A2B3C;">
        Définir comme tarif actif
    </label>
</div>