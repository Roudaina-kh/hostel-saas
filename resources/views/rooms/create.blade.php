@extends('layouts.app')
@section('title', 'Ajouter une chambre')
@section('content')

<div class="max-w-xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold" style="color: #1A2B3C;">Ajouter une chambre</h1>
        <p class="text-sm mt-1" style="color: #8A9BB0;">Remplissez les informations de la chambre.</p>
    </div>

    <div class="bg-white rounded-2xl p-8 shadow-sm" style="border: 1px solid #E8EEF2;">
        @if($errors->any())
        <div class="rounded-xl p-4 mb-6 text-sm" style="background:#FEF2F2;border:1px solid #FECACA;color:#DC2626;">
            @foreach($errors->all() as $e)<p>• {{ $e }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('rooms.store') }}" class="space-y-5">
            @csrf
            @include('rooms._form')
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl text-sm font-bold text-white"
                        style="background: linear-gradient(135deg, #1A4A6B, #2C6E8A); box-shadow:0 4px 15px rgba(44,110,138,0.3);">
                    Créer la chambre
                </button>
                <a href="{{ route('rooms.index') }}"
                   class="px-6 py-2.5 rounded-xl text-sm font-medium"
                   style="background:#F8FBFD; color:#5A6B7A; border:1px solid #E8EEF2;">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection