@extends('layouts.app')
@section('title', 'Modifier le membre')
@section('content')

<div class="max-w-xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold" style="color: #1A2B3C;">Modifier le membre</h1>
        <p class="text-sm mt-1" style="color: #8A9BB0;">{{ $manager->name }}</p>
    </div>

    <div class="bg-white rounded-2xl p-8 shadow-sm" style="border: 1px solid #E8EEF2;">
        @if($errors->any())
        <div class="rounded-xl p-4 mb-6 text-sm" style="background:#FEF2F2;border:1px solid #FECACA;color:#DC2626;">
            @foreach($errors->all() as $e)<p>• {{ $e }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('managers.update', $manager) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold mb-2">Nom Complet *</label>
                <input type="text" name="name" value="{{ old('name', $manager->name) }}" required
                       class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none focus:border-[#3B82F6]">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2">Email</label>
                <input type="email" value="{{ $manager->email }}" disabled
                       class="w-full rounded-xl px-4 py-3 bg-[#F1F5F9] border border-[#E2E8F0] text-[#94A3B8] cursor-not-allowed">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2">Téléphone</label>
                <input type="text" name="phone" value="{{ old('phone', $manager->phone) }}"
                       class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none focus:border-[#3B82F6]">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2">Rôle *</label>
                <select name="role" required
                        class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none focus:border-[#3B82F6]">
                    <option value="manager"  {{ old('role', $manager->role) === 'manager'   ? 'selected' : '' }}>Manager</option>
                    <option value="staff"    {{ old('role', $manager->role) === 'staff'     ? 'selected' : '' }}>Staff Opérationnel</option>
                    <option value="financial"{{ old('role', $manager->role) === 'financial' ? 'selected' : '' }}>Responsable Financier</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2">Statut *</label>
                <select name="status" required
                        class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none focus:border-[#3B82F6]">
                    <option value="active"   {{ ($manager->is_active ? 'active' : 'inactive') === 'active'   ? 'selected' : '' }}>Actif</option>
                    <option value="inactive" {{ ($manager->is_active ? 'active' : 'inactive') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 btn-blue">Sauvegarder</button>
                <a href="{{ route('managers.index') }}"
                    class="flex-1 px-6 py-2.5 text-center bg-[#F1F5F9] text-[#64748B] rounded-xl font-bold no-underline text-sm leading-[2.5]">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection