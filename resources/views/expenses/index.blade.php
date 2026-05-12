@extends('layouts.app')
@section('title', 'Dépenses — ' . $activeHostel?->name)
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,500&family=DM+Sans:wght@400;500;600;700&display=swap');

:root {
    --sand:    #F5EFE6;
    --sand2:   #EDE3D4;
    --white:   #FEFCF9;
    --terra:      #C8602A;
    --terra2:     #A84E20;
    --terra-soft: #FEF3E2;
    --cream:      #F5C896;
    --teal:      #1B6B6B;
    --teal2:     #134F4F;
    --teal-soft: #E8F4F0;
    --night:  #1C1C24;
    --ink:    #2E2E3A;
    --gray:   #6B6B7A;
    --lgray:  #A0A0B0;
    --border: #DDD6CA;
    --success: #4A8F6E;
    --danger:  #A84E20;
    --warning: #C8842A;
}

.expenses-page { font-family: 'DM Sans', sans-serif; background: var(--sand); min-height: 100vh; padding: 2rem; color: var(--ink); }
.expenses-page * { box-sizing: border-box; }

.page-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem; }
.page-header h1 { font-family: 'Playfair Display', serif; font-size: 2.5rem; color: var(--night); margin: 0; }
.page-header h1 em { color: var(--terra); font-style: italic; }
.page-header .subtitle { color: var(--gray); margin-top: 0.25rem; font-size: 0.95rem; }

.btn-primary { background: var(--terra); color: white; padding: 0.75rem 1.5rem; border-radius: 10px; border: none; font-weight: 600; cursor: pointer; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; }
.btn-primary:hover { background: var(--terra2); transform: translateY(-1px); box-shadow: 0 6px 18px rgba(200, 96, 42, 0.3); }

/* Stats */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
.stat-card { background: white; border: 1px solid var(--border); border-radius: 14px; padding: 1.25rem 1.5rem; transition: all 0.2s; }
.stat-card:hover { border-color: var(--terra); box-shadow: 0 4px 14px rgba(0,0,0,0.04); }
.stat-card .label { color: var(--gray); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }
.stat-card .value { font-family: 'Playfair Display', serif; font-size: 1.85rem; color: var(--night); margin-top: 0.5rem; font-weight: 700; }
.stat-card .value.terra { color: var(--terra); }
.stat-card .value.teal { color: var(--teal); }
.stat-card .hint { color: var(--lgray); font-size: 0.8rem; margin-top: 0.25rem; }

/* Filters */
.filters { background: white; border: 1px solid var(--border); border-radius: 14px; padding: 1.25rem; margin-bottom: 1.5rem; }
.filters form { display: grid; grid-template-columns: 2fr 1.5fr 1fr 1fr auto; gap: 0.75rem; align-items: end; }
.filters label { display: block; font-size: 0.8rem; color: var(--gray); font-weight: 600; margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.3px; }
.filters input, .filters select { width: 100%; padding: 0.6rem 0.85rem; border: 1px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 0.95rem; background: white; color: var(--ink); }
.filters input:focus, .filters select:focus { outline: none; border-color: var(--terra); box-shadow: 0 0 0 3px rgba(200, 96, 42, 0.1); }
.filters .btn-filter { background: var(--teal); color: white; padding: 0.6rem 1.25rem; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; }
.filters .btn-filter:hover { background: var(--teal2); }
.filters .btn-reset { background: transparent; color: var(--gray); padding: 0.6rem 0.75rem; border: 1px solid var(--border); border-radius: 8px; cursor: pointer; text-decoration: none; font-size: 0.9rem; }

/* Table */
.expenses-table { background: white; border-radius: 14px; overflow: hidden; border: 1px solid var(--border); }
table { width: 100%; border-collapse: collapse; }
thead { background: var(--sand2); }
th { text-align: left; padding: 1rem; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--ink); font-weight: 700; border-bottom: 2px solid var(--border); }
td { padding: 1rem; border-bottom: 1px solid var(--border); font-size: 0.95rem; }
tr:last-child td { border-bottom: none; }
tr:hover { background: var(--terra-soft); }

.badge-cat { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.25rem 0.65rem; border-radius: 999px; background: var(--teal-soft); color: var(--teal2); font-size: 0.82rem; font-weight: 600; }
.amount { font-weight: 700; color: var(--night); font-family: 'Playfair Display', serif; font-size: 1.05rem; }
.amount .currency { font-size: 0.8rem; color: var(--gray); font-weight: 500; margin-left: 0.25rem; }
.payer { color: var(--ink); font-weight: 500; }
.creator { color: var(--gray); font-size: 0.85rem; }
.date { color: var(--ink); white-space: nowrap; }
.date small { display: block; color: var(--lgray); font-size: 0.78rem; }

.actions { display: flex; gap: 0.4rem; }
.btn-icon { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border); background: white; cursor: pointer; transition: all 0.15s; text-decoration: none; color: var(--ink); }
.btn-icon:hover { transform: translateY(-1px); }
.btn-icon.edit:hover { background: var(--teal-soft); border-color: var(--teal); color: var(--teal); }
.btn-icon.delete:hover { background: #FFE5DD; border-color: var(--danger); color: var(--danger); }

.empty { text-align: center; padding: 4rem 1rem; color: var(--gray); }
.empty .icon { font-size: 3rem; margin-bottom: 1rem; }
.empty h3 { font-family: 'Playfair Display', serif; color: var(--night); margin-bottom: 0.5rem; }

.alert-success { background: #DFF6E8; border-left: 4px solid var(--success); color: #1E5A3F; padding: 1rem 1.25rem; border-radius: 10px; margin-bottom: 1.5rem; font-weight: 500; }
.alert-error { background: #FBE3DC; border-left: 4px solid var(--danger); color: #7A2E14; padding: 1rem 1.25rem; border-radius: 10px; margin-bottom: 1.5rem; font-weight: 500; }

/* Pagination */
.pagination-wrap { padding: 1rem; display: flex; justify-content: center; }
.pagination-wrap .pagination { gap: 0.25rem; }

@media (max-width: 768px) {
    .filters form { grid-template-columns: 1fr 1fr; }
    .page-header h1 { font-size: 1.85rem; }
    table { font-size: 0.85rem; }
    th, td { padding: 0.6rem; }
}
</style>

<div class="expenses-page">

    {{-- Header --}}
    <div class="page-header">
        <div>
            <h1>Dépenses <em>opérationnelles</em></h1>
            <p class="subtitle">{{ $activeHostel?->name }} — Suivi complet des coûts</p>
        </div>
        <a href="{{ route($routes['create']) }}" class="btn-primary">
            ➕ Nouvelle dépense
        </a>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert-error">
            @foreach ($errors->all() as $error)
                <div>⚠️ {{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="label">Total dépenses</div>
            <div class="value">{{ $stats['total_count'] }}</div>
            <div class="hint">Enregistrées au total</div>
        </div>
        <div class="stat-card">
            <div class="label">Montant total (TND)</div>
            <div class="value terra">{{ number_format($stats['total_amount'], 3, ',', ' ') }}</div>
            <div class="hint">Toutes périodes confondues</div>
        </div>
        <div class="stat-card">
            <div class="label">Ce mois-ci (TND)</div>
            <div class="value teal">{{ number_format($stats['this_month'], 3, ',', ' ') }}</div>
            <div class="hint">{{ now()->locale('fr')->isoFormat('MMMM YYYY') }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Catégorie dominante</div>
            <div class="value">
                @php
                    $topCat = collect($stats['by_category'])->sortDesc()->keys()->first();
                    $topCatLabel = $topCat ? ($categories[$topCat] ?? $topCat) : '—';
                @endphp
                {{ $topCatLabel }}
            </div>
            <div class="hint">{{ $topCat && isset($stats['by_category'][$topCat]) ? number_format($stats['by_category'][$topCat], 3, ',', ' ') . ' TND' : 'Aucune donnée' }}</div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="filters">
        <form method="GET" action="{{ route($routes['index']) }}">
            <div>
                <label>Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Libellé, payeur, note...">
            </div>
            <div>
                <label>Catégorie</label>
                <select name="category">
                    <option value="">Toutes</option>
                    @foreach ($categories as $value => $label)
                        <option value="{{ $value }}" @selected(request('category') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Du</label>
                <input type="date" name="from" value="{{ request('from') }}">
            </div>
            <div>
                <label>Au</label>
                <input type="date" name="to" value="{{ request('to') }}">
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn-filter">Filtrer</button>
                @if (request()->hasAny(['search', 'category', 'from', 'to']))
                    <a href="{{ route($routes['index']) }}" class="btn-reset">Reset</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="expenses-table">
        @if ($expenses->count() === 0)
            <div class="empty">
                <div class="icon">💸</div>
                <h3>Aucune dépense pour le moment</h3>
                <p>Commencez par enregistrer votre première dépense.</p>
                <a href="{{ route($routes['create']) }}" class="btn-primary" style="margin-top: 1rem;">➕ Créer une dépense</a>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Catégorie</th>
                        <th>Libellé</th>
                        <th>Payé par</th>
                        <th>Montant</th>
                        <th>Créé par</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenses as $exp)
                        <tr>
                            <td class="date">
                                {{ $exp->expense_date->format('d/m/Y') }}
                                <small>{{ $exp->expense_date->locale('fr')->isoFormat('dddd') }}</small>
                            </td>
                            <td>
                                <span class="badge-cat">
                                    {{ $exp->category_emoji }} {{ $exp->category_label }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $exp->label }}</strong>
                                @if ($exp->note)
                                    <div style="color: var(--lgray); font-size: 0.82rem; margin-top: 0.2rem;">{{ Str::limit($exp->note, 60) }}</div>
                                @endif
                            </td>
                            <td class="payer">{{ $exp->payer_name }}</td>
                            <td class="amount">
                                {{ number_format((float) $exp->amount, 3, ',', ' ') }}
                                <span class="currency">{{ $exp->currency }}</span>
                            </td>
                            <td class="creator">{{ $exp->creator_label ?? '—' }}</td>
                            <td>
                                <div class="actions" style="justify-content: flex-end;">
                                    <a href="{{ route($routes['edit'], $exp->id) }}" class="btn-icon edit" title="Modifier">✏️</a>
                                    <button type="button" class="btn-icon delete" title="Supprimer"
                                            onclick="confirmDelete({{ $exp->id }}, '{{ addslashes($exp->label) }}')">🗑️</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination-wrap">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Formulaire caché pour la suppression --}}
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="password" id="delete-password">
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const PWD_CHECK_URL = "{{ route($routes['pwd']) }}";
const CSRF = "{{ csrf_token() }}";

function confirmDelete(id, label) {
    Swal.fire({
        title: 'Supprimer cette dépense ?',
        html: `<p style="color:#6B6B7A;">« <strong>${label}</strong> »<br>Cette action est irréversible.</p>`,
        icon: 'warning',
        input: 'password',
        inputLabel: 'Confirmez avec votre mot de passe',
        inputPlaceholder: 'Mot de passe',
        showCancelButton: true,
        confirmButtonText: 'Supprimer',
        cancelButtonText: 'Annuler',
        confirmButtonColor: '#A84E20',
        cancelButtonColor: '#6B6B7A',
        inputValidator: (value) => {
            if (!value) return 'Le mot de passe est requis.';
        },
        preConfirm: async (password) => {
            try {
                const res = await fetch(PWD_CHECK_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ password })
                });
                const data = await res.json();
                if (!data.success) {
                    Swal.showValidationMessage('Mot de passe incorrect.');
                    return false;
                }
                return password;
            } catch (e) {
                Swal.showValidationMessage('Erreur lors de la vérification.');
                return false;
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('delete-form');
            form.action = `{{ url('') }}/${getResourcePath()}/expenses/${id}`;
            document.getElementById('delete-password').value = result.value;
            form.submit();
        }
    });
}

// Récupère dynamiquement le préfixe owner/manager/staff depuis l'URL courante
function getResourcePath() {
    const path = window.location.pathname;
    if (path.startsWith('/manager/')) return 'manager';
    if (path.startsWith('/staff/')) return 'staff';
    return '';  // owner
}
</script>

@endsection