@extends('layouts.app')
@section('title', 'Taxes (Manager)')
@section('content')

<div class="flex items-center justify-between mb-8 fade-up text-[#1A2B3C]">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight">Taxes</h1>
        <p class="text-[15px] font-medium text-[#8A9BB0] mt-1">
            Taxes configurées pour cet hostel (lecture seule).
        </p>
    </div>
</div>

<div class="glass-table fade-up delay-1">
    <table class="w-full text-[14.5px] text-left">
        <thead class="table-header-blue text-[#1E293B] uppercase tracking-wider text-[12px]">
            <tr>
                <th class="font-bold">NOM</th>
                <th class="font-bold">TYPE</th>
                <th class="font-bold text-center">MONTANT</th>
                <th class="font-bold text-center">STATUT</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#E2E8F0]">
            @forelse($taxes as $tax)
            <tr class="table-row-hover">
                <td class="font-bold text-[#0F172A]">{{ $tax->name }}</td>
                <td class="text-[#64748B]">
                    @php
                        $typeLabels = [
                            'percentage'              => 'Pourcentage',
                            'fixed_amount'            => 'Montant fixe',
                            'fixed_per_night'         => 'Par nuit',
                            'fixed_per_person_per_night' => 'Par pers./nuit',
                        ];
                    @endphp
                    {{ $typeLabels[$tax->type] ?? $tax->type }}
                </td>
                <td class="text-center font-bold text-[#2563EB]">
                    {{ number_format($tax->amount, 3) }}
                    {{ $tax->type === 'percentage' ? '%' : 'TND' }}
                </td>
                <td class="text-center">
                    <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider border
                        {{ $tax->is_enabled
                            ? 'bg-[#ECFDF5] text-[#059669] border-[#A7F3D0]'
                            : 'bg-[#FEF2F2] text-[#DC2626] border-[#FECACA]' }}">
                        {{ $tax->is_enabled ? 'Active' : 'Inactive' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="py-16 text-center text-[#94A3B8]">
                    <div class="text-5xl mb-4 opacity-50">🧾</div>
                    Aucune taxe configurée pour cet hostel.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection