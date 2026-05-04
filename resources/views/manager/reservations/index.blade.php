@extends('layouts.app')

@section('title', 'Réservations — ' . $hostel->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

    {{-- ── En-tête ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Réservations</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $hostel->name }}</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold shadow-sm"
                  style="background-color:#2563eb; color:#ffffff;">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </span>
            <a href="#calendar-section"
               class="inline-flex items-center gap-2 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Calendrier
            </a>

            @if($canEdit)
            {{-- ✅ Bouton Créer — visible uniquement pour manager et staff --}}
            <a href="{{ route('manager.reservations.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition shadow-sm"
               style="background-color:#059669; color:#ffffff;"
               onmouseover="this.style.backgroundColor='#047857'"
               onmouseout="this.style.backgroundColor='#059669'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Créer réservation
            </a>
            @else
            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium border border-orange-200 text-orange-600 bg-orange-50">
                👁 Lecture seule
            </span>
            @endif
        </div>
    </div>

    {{-- ── Stats ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Total</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-1">réservations</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Confirmées</p>
            <p class="text-3xl font-bold text-red-600">{{ $stats['confirmed'] }}</p>
            <p class="text-xs text-gray-500 mt-1">réservations</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">En attente</p>
            <p class="text-3xl font-bold text-emerald-600">{{ $stats['pending'] }}</p>
            <p class="text-xs text-gray-500 mt-1">réservations</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Revenus</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['revenue'], 0, '.', ' ') }}</p>
            <p class="text-xs text-gray-500 mt-1">TND (total)</p>
        </div>
    </div>

    {{-- ── Tableau ── --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm mb-6">
        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 bg-gray-50 rounded-t-xl">
            <span class="font-semibold text-gray-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Réservations {{ $year }}
            </span>
            <div class="flex items-center gap-2">
                <a href="{{ route('manager.reservations.index', ['year' => $year - 1]) }}"
                   class="text-gray-400 hover:text-gray-700 transition p-1 rounded">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <span class="text-sm font-bold text-gray-700 w-12 text-center">{{ $year }}</span>
                <a href="{{ route('manager.reservations.index', ['year' => $year + 1]) }}"
                   class="text-gray-400 hover:text-gray-700 transition p-1 rounded">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        @if($reservations->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm font-medium">Aucune réservation pour {{ $year }}</p>
                @if($canEdit)
                    <a href="{{ route('manager.reservations.create') }}" class="mt-3 text-sm text-blue-600 hover:underline">
                        Créer la première réservation →
                    </a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100">
                            <th class="text-left px-5 py-3 font-semibold">Guest principal</th>
                            <th class="text-left px-4 py-3 font-semibold">Arrivée</th>
                            <th class="text-left px-4 py-3 font-semibold">Départ</th>
                            <th class="text-left px-4 py-3 font-semibold">Nuits</th>
                            <th class="text-left px-4 py-3 font-semibold">Guests</th>
                            <th class="text-left px-4 py-3 font-semibold">Source</th>
                            <th class="text-left px-4 py-3 font-semibold">Montant TND</th>
                            <th class="text-left px-4 py-3 font-semibold">Statut</th>
                            <th class="text-left px-4 py-3 font-semibold">Ajouté par</th>
                            @if($canEdit)
                            <th class="text-left px-4 py-3 font-semibold">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($reservations as $res)
                            @php
                                $isConfirmed = $res->status === 'confirmed';
                                $isPending   = $res->status === 'pending';
                                $today       = now()->toDateString();
                                $isActive    = $res->start_date->toDateString() <= $today
                                            && $res->end_date->toDateString() > $today;
                            @endphp
                            <tr class="hover:bg-gray-50 transition {{ $isActive ? 'bg-blue-50/40' : '' }}">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        @if($isActive)
                                            <span class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0 animate-pulse"></span>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900">
                                                {{ $res->mainGuest?->first_name }} {{ $res->mainGuest?->last_name }}
                                            </p>
                                            <p class="text-xs text-gray-400">{{ $res->mainGuest?->country?->name ?? '—' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-700 whitespace-nowrap">{{ $res->start_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-gray-700 whitespace-nowrap">{{ $res->end_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $res->nights }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $res->total_guests }}</td>
                                <td class="px-4 py-3">
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-medium">
                                        {{ $res->source ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-semibold text-gray-900">{{ number_format($res->total_price_tnd, 3) }}</td>
                                <td class="px-4 py-3">
                                    @if($isConfirmed)
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold bg-red-100 text-red-700 px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Confirmé
                                        </span>
                                    @elseif($isPending)
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>En attente
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold bg-gray-100 text-gray-500 px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>{{ ucfirst($res->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">{{ $res->created_by ?? '—' }}</td>
                                @if($canEdit)
                                <td class="px-4 py-3">
                                    <a href="{{ route('manager.reservations.edit', $res->id) }}"
                                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-1.5 rounded-lg transition whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Modifier
                                    </a>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ── Calendrier ── --}}
    @include('reservations._calendar', ['year' => $year, 'calendarDays' => $calendarDays])

</div>
@endsection