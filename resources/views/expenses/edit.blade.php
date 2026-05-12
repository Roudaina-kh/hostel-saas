@extends('layouts.app')
@section('title', 'Modifier dépense — ' . $activeHostel?->name)
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,500&family=DM+Sans:wght@400;500;600;700&display=swap');

:root {
    --sand: #F5EFE6; --sand2: #EDE3D4;
    --terra: #C8602A; --terra2: #A84E20;
    --teal: #1B6B6B; --teal2: #134F4F; --teal-soft: #E8F4F0;
    --night: #1C1C24; --ink: #2E2E3A; --gray: #6B6B7A; --lgray: #A0A0B0; --border: #DDD6CA;
    --danger: #A84E20;
}

.exp-form-page { font-family: 'DM Sans', sans-serif; background: var(--sand); min-height: 100vh; padding: 2rem; color: var(--ink); }
.exp-form-page * { box-sizing: border-box; }

.back-link { color: var(--gray); text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.35rem; margin-bottom: 1rem; }
.back-link:hover { color: var(--terra); }

.form-header h1 { font-family: 'Playfair Display', serif; font-size: 2.25rem; color: var(--night); margin: 0; }
.form-header h1 em { color: var(--teal); font-style: italic; }
.form-header p { color: var(--gray); margin-top: 0.25rem; }

.meta-bar { display: flex; gap: 1.5rem; margin-top: 0.75rem; flex-wrap: wrap; }
.meta-bar .meta-item { color: var(--gray); font-size: 0.85rem; }
.meta-bar .meta-item strong { color: var(--ink); }

.form-card { background: white; border: 1px solid var(--border); border-radius: 16px; padding: 2rem; margin-top: 1.5rem; box-shadow: 0 4px 14px rgba(0,0,0,0.03); max-width: 900px; }
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
@media (max-width: 720px) { .grid-2 { grid-template-columns: 1fr; } }

.field label { display: block; font-size: 0.82rem; color: var(--ink); font-weight: 600; margin-bottom: 0.45rem; text-transform: uppercase; letter-spacing: 0.4px; }
.field label .req { color: var(--danger); }
.field input, .field select, .field textarea {
    width: 100%; padding: 0.75rem 0.95rem; border: 1px solid var(--border); border-radius: 10px;
    font-family: inherit; font-size: 0.96rem; background: white; color: var(--ink);
}
.field input:focus, .field select:focus, .field textarea:focus {
    outline: none; border-color: var(--teal); box-shadow: 0 0 0 3px rgba(27, 107, 107, 0.12);
}
.field textarea { resize: vertical; min-height: 100px; }
.field .help { font-size: 0.78rem; color: var(--lgray); margin-top: 0.3rem; }

.amount-row { display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; }
@media (max-width: 720px) { .amount-row { grid-template-columns: 1fr; } }

.actions-row { display: flex; gap: 0.75rem; margin-top: 1.75rem; padding-top: 1.5rem; border-top: 1px solid var(--border); justify-content: flex-end; }
.btn-primary { background: var(--teal); color: white; padding: 0.85rem 1.75rem; border-radius: 10px; border: none; font-weight: 700; cursor: pointer; }
.btn-primary:hover { background: var(--teal2); transform: translateY(-1px); }
.btn-cancel { background: transparent; color: var(--gray); padding: 0.85rem 1.5rem; border: 1px solid var(--border); border-radius: 10px; cursor: pointer; text-decoration: none; font-weight: 600; }

.alert-error { background: #FBE3DC; border-left: 4px solid var(--danger); color: #7A2E14; padding: 1rem 1.25rem; border-radius: 10px; margin-bottom: 1.5rem; }
</style>

<div class="exp-form-page">
    <a href="{{ route($routes['index']) }}" class="back-link">← Retour aux dépenses</a>

    <div class="form-header">
        <h1>Modifier <em>dépense #{{ $expense->id }}</em></h1>
        <p>{{ $activeHostel?->name }}</p>
        <div class="meta-bar">
            <div class="meta-item">Créée le <strong>{{ $expense->created_at->format('d/m/Y H:i') }}</strong></div>
            <div class="meta-item">Par <strong>{{ $expense->creator_label ?? '—' }}</strong></div>
            @if ($expense->updated_at->gt($expense->created_at))
                <div class="meta-item">Dernière modification : <strong>{{ $expense->updated_at->format('d/m/Y H:i') }}</strong></div>
            @endif
        </div>
    </div>

    @if ($errors->any())
        <div class="alert-error" style="max-width: 900px; margin-top: 1.5rem;">
            <strong>⚠️ Erreur</strong>
            <ul style="margin: 0.5rem 0 0; padding-left: 1.25rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="expense-form" method="POST" action="{{ route($routes['update'], $expense->id) }}" class="form-card">
        @csrf
        @method('PUT')

        <div class="grid-2">
            <div class="field">
                <label>Catégorie <span class="req">*</span></label>
                <select name="category" required>
                    @foreach ($categories as $value => $label)
                        <option value="{{ $value }}" @selected(old('category', $expense->category) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label>Date <span class="req">*</span></label>
                <input type="date" name="expense_date"
                       value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}"
                       max="{{ now()->format('Y-m-d') }}" required>
            </div>
        </div>

        <div class="field" style="margin-top: 1.25rem;">
            <label>Libellé <span class="req">*</span></label>
            <input type="text" name="label" value="{{ old('label', $expense->label) }}" maxlength="255" required>
        </div>

        <div class="amount-row" style="margin-top: 1.25rem;">
            <div class="field">
                <label>Montant <span class="req">*</span></label>
                <input type="number" name="amount" step="0.001" min="0"
                       value="{{ old('amount', $expense->amount) }}" required>
            </div>
            <div class="field">
                <label>Devise <span class="req">*</span></label>
                <select name="currency" required>
                    <option value="TND" @selected(old('currency', $expense->currency) === 'TND')>🇹🇳 TND</option>
                    <option value="EUR" @selected(old('currency', $expense->currency) === 'EUR')>🇪🇺 EUR</option>
                    <option value="USD" @selected(old('currency', $expense->currency) === 'USD')>🇺🇸 USD</option>
                </select>
            </div>
        </div>

        <div class="field" style="margin-top: 1.25rem;">
            <label>Payé par <span class="req">*</span></label>
            <input type="text" name="payer_name" value="{{ old('payer_name', $expense->payer_name) }}" maxlength="255" required>
        </div>

        <div class="field" style="margin-top: 1.25rem;">
            <label>Note (optionnel)</label>
            <textarea name="note">{{ old('note', $expense->note) }}</textarea>
        </div>

        <input type="hidden" name="password" id="password-hidden">

        <div class="actions-row">
            <a href="{{ route($routes['index']) }}" class="btn-cancel">Annuler</a>
            <button type="button" class="btn-primary" onclick="confirmAndSubmit()">💾 Mettre à jour</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const PWD_CHECK_URL = "{{ route($routes['pwd']) }}";
const CSRF = "{{ csrf_token() }}";

function confirmAndSubmit() {
    const form = document.getElementById('expense-form');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    Swal.fire({
        title: 'Confirmer la modification',
        text: 'Saisissez votre mot de passe pour valider.',
        icon: 'question',
        input: 'password',
        inputPlaceholder: 'Mot de passe',
        showCancelButton: true,
        confirmButtonText: 'Confirmer',
        cancelButtonText: 'Annuler',
        confirmButtonColor: '#1B6B6B',
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