@extends('layouts.app')
@section('title', 'Modifier le tarif')
@section('content')
<div class="max-w-xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold" style="color:#1A2B3C;">Modifier le tarif</h1>
    </div>
    <div class="bg-white rounded-2xl p-8 shadow-sm" style="border:1px solid #E8EEF2;">
        <form method="POST" action="{{ route('pricing.update', $pricing) }}" class="space-y-5">
            @csrf @method('PUT')
            @include('pricing._form', ['pricing' => $pricing])
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white"
                        style="background:linear-gradient(135deg,#1A4A6B,#2C6E8A);box-shadow:0 4px 15px rgba(44,110,138,0.3);">
                    Enregistrer
                </button>
                <a href="{{ route('pricing.index') }}" class="px-6 py-2.5 rounded-xl text-sm font-medium"
                   style="background:#F8FBFD;color:#5A6B7A;border:1px solid #E8EEF2;">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection