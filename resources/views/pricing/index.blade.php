@extends('layouts.app')
@section('title', 'Tarifs (Manager)')
@section('content')

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight text-[#0F172A]">Tarifs</h1>
        <p class="text-sm font-medium text-slate-500 mt-1">Gérez la tarification du hostel.</p>
    </div>
    <a href="{{ route('manager.pricing.create') }}" class="btn-blue">
        + Ajouter un tarif
    </a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-bold">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-slate-50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-600">
            <tr>
                <th class="px-6 py-4 font-bold">Élément</th>
                <th class="px-6 py-4 font-bold">Type</th>
                <th class="px-6 py-4 font-bold">Mode</th>
                <th class="px-6 py-4 font-bold">Prix HT</th>
                <th class="px-6 py-4 font-bold">Prix TTC</th>
                <th class="px-6 py-4 font-bold">Validité</th>
                <th class="px-6 py-4 font-bold">Taxes</th>
                <th class="px-6 py-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($prices as $price)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4 font-bold text-slate-800">
                    {{ $price->priceable?->name ?? '—' }}
                </td>
                <td class="px-6 py-4 text-slate-500 text-xs">{{ $price->priceable_type }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold">
                        {{ str_replace('_', ' ', $price->pricing_mode) }}
                    </span>
                </td>
                <td class="px-6 py-4 font-bold text-slate-800">{{ number_format($price->price_ht, 3) }}</td>
                <td class="px-6 py-4 font-bold text-green-700">{{ number_format($price->price_ttc, 3) }}</td>
                <td class="px-6 py-4 text-slate-500 text-xs">
                    {{ $price->valid_from?->format('d/m/Y') }}
                    @if($price->valid_to) → {{ $price->valid_to->format('d/m/Y') }} @else → ∞ @endif
                </td>
                <td class="px-6 py-4 text-xs">
                    @foreach($price->taxes as $tax)
                        <span class="inline-block px-2 py-0.5 bg-amber-50 text-amber-700 rounded-lg font-bold mr-1">
                            {{ $tax->name }}
                        </span>
                    @endforeach
                </td>
                <td class="px-6 py-4 flex items-center gap-2">
                    <a href="{{ route('manager.pricing.edit', $price) }}"
                       class="text-blue-600 hover:underline font-bold text-xs">Modifier</a>
                    <form method="POST" action="{{ route('manager.pricing.destroy', $price) }}"
                          onsubmit="return confirm('Supprimer ce tarif ?')" class="inline">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline font-bold text-xs bg-transparent border-none cursor-pointer">
                            Supprimer
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-12 text-center text-slate-400 font-medium">
                    Aucun tarif défini.
                    <a href="{{ route('manager.pricing.create') }}" class="text-blue-600 font-bold ml-1">Créer le premier →</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection