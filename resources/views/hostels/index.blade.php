@extends('layouts.app')
@section('title', 'Mes Hostels')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mes Hostels</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $hostels->count() }} établissement(s) enregistré(s)</p>
        </div>
        <a href="{{ route('hostels.create') }}"
           class="flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-blue-700">
            + Ajouter un hostel
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 mb-4 text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
            ❌ {{ session('error') }}
        </div>
    @endif

    @forelse($hostels as $hostel)
    <div class="bg-white rounded-xl border {{ $hostel->id == $activeId ? 'border-blue-400 shadow-md' : 'border-gray-200' }} mb-4 overflow-hidden">
        <div class="flex items-center gap-4 p-5">

            {{-- Icône --}}
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0
                        {{ $hostel->id == $activeId ? 'bg-blue-50' : 'bg-gray-50' }}">
                {{ $hostel->type === 'camping' ? '🏕' : '🏨' }}
            </div>

            {{-- Infos --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-gray-900 text-base">{{ $hostel->name }}</h3>
                    @if($hostel->id == $activeId)
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-semibold">✓ Actif</span>
                    @endif
                    @if(!($hostel->is_active ?? true))
                        <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-semibold">🚫 Désactivé</span>
                    @endif
                </div>
                <div class="text-sm text-gray-500 mt-0.5 flex items-center gap-3 flex-wrap">
                    @if($hostel->region)
                        <span>📍 {{ $hostel->region->name }}</span>
                    @elseif($hostel->city)
                        <span>📍 {{ $hostel->city }}</span>
                    @endif
                    @if($hostel->type)
                        <span class="capitalize">{{ $hostel->type }}</span>
                    @endif
                    @if($hostel->phone)
                        <span>📞 {{ $hostel->phone }}</span>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2 flex-shrink-0">
                {{-- Basculer --}}
                @if($hostel->id != $activeId && ($hostel->is_active ?? true))
                    <form method="POST" action="{{ route('hostel.switch', $hostel) }}">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 rounded-lg border border-blue-300 text-blue-600 text-sm font-semibold hover:bg-blue-50 transition">
                            Basculer
                        </button>
                    </form>
                @endif

                {{-- Modifier --}}
                <a href="{{ route('hostels.edit', $hostel) }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-gray-600 text-sm font-semibold hover:bg-gray-50 transition">
                    Modifier
                </a>

                {{-- Supprimer --}}
                @if($hostels->count() > 1)
                    <form method="POST" action="{{ route('hostels.destroy', $hostel) }}"
                          onsubmit="return confirm('Supprimer ce hostel ? Cette action est irréversible.')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="px-3 py-2 rounded-lg border border-red-200 text-red-500 text-sm font-semibold hover:bg-red-50 transition">
                            🗑
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Description --}}
        @if($hostel->description)
        <div class="px-5 pb-4 border-t border-gray-50">
            <p class="text-sm text-gray-400 mt-3 line-clamp-2">{{ $hostel->description }}</p>
        </div>
        @endif
    </div>
    @empty
        <div class="text-center py-16 text-gray-400">
            <div class="text-5xl mb-4">🏨</div>
            <p class="text-lg font-semibold text-gray-500">Aucun hostel enregistré</p>
            <a href="{{ route('hostels.create') }}" class="inline-block mt-4 bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-blue-700">
                Créer mon premier hostel
            </a>
        </div>
    @endforelse
</div>
@endsection