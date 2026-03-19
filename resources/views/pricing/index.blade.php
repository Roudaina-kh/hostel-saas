@extends('layouts.app')
@section('title', 'Prix')
@section('content')

<div class="flex items-center justify-between mb-8 fade-up">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight text-[#0F172A]">Prix</h1>
        <p class="text-[15px] font-medium text-[#64748B] mt-1">Gérez la tarification de base et les tarifs saisonniers de vos chambres.</p>
    </div>
    <a href="{{ route('pricing.create') }}" class="btn-blue fade-up delay-1">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Ajouter un tarif
    </a>
</div>

<div class="glass-table fade-up delay-2">
    <table class="w-full text-[14.5px] text-left">
        <thead class="table-header-blue text-[#1E293B] shadow-sm uppercase tracking-wider text-[12px]">
            <tr>
                <th class="font-bold">CHAMBRE</th>
                <th class="font-bold text-center">PRIX</th>
                <th class="font-bold text-center">VALIDITÉ</th>
                <th class="font-bold text-center">STATUT</th>
                <th class="font-bold text-right">ACTIONS</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#E2E8F0]">
            @forelse($prices as $price)
            <tr class="table-row-hover" id="price-row-{{ $price->id }}">
                <td class="font-bold text-[#0F172A]">{{ $price->room->name }}</td>
                <td class="text-center font-extrabold text-[#2563EB] text-[16px]">
                    {{ number_format($price->price_amount, 3) }} 
                    <span class="text-[#64748B] text-[13px] font-bold">{{ $price->currency }}</span>
                </td>
                <td class="text-center font-semibold text-[#475569]">
                    @if($price->valid_from || $price->valid_to)
                        <div class="inline-flex items-center gap-2 bg-[#F8FAFC] border border-[#E2E8F0] px-3 py-1.5 rounded-xl shadow-sm">
                            <span class="text-[#0F172A]">{{ $price->valid_from?->format('d/m/Y') ?? '—' }}</span>
                            <span class="text-[#94A3B8] text-[10px]">▶</span>
                            <span class="text-[#0F172A]">{{ $price->valid_to?->format('d/m/Y') ?? '∞' }}</span>
                        </div>
                    @else
                        <span class="inline-flex items-center px-4 py-1.5 bg-[#F1F5F9] border border-[#CBD5E1] rounded-full font-bold text-[#475569] text-xs uppercase tracking-wider">Permanent</span>
                    @endif
                </td>
                <td class="text-center">
                    <span id="status-badge-{{ $price->id }}"
                          class="inline-flex px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider shadow-sm transition-colors duration-300 {{ $price->is_active ? 'bg-[#ECFDF5] text-[#059669] border border-[#A7F3D0]' : 'bg-[#F1F5F9] text-[#64748B] border border-[#CBD5E1]' }}">
                        {{ $price->is_active ? '✅ Actif' : 'Inactif' }}
                    </span>
                </td>
                <td class="text-right space-x-3">
                    @if(!$price->is_active)
                    <button onclick="activatePrice({{ $price->id }})"
                            class="inline-flex items-center px-3 py-1.5 rounded-lg text-[13px] font-bold text-[#10B981] hover:bg-[#ECFDF5] transition-colors border-none bg-transparent cursor-pointer">Activer</button>
                    @endif
                    <a href="{{ route('pricing.edit', $price) }}" class="inline-flex items-center justify-center p-2 rounded-xl text-[#3B82F6] hover:bg-[#EFF6FF] transition-colors" title="Modifier">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </a>
                    <button onclick="deleteItem('{{ route('pricing.destroy', $price) }}', 'ce tarif')" class="inline-flex items-center justify-center p-2 rounded-xl text-[#EF4444] hover:bg-[#FEF2F2] transition-colors cursor-pointer bg-transparent border-none outline-none" title="Supprimer">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-16 text-center text-[#64748B] font-medium text-[15.5px]">
                    <div class="text-5xl mb-4 opacity-50">💲</div>
                    Aucun tarif n'a été défini.<br>
                    <a href="{{ route('pricing.create') }}" class="text-[#3B82F6] font-bold hover:underline mt-2 inline-block">Créer votre premier tarif</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function activatePrice(id) {
    fetch(`/pricing/${id}/activate`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    }).then(() => window.location.reload());
}
</script>

@include('partials.delete-script')
@endsection