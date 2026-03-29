@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Gestion de la Caisse</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if(!$currentShift)
                        <div class="text-center py-4">
                            <i class="fas fa-cash-register fa-4x text-gray-200 mb-3"></i>
                            <h4>Ouvrir une nouvelle session</h4>
                            <p class="text-muted">Veuillez saisir le montant initial en caisse pour commencer.</p>
                            
                            <form action="{{ route('staff.cash-shifts.open') }}" method="POST" class="mt-4">
                                @csrf
                                <div class="form-group mx-auto" style="max-width: 300px;">
                                    <label>Solde d'ouverture (TND)</label>
                                    <input type="number" name="opening_balance" step="0.01" class="form-control text-center h4" placeholder="0.00" required>
                                </div>
                                <button type="submit" class="btn btn-success btn-lg px-5 mt-3">Ouvrir la Caisse</button>
                            </form>
                        </div>
                    @else
                        <div class="py-4">
                            <div class="alert alert-success d-flex align-items-center">
                                <i class="fas fa-check-circle mr-3 fa-2x"></i>
                                <div>
                                    <strong>Caisse Ouverte</strong><br>
                                    Ouverte le {{ $currentShift->opened_at->format('d/m/Y à H:i') }} par {{ Auth::guard('staff')->user()->name }}
                                </div>
                            </div>

                            <hr>

                            <div class="row text-center my-4">
                                <div class="col-6 border-right">
                                    <h6 class="text-uppercase text-muted small">Solde d'ouverture</h6>
                                    <h3 class="font-weight-bold text-gray-800">{{ number_format($currentShift->opening_balance, 2) }} TND</h3>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-uppercase text-muted small">Ventes (Shift)</h6>
                                    <h3 class="font-weight-bold text-primary">0.00 TND</h3>
                                </div>
                            </div>

                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h5 class="mb-3">Clôturer la Caisse</h5>
                                    <form action="{{ route('staff.cash-shifts.close', $currentShift->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label>Solde de clôture (Compté physiquement)</label>
                                            <input type="number" name="closing_balance" step="0.01" class="form-control h4" placeholder="0.00" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Notes / Remarques</label>
                                            <textarea name="notes" class="form-control" rows="2"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-danger btn-block btn-lg">Valider la Clôture</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
