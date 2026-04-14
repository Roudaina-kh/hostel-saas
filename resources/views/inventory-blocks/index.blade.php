@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-black text-slate-800 mb-6">Indisponibilités</h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-bold">
            {{ session('success') }}
        </div>
    @endif

    {{-- Formulaire d'ajout --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
        <h2 class="text-lg font-bold text-slate-700 mb-4">Ajouter un blocage</h2>

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-bold">
                @foreach($errors->all() as $e) <p>{{ $e }}</p> @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('inventory-blocks.store') }}" class="grid grid-cols-2 gap-4">
            @csrf

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Type d'élément</label>
                <select name="blockable_type" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium outline-none focus:ring-2 focus:ring-blue-200">
                    <option value="room">Chambre</option>
                    <option value="bed">Lit</option>
                    <option value="tent_space">Espace tente</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Élément</label>
                <select name="blockable_id" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium outline-none focus:ring-2 focus:ring-blue-200">
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}">🚪 {{ $room->name }}</option>
                    @endforeach
                    @foreach($beds as $bed)
                        <option value="{{ $bed->id }}">🛏️ {{ $bed->name }} ({{ $bed->room->name }})</option>
                    @endforeach
                    @foreach($tentSpaces as $ts)
                        <option value="{{ $ts->id }}">⛺ {{ $ts->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Type de blocage</label>
                <select name="block_type" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium outline-none focus:ring-2 focus:ring-blue-200">
                    <option value="maintenance">Maintenance</option>
                    <option value="manual_block">Blocage manuel</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Date début</label>
                <input type="date" name="start_date" required value="{{ old('start_date') }}"
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium outline-none focus:ring-2 focus:ring-blue-200">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Date fin <span class="text-slate-400 font-normal">(optionnel)</span></label>
                <input type="date" name="end_date" value="{{ old('end_date') }}"
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium outline-none focus:ring-2 focus:ring-blue-200">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Motif</label>
                <input type="text" name="reason" value="{{ old('reason') }}" placeholder="Ex: plumbing"
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium outline-none focus:ring-2 focus:ring-blue-200">
            </div>

            <div class="col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-1">Note détaillée <span class="text-slate-400 font-normal">(optionnel)</span></label>
                <textarea name="note" rows="2" placeholder="Détails supplémentaires..."
                          class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium outline-none focus:ring-2 focus:ring-blue-200">{{ old('note') }}</textarea>
            </div>

            <div class="col-span-2">
                <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all">
                    Créer le blocage
                </button>
            </div>
        </form>
    </div>

    {{-- Liste des blocages --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-left px-6 py-4 font-bold text-slate-600">Élément</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600">Type</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600">Blocage</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600">Du</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600">Au</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600">Motif</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($blocks as $block)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $block->blockable?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-xs">{{ $block->blockable_type }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-lg text-xs font-bold
                                {{ $block->block_type === 'maintenance' ? 'bg-orange-50 text-orange-700' : 'bg-red-50 text-red-700' }}">
                                {{ $block->block_type === 'maintenance' ? 'Maintenance' : 'Blocage manuel' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $block->start_date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $block->end_date ? $block->end_date->format('d/m/Y') : '∞' }}
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-xs">{{ $block->reason ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('inventory-blocks.destroy', $block) }}"
                                  onsubmit="return confirm('Supprimer ce blocage ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:underline font-bold text-xs">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-medium">
                            Aucun blocage enregistré.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection