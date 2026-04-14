@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-black text-slate-800">Tarifs</h1>
        <a href="{{ route('prices.create') }}"
           class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all">
            + Ajouter un tarif
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-left px-6 py-4 font-bold text-slate-600">Élément</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600">Type</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600">Mode</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600">Prix HT</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600">Prix TTC</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600">Validité</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600">Taxes</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($prices as $price)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $price->priceable?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-slate-500">
                            {{ $price->priceable_type }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold">
                                {{ $price->pricing_mode }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ number_format($price->price_ht, 3) }}
                        </td>
                        <td class="px-6 py-4 font-bold text-green-700">
                            {{ number_format($price->price_ttc, 3) }}
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-xs">
                            {{ $price->valid_from?->format('d/m/Y') }}
                            @if($price->valid_to) → {{ $price->valid_to->format('d/m/Y') }} @else → ∞ @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-xs">
                            @foreach($price->taxes as $tax)
                                <span class="inline-block px-2 py-0.5 bg-amber-50 text-amber-700 rounded-lg font-bold mr-1">
                                    {{ $tax->name }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 flex items-center gap-3">
                            <a href="{{ route('prices.edit', $price) }}"
                               class="text-blue-600 hover:underline font-bold text-xs">Modifier</a>
                            <form method="POST" action="{{ route('prices.destroy', $price) }}"
                                  onsubmit="return confirm('Supprimer ce tarif ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:underline font-bold text-xs">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-400 font-medium">
                            Aucun tarif défini. Commencez par en ajouter un.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection