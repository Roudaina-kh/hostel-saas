@extends('layouts.app')
@section('title', 'Modifier le Membre (Manager)')
@section('content')

<div class="max-w-xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold" style="color: #1A2B3C;">Modifier le membre d'équipe</h1>
        <p class="text-sm mt-1" style="color: #8A9BB0;">Mettez à jour les informations du membre (Manager).</p>
    </div>

    <div class="bg-white rounded-2xl p-8 shadow-sm" style="border: 1px solid #E8EEF2;">
        @if($errors->any())
        <div class="rounded-xl p-4 mb-6 text-sm" style="background:#FEF2F2;border:1px solid #FECACA;color:#DC2626;">
            @foreach($errors->all() as $e)<p>• {{ $e }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('manager.staff.update', $staff) }}" class="space-y-5">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-semibold mb-2">Nom Complet *</label>
                <input type="text" name="name" value="{{ old('name', $staff->name) }}" required class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none focus:border-[#3B82F6]">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Email *</label>
                <input type="email" name="email" value="{{ old('email', $staff->email) }}" required class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none focus:border-[#3B82F6]">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Rôle *</label>
                <select name="role" required class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none focus:border-[#3B82F6]">
                    <option value="staff" {{ $staff->role === 'staff' ? 'selected' : '' }}>Staff Opérationnel</option>
                    <option value="financial" {{ $staff->role === 'financial' ? 'selected' : '' }}>Responsable Financier</option>
                </select>
            </div>

            <div class="p-4 rounded-xl bg-[#F1F5F9]/50 border border-dashed border-[#CBD5E1]">
                <p class="text-xs font-bold text-[#64748B] mb-3 uppercase tracking-wider">Modifier le mot de passe (laisser vide si inchangé)</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <input type="password" name="password" placeholder="Nouveau..." class="w-full rounded-xl px-4 py-3 bg-white border border-[#D8E8F0] outline-none focus:border-[#3B82F6]">
                    </div>
                    <div>
                        <input type="password" name="password_confirmation" placeholder="Confirmer..." class="w-full rounded-xl px-4 py-3 bg-white border border-[#D8E8F0] outline-none focus:border-[#3B82F6]">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0]">
                <label class="text-sm font-bold text-[#1A2B3C]">Compte actif</label>
                <input type="checkbox" name="is_active" value="1" {{ $staff->is_active ? 'checked' : '' }} class="w-5 h-5">
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 btn-blue">Sauvegarder</button>
                <a href="{{ route('manager.staff.index') }}" class="flex-1 px-6 py-2.5 text-center bg-[#F1F5F9] text-[#64748B] rounded-xl font-bold border-none transition-colors hover:bg-[#E2E8F0] cursor-pointer no-underline text-sm leading-[2.5]">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
