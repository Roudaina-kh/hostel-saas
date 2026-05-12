@extends('layouts.app')
@section('title', 'Nouvelle dépense — ' . $activeHostel?->name)
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,500&family=DM+Sans:wght@400;500;600;700&display=swap');

:root {
    --sand: #F5EFE6; --sand2: #EDE3D4; --white: #FEFCF9;
    --terra: #C8602A; --terra2: #A84E20; --terra-soft: #FEF3E2;
    --teal: #1B6B6B; --teal2: #134F4F; --teal-soft: #E8F4F0;
    --night: #1C1C24; --ink: #2E2E3A; --gray: #6B6B7A; --lgray: #A0A0B0; --border: #DDD6CA;
    --danger: #A84E20;
}

.exp-form-page { font-family: 'DM Sans', sans-serif; background: var(--sand); min-height: 100vh; padding: 2rem; color: var(--ink); }
.exp-form-page * { box-sizing: border-box; }

.back-link { color: var(--gray); text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.35rem; margin-bottom: 1rem; transition: color 0.2s; }
.back-link:hover { color: var(--terra); }

.form-header h1 { font-family: 'Playfair Display', serif; font-size: 2.25rem; color: var(--night); margin: 0; }
.form-header h1 em { color: var(--terra); font-style: italic; }
.form-header p { color: var(--gray); margin-top: 0.25rem; }

.form-card { background: white; border: 1px solid var(--border); border-radius: 16px; padding: 2rem; margin-top: 1.5rem; box-shadow: 0 4px 14px rgba(0,0,0,0.03); max-width: 900px; }
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
.grid-3 { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 1.25rem; }
@media (max-width: 720px) {
    .grid-2, .grid-3 { grid-template-columns: 1fr; }
}

.field label { display: block; font-size: 0.82rem; color: var(--ink); font-weight: 600; margin-bottom: 0.45rem; text-transform: uppercase; letter-spacing: 0.4px; }
.field label .req { color: var(--danger); }
.field input, .field select, .field textarea {
    width: 100%; padding: 0.75rem 0.95rem; border: 1px solid var(--border); border-radius: 10px;
    font-family: inherit; font-size: 0.96rem; background: white; color: var(--ink); transition: all 0.15s;
}
.field input:focus, .field select:focus, .field textarea:focus {
    outline: none; border-color: var(--terra); box-shadow: 0 0 0 3px rgba(200, 96, 42, 0.12);
}
.field textarea { resize: vertical; min-height: 100px; }
.field .help { font-size: 0.78rem; color: var(--lgray); margin-top: 0.3rem; }

.actions-row { display: flex; gap: 0.75rem; margin-top: 1.75rem; padding-top: 1.5rem; border-top: 1px solid var(--border); justify-content: flex-end; }
.btn-primary { background: var(--terra); color: white; padding: 0.85rem 1.75rem; border-radius: 10px; border: none; font-weight: 700; cursor: pointer; transition: all 0.2s; font-size: 0.96rem; }
.btn-primary:hover { background: var(--terra2); transform: translateY(-1px); box-shadow: 0 6px 18px rgba(200, 96, 42, 0.3); }
.btn-cancel { background: transparent; color: var(--gray); padding: 0.85rem 1.5rem; border: 1px solid var(--border); border-radius: 10px; cursor: pointer; text-decoration: none; font-weight: 600; }
.btn-cancel:hover { background: var(--sand2); color: var(--ink); }

.alert-error { background: #FBE3DC; border-left: 4px solid var(--danger); color: #7A2E14; padding: 1rem 1.25rem; border-radius: 10px; margin-bottom: 1.5rem; }
.alert-error ul { margin: 0; padding-left: 1.25rem; }

.amount-row { display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; }
@media (max-width: 720px) { .amount-row { grid-template-columns: 1fr; } }
</style>

<div class="exp-form-page">
    <a href="{{ route($routes['index']) }}" class="back-link">← Retour aux dépenses</a>

    <div class="form-header">
        <h1>Nouvelle <em>dépense</em></h1>
        <p>{{ $activeHostel?->name }} — Enregistrer une dépense opérationnelle</p>
    </div>

    @if ($errors->any())
        <div class="alert-error" style="max-width: 900px;">
            <strong>⚠️ Erreur</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="expense-form" method="POST" action="{{ route($routes['store']) }}" class="form-card">
        @csrf

        <div class="grid-2">
            <div class="field">
                <label>Catégorie <span class="req">*</span></label>
                <select name="category" required>
                    <option value="">Sélectionner...</option>
                    @foreach ($categories as $value => $label)
                        <option value="{{ $value }}" @selected(old('category') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label>Date de la dépense <span class="req">*</span></label>
                <input type="date" name="expense_date" value="{{ old('expense_date', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" required>
            </div>
        </div>

        <div class="field" style="margin-top: 1.25rem;">
            <label>Libellé <span class="req">*</span></label>
            <input type="text" name="label" value="{{ old('label') }}" placeholder="Ex: Achat de produits de nettoyage" maxlength="255" required>
            <div class="help">Titre court qui décrit la dépense</div>
        </div>

        <div class="amount-row" style="margin-top: 1.25rem;">
            <div class="field">
                <label>Montant <span class="req">*</span></label>
                <input type="number" name="amount" step="0.001" min="0" value="{{ old('amount') }}" placeholder="0.000" required>
            </div>
            <div class="field">
                <label>Devise <span class="req">*</span></label>
                <select name="currency" required>
                    <option value="TND" @selected(old('currency', 'TND') === 'TND')>🇹🇳 TND</option>
                    <option value="EUR" @selected(old('currency') === 'EUR')>🇪🇺 EUR</option>
                    <option value="USD" @selected(old('currency') === 'USD')>🇺🇸 USD</option>
                </select>
            </div>
        </div>

        <div class="field" style="margin-top: 1.25rem;">
            <label>Payé par <span class="req">*</span></label>
            <input type="text" name="payer_name" value="{{ old('payer_name') }}" placeholder="Nom de la personne ayant réellement payé" maxlength="255" required>
            <div class="help">Ex: Ahmed Ben Salah, Caisse hostel, Marie (gérante)...</div>
        </div>

        <div class="field" style="margin-top: 1.25rem;">
            <label>Note (optionnel)</label>
            <textarea name="note" placeholder="Informations complémentaires, numéro de facture, fournisseur...">{{ old('note') }}</textarea>
        </div>

        <input type="hidden" name="password" id="password-hidden">

        <div class="actions-row">
            <a href="{{ route($routes['index']) }}" class="btn-cancel">Annuler</a>
            <button type="button" class="btn-primary" onclick="confirmAndSubmit()">💾 Enregistrer la dépense</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const PWD_CHECK_URL = "{{ route($routes['pwd']) }}";
const CSRF = "{{ csrf_token() }}";

function confirmAndSubmit() {
    const form = document.getElementById('expense-form');

    // HTML5 validation native
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    Swal.fire({
        title: 'Confirmer la dépense',
        text: 'Saisissez votre mot de passe pour valider l\'enregistrement.',
        icon: 'question',
        input: 'password',
        inputPlaceholder: 'Mot de passe',
        showCancelButton: true,
        confirmButtonText: 'Confirmer',
        cancelButtonText: 'Annuler',
        confirmButtonColor: '#C8602A',
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
                Swal.showValidationMessage('Erreur de vérification.');
                return false;
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('password-hidden').value = result.value;
            form.submit();
        }
    });
}
</script>

@endsection