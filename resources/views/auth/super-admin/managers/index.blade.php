@extends('super-admin.layout')
@section('breadcrumb', 'Managers')
@section('page-title', 'Gestion des managers')

@section('content')

<div style="margin-bottom:20px">
    <p style="font-size:13px;color:#64748B;padding:12px;background:#FEF9C3;border-radius:10px;border-left:3px solid #F59E0B">
        ⚠️ Bloquer un manager l'empêche de se connecter sur <strong>toute la plateforme</strong>. L'owner peut créer un nouveau compte depuis son panel.
    </p>
</div>

<div class="sa-table-wrap">
    <table class="sa-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Manager</th>
                <th>Email</th>
                <th>Hostel(s)</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($managers as $manager)
            <tr>
                <td style="color:#94A3B8;font-size:12px">{{ $manager->id }}</td>
                <td style="font-weight:600">{{ $manager->name }}</td>
                <td style="color:#64748B;font-size:12px">{{ $manager->email }}</td>
                <td>
                    @foreach($manager->hostels as $h)
                        <span class="badge badge-blue">{{ $h->name }}</span>
                    @endforeach
                </td>
                <td>
                    @if($manager->is_active)
                        <span class="badge badge-active">✅ Actif</span>
                    @else
                        <span class="badge badge-inactive">🚫 Bloqué</span>
                    @endif
                </td>
                <td>
                    <form method="POST" action="{{ route('super-admin.managers.toggle', $manager) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                                class="btn btn-sm {{ $manager->is_active ? 'btn-warning' : 'btn-secondary' }}"
                                onclick="return confirm('{{ $manager->is_active ? 'Bloquer' : 'Débloquer' }} ce manager ?')">
                            {{ $manager->is_active ? '🚫 Bloquer' : '✅ Débloquer' }}
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:40px;color:#94A3B8">
                    Aucun manager sur la plateforme.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:16px">{{ $managers->links() }}</div>

@endsection