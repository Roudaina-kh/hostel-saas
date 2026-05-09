@extends('layouts.app')
@section('title', 'Ajouter un membre')

@section('content')
<div class="max-w-xl mx-auto px-4 py-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Ajouter un membre</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $activeHostel?->name ?? '' }}</p>
        </div>
        <a href="{{ route('managers.index') }}" class="text-sm text-blue-600 hover:underline">← Retour à l'équipe</a>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
            <ul class="list-disc ml-4 space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 bg-gray-50 rounded-t-xl">
            <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center">👤</span>
            <span class="font-semibold text-gray-800">Nouveau membre d'équipe</span>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('managers.store') }}" class="space-y-4">
                @csrf

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Nom complet <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="Prénom Nom"
                           class="border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           placeholder="membre@hostel.com"
                           class="border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           placeholder="+216 XX XXX XXX"
                           class="border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Rôle <span class="text-red-500">*</span></label>
                    <select name="role" required
                            class="border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">— Sélectionner un rôle —</option>
                        <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>👔 Manager</option>
                        <option value="staff"   {{ old('role') === 'staff'   ? 'selected' : '' }}>👤 Staff</option>
                        <option value="financial" {{ old('role') === 'financial' ? 'selected' : '' }}>💰 Financier</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Mot de passe <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required minlength="8"
                           placeholder="Minimum 8 caractères"
                           class="border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Confirmer le mot de passe <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required
                           placeholder="Répéter le mot de passe"
                           class="border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('managers.index') }}"
                       class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit"
                            class="flex items-center gap-2 bg-blue-600 text-white px-6 py-2.5 rounded-lg font-semibold text-sm hover:bg-blue-700">
                        ✅ Ajouter le membre
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection