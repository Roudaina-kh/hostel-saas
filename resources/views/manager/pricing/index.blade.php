@extends('layouts.app')
@section('title', 'Prix (Manager)')
@section('content')

<div class="flex items-center justify-between mb-8 fade-up text-[#1A2B3C]">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight">Prix</h1>
        <p class="text-[15px] font-medium text-[#8A9BB0] mt-1">Gérez la tarification de l'hostel.</p>
    </div>
    <a href="{{ route('manager.pricing.create') }}" class="btn-blue fade-up delay-1">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Ajouter un tarif
    </a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-bold fade-up">
        {{ session('success') }}
    </div>
@endif

<div class="glass-table fade-up delay-2">
    <table class="w-full text-[14.5px] text-left">
        <thead class="table-header-blue text-[#1E293B] shadow-sm uppercase tracking-wider text-[12px]">
            <tr>
                <th class="font-bold">ÉLÉMENT</th>
                <th class="font-bold text-center">MODE</th>
                <th class="font-bold text-center">PRIX</th>
                <th class="font-bold text-center">VALIDITÉ</th>
                <th class="font-bold text-right">ACTIONS</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#E2E8F0]">
            @forelse($prices as $price)
            <tr class="table-row-hover">

                {{-- Nom de l'élément tarifé (room, tent_space ou extra) --}}
                <td class="font-bold text-[#0F172A]">
                    {{ $price->priceable?->name ?? '—' }}
                    <span class="text-[#94A3B8] text-[11px] font-normal ml-1">
                        ({{ $price->priceable_type }})
                    </span>
                </td>

                {{-- Mode de tarification --}}
                <td class="text-center">
                    <span class="inline-flex px-2 py-1 bg-[#EFF6FF] text-[#2563EB] rounded-lg text-[11px] font-bold uppercase">
                        {{ str_replace('_', ' ', $price->pricing_mode) }}
                    </span>
                </td>

                {{-- Prix HT / TTC --}}
                <td class="text-center font-extrabold text-[#2563EB] text-[15px]">
                    {{ number_format($price->price_ttc, 3) }}
                    <span class="text-[#64748B] text-[12px] font-bold">TTC</span>
                    <div class="text-[11px] text-[#94A3B8] font-normal">
                        HT : {{ number_format($price->price_ht, 3) }}
                    </div>
                </td>

                {{-- Validité --}}
                <td class="text-center font-semibold text-[#475569]">
                    <div class="inline-flex items-center gap-2 bg-[#F8FAFC] border border-[#E2E8F0] px-3 py-1.5 rounded-xl shadow-sm">
                        <span class="text-[#0F172A]">{{ $price->valid_from?->format('d/m/Y') ?? '—' }}</span>
                        <span class="text-[#94A3B8] text-[10px]">▶</span>
                        <span class="text-[#0F172A]">{{ $price->valid_to?->format('d/m/Y') ?? '∞' }}</span>
                    </div>
                </td>

                {{-- Actions --}}
                <td class="text-right space-x-3">
                    <a href="{{ route('manager.pricing.edit', $price) }}"
                       class="inline-flex items-center justify-center p-2 rounded-xl text-[#3B82F6] hover:bg-[#EFF6FF] transition-colors"
                       title="Modifier">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </a>
                    <form action="{{ route('manager.pricing.destroy', $price) }}" method="POST"
                          class="inline-block" onsubmit="return confirm('Supprimer ce tarif ?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center justify-center p-2 rounded-xl text-[#EF4444] hover:bg-[#FEF2F2] transition-colors cursor-pointer bg-transparent border-none"
                                title="Supprimer">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-16 text-center text-[#94A3B8]">
                    <div class="text-5xl mb-4 opacity-50">💲</div>
                    Aucun tarif défini.
                    <a href="{{ route('manager.pricing.create') }}" class="block text-[#3B82F6] font-bold mt-2">
                        Créer le premier tarif →
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection