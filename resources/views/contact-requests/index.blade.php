@extends('layouts.app')

@section('breadcrumb', 'Clients')
@section('page-title', 'Demandes clients')

@section('content')

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px">
    <div class="stat-card" style="border-left:4px solid #6366f1">
        <div class="stat-label">Total demandes</div>
        <div class="stat-value">{{ $stats['total'] }}</div>
    </div>
    <div class="stat-card" style="border-left:4px solid #ef4444">
        <div class="stat-label">🔴 Nouvelles</div>
        <div class="stat-value" style="color:#ef4444">{{ $stats['new'] }}</div>
    </div>
    <div class="stat-card" style="border-left:4px solid #f59e0b">
        <div class="stat-label">🟡 Lues</div>
        <div class="stat-value" style="color:#f59e0b">{{ $stats['read'] }}</div>
    </div>
    <div class="stat-card" style="border-left:4px solid #22c55e">
        <div class="stat-label">✅ Répondues</div>
        <div class="stat-value" style="color:#22c55e">{{ $stats['replied'] }}</div>
    </div>
</div>

<div class="table-card">
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Client</th>
                <th>Email</th>
                <th>Destination</th>
                <th>Arrivée</th>
                <th>Départ</th>
                <th>Voyageurs</th>
                <th>Statut</th>
                <th>Reçu le</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($requests as $req)
                <tr>
                    <td>{{ $req->id }}</td>
                    <td>
                        <div style="font-weight:600">{{ $req->full_name }}</div>
                        @if($req->phone)
                            <div style="font-size:11px;color:var(--color-text-secondary)">{{ $req->phone }}</div>
                        @endif
                    </td>
                    <td>
                        <a href="mailto:{{ $req->email }}" style="color:#534AB7">{{ $req->email }}</a>
                    </td>
                    <td><strong>{{ $req->destination }}</strong></td>
                    <td>{{ $req->arrival_date->format('d/m/Y') }}</td>
                    <td>{{ $req->departure_date->format('d/m/Y') }}</td>
                    <td style="text-align:center">{{ $req->travelers }}</td>
                    <td>
                        <span class="badge badge-{{ $req->status_color }}">
                            {{ $req->status_label }}
                        </span>
                    </td>
                    <td style="font-size:12px">{{ $req->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            @if($req->status === 'new')
                                <form method="POST" action="{{ route('contact-requests.mark-read', $req) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-icon" title="Marquer comme lu">👁</button>
                                </form>
                            @endif
                            @if($req->status !== 'replied')
                                <form method="POST" action="{{ route('contact-requests.mark-replied', $req) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-icon" title="Marquer comme répondu">✅</button>
                                </form>
                            @endif
                            @if($req->message)
                                <button class="btn-icon" title="Message"
                                        onclick="alert('{{ addslashes($req->message) }}')">💬</button>
                            @endif
                            <form method="POST" action="{{ route('contact-requests.destroy', $req) }}"
                                  onsubmit="return confirm('Supprimer cette demande ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon danger" title="Supprimer">🗑</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align:center;padding:32px;color:var(--color-text-secondary)">
                        Aucune demande client reçue pour l'instant.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:16px">{{ $requests->links() }}</div>

@endsection

@push('scripts')
<style>
.stat-card   { background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-lg);padding:16px }
.stat-label  { font-size:11px;color:var(--color-text-secondary);font-weight:500;margin-bottom:6px }
.stat-value  { font-size:22px;font-weight:700 }
.badge { display:inline-block;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:500 }
.badge-success { background:#dcfce7;color:#16a34a }
.badge-warning { background:#fef9c3;color:#ca8a04 }
.badge-danger  { background:#fee2e2;color:#dc2626 }
.table-card  { background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-lg);overflow:hidden }
.data-table  { width:100%;border-collapse:collapse;font-size:13px }
.data-table th { padding:10px 12px;text-align:left;font-size:11px;font-weight:600;color:var(--color-text-secondary);border-bottom:0.5px solid var(--color-border-tertiary);background:var(--color-background-secondary) }
.data-table td { padding:10px 12px;border-bottom:0.5px solid var(--color-border-tertiary) }
.data-table tr:last-child td { border-bottom:none }
.btn-icon { padding:4px 8px;border-radius:6px;border:0.5px solid var(--color-border-tertiary);background:var(--color-background-secondary);cursor:pointer;font-size:14px }
.btn-icon.danger { border-color:#fca5a5 }
</style>
@endpush