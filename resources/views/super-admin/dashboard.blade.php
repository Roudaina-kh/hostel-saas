@extends('super-admin.layouts.app')
@section('title', 'Dashboard Plateforme')
@section('content')

<div style="margin-bottom:2rem;">
    <h1 style="font-size:1.5rem; font-weight:700; color:#1A2B3C; margin:0;">
        Vue d'ensemble de la plateforme
    </h1>
    <p style="font-size:0.875rem; color:#8A9BB0; margin:0.25rem 0 0;">
        Supervision globale — Hostel SaaS
    </p>
</div>

{{-- Stats globales --}}
<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1.25rem; margin-bottom:2rem;">
    @foreach([
        ['label' => 'Propriétaires total',  'value' => $stats['total_owners'],  'icon' => '👑', 'color' => '#EFF6FF', 'text' => '#1A4A6B'],
        ['label' => 'Hostels total',         'value' => $stats['total_hostels'], 'icon' => '🏨', 'color' => '#F0FDF4', 'text' => '#2A6B4F'],
        ['label' => 'Owners actifs',         'value' => $stats['active_owners'], 'icon' => '✅', 'color' => '#FFFBEB', 'text' => '#92400E'],
    ] as $stat)
    <div style="background:white; border-radius:1rem; padding:1.5rem;
                border:1px solid #E8EEF2; display:flex; align-items:center; gap:1rem;">
        <div style="width:48px; height:48px; border-radius:0.75rem; background:{{ $stat['color'] }};
                    display:flex; align-items:center; justify-content:center; font-size:1.5rem; flex-shrink:0;">
            {{ $stat['icon'] }}
        </div>
        <div>
            <p style="font-size:1.75rem; font-weight:700; color:#1A2B3C; margin:0;">
                {{ $stat['value'] }}
            </p>
            <p style="font-size:0.8rem; color:#8A9BB0; margin:0;">{{ $stat['label'] }}</p>
        </div>
    </div>
    @endforeach
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">

    {{-- Derniers propriétaires --}}
    <div style="background:white; border-radius:1rem; padding:1.5rem; border:1px solid #E8EEF2;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
            <h2 style="font-size:1rem; font-weight:700; color:#1A2B3C; margin:0;">
                Derniers propriétaires
            </h2>
            <a href="{{ route('super-admin.owners.index') }}"
               style="font-size:0.75rem; color:#2C6E8A; text-decoration:none; font-weight:500;">
                Voir tous →
            </a>
        </div>
        @forelse($stats['recent_owners'] as $owner)
        <div style="display:flex; align-items:center; gap:0.75rem; padding:0.75rem 0;
                    border-bottom:1px solid #F0F4F8;">
            <div style="width:36px; height:36px; border-radius:50%; background:#EFF6FF;
                        display:flex; align-items:center; justify-content:center;
                        font-size:0.875rem; font-weight:700; color:#1A4A6B; flex-shrink:0;">
                {{ strtoupper(substr($owner->name, 0, 1)) }}
            </div>
            <div style="flex:1;">
                <p style="font-size:0.875rem; font-weight:600; color:#1A2B3C; margin:0;">
                    {{ $owner->name }}
                </p>
                <p style="font-size:0.75rem; color:#8A9BB0; margin:0;">{{ $owner->email }}</p>
            </div>
            <span style="font-size:0.7rem; color:#8A9BB0;">
                {{ $owner->created_at->diffForHumans() }}
            </span>
        </div>
        @empty
        <p style="color:#8A9BB0; font-size:0.875rem; text-align:center; padding:1rem 0;">
            Aucun propriétaire.
        </p>
        @endforelse
    </div>

    {{-- Derniers hostels --}}
    <div style="background:white; border-radius:1rem; padding:1.5rem; border:1px solid #E8EEF2;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
            <h2 style="font-size:1rem; font-weight:700; color:#1A2B3C; margin:0;">
                Derniers hostels
            </h2>
            <a href="{{ route('super-admin.hostels.index') }}"
               style="font-size:0.75rem; color:#2C6E8A; text-decoration:none; font-weight:500;">
                Voir tous →
            </a>
        </div>
        @forelse($stats['recent_hostels'] as $hostel)
        <div style="display:flex; align-items:center; gap:0.75rem; padding:0.75rem 0;
                    border-bottom:1px solid #F0F4F8;">
            <div style="width:36px; height:36px; border-radius:50%; background:#F0FDF4;
                        display:flex; align-items:center; justify-content:center;
                        font-size:1rem; flex-shrink:0;">🏨</div>
            <div style="flex:1;">
                <p style="font-size:0.875rem; font-weight:600; color:#1A2B3C; margin:0;">
                    {{ $hostel->name }}
                </p>
                <p style="font-size:0.75rem; color:#8A9BB0; margin:0;">
                    {{ $hostel->city }}, {{ $hostel->country }}
                    • {{ $hostel->owner->name }}
                </p>
            </div>
        </div>
        @empty
        <p style="color:#8A9BB0; font-size:0.875rem; text-align:center; padding:1rem 0;">
            Aucun hostel.
        </p>
        @endforelse
    </div>
</div>

@endsection