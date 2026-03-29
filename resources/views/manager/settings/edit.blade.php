@extends('layouts.app')
@section('title', 'Paramètres Hostel (Manager)')
@section('content')

<div class="max-w-xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold" style="color: #1A2B3C;">Paramètres de l'Hostel</h1>
        <p class="text-sm mt-1" style="color: #8A9BB0;">Configurez les informations de l'établissement (Manager).</p>
    </div>

    <div class="bg-white rounded-2xl p-8 shadow-sm" style="border: 1px solid #E8EEF2;">
        @if($errors->any())
        <div class="rounded-xl p-4 mb-6 text-sm" style="background:#FEF2F2;border:1px solid #FECACA;color:#DC2626;">
            @foreach($errors->all() as $e)<p>• {{ $e }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('manager.settings.update') }}" class="space-y-5">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-semibold mb-2">Nom de l'Hostel *</label>
                <input type="text" name="name" value="{{ old('name', $hostel->name) }}" required class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none focus:border-[#3B82F6]">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Adresse *</label>
                <textarea name="address" rows="2" required class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none focus:border-[#3B82F6]">{{ old('address', $hostel->address) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone', $hostel->phone) }}" class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none focus:border-[#3B82F6]">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Email de contact</label>
                    <input type="email" name="email" value="{{ old('email', $hostel->email) }}" class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none focus:border-[#3B82F6]">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none focus:border-[#3B82F6]">{{ old('description', $hostel->description) }}</textarea>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full btn-blue">Sauvegarder les modifications</button>
            </div>
        </form>
    </div>
</div>
@endsection
