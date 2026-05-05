@extends('super-admin.layout')
@section('breadcrumb', 'Propriétaires › Créer')
@section('page-title', 'Créer un propriétaire')

@section('content')

<div style="max-width:560px">
    <div class="sa-card">
        <div class="sa-card-title">👤 Nouveau compte propriétaire</div>
        <p style="font-size:13px;color:#64748B;margin-bottom:24px;padding:12px;background:#F8FAFC;border-radius:10px;border-left:3px solid #7C3AED">
            🔒 Le mot de passe généré doit être transmis au propriétaire par un canal sécurisé (email chiffré, message privé). Il pourra le changer après sa première connexion.
        </p>

        @if($errors->any())
        <div style="background:#FEE2E2;border:1px solid #FECACA;border-radius:10px;padding:12px;margin-bottom:20px">
            @foreach($errors->all() as $error)
                <div style="font-size:13px;color:#DC2626;font-weight:500">→ {{ $error }}</div>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('super-admin.owners.store') }}">
            @csrf

            <div class="form-grid">
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Nom complet *</label>
                    <input class="form-input" type="text" name="name"
                           value="{{ old('name') }}" placeholder="Jean Dupont" required>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Email *</label>
                    <input class="form-input" type="email" name="email"
                           value="{{ old('email') }}" placeholder="jean@hostel.com" required>
                    @error('email') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Téléphone</label>
                    <input class="form-input" type="tel" name="phone"
                           value="{{ old('phone') }}" placeholder="+216 XX XXX XXX">
                </div>
                <div class="form-group">
                    <label class="form-label">Mot de passe * <span style="font-size:10px;color:#94A3B8;font-weight:400">(min. 8 car., lettres+chiffres)</span></label>
                    <input class="form-input" type="password" name="password"
                           placeholder="••••••••" required autocomplete="new-password">
                    @error('password') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Confirmer le mot de passe *</label>
                    <input class="form-input" type="password" name="password_confirmation"
                           placeholder="••••••••" required autocomplete="new-password">
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary">✅ Créer le compte</button>
                <a href="{{ route('super-admin.owners.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

@endsection