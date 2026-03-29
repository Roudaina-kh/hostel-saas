@extends('layouts.app')
@section('title', 'Ajouter un Membre (Manager)')
@section('content')

<div class="max-w-xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold" style="color: #1A2B3C;">Ajouter un membre d'équipe</h1>
        <p class="text-sm mt-1" style="color: #8A9BB0;">Créez un compte pour un membre de votre équipe opérationnelle.</p>
    </div>

    <div class="bg-white rounded-2xl p-8 shadow-sm" style="border: 1px solid #E8EEF2;">
        @if($errors->any())
        <div class="rounded-xl p-4 mb-6 text-sm" style="background:#FEF2F2;border:1px solid #FECACA;color:#DC2626;">
            @foreach($errors->all() as $e)<p>• {{ $e }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('manager.staff.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold mb-2">Nom Complet *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none focus:border-[#3B82F6]">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none focus:border-[#3B82F6]">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2">Rôle *</label>
                <select name="role" required class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none focus:border-[#3B82F6]">
                    <option value="staff">Staff Opérationnel</option>
                    <option value="financial">Responsable Financier</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Mot de passe *</label>
                    <input type="password" name="password" required class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none focus:border-[#3B82F6]">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Confirmer *</label>
                    <input type="password" name="password_confirmation" required class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none focus:border-[#3B82F6]">
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 btn-blue">Créer le compte</button>
                <a href="{{ route('manager.staff.index') }}" class="flex-1 px-6 py-2.5 text-center bg-[#F1F5F9] text-[#64748B] rounded-xl font-bold border-none transition-colors hover:bg-[#E2E8F0] cursor-pointer no-underline text-sm leading-[2.5]">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
