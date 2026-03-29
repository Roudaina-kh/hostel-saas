@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Rapports Financiers</h1>
    </div>

    <div class="row">
        <!-- Daily Report Card -->
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Rapport Journalier</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">Générez un rapport détaillé des transactions pour une date spécifique.</p>
                    <form action="{{ route('staff.financial.reports.generate') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="daily">
                        <div class="form-group">
                            <label>Choisir la date</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Générer PDF</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Monthly Report Card -->
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Rapport Mensuel</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">Résumé financier consolidé pour un mois complet.</p>
                    <form action="{{ route('staff.financial.reports.generate') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="monthly">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Mois</label>
                                    <select name="month" class="form-control">
                                        @foreach(range(1,12) as $m)
                                            <option value="{{ $m }}" {{ date('m') == $m ? 'selected' : '' }}>
                                                {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Année</label>
                                    <select name="year" class="form-control">
                                        @foreach(range(date('Y')-2, date('Y')) as $y)
                                            <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Générer Rapport Excel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- History list placeholder -->
    <div class="card shadow mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Historique des Rapports Générés</h6>
        </div>
        <div class="card-body text-center py-5">
            <i class="fas fa-history fa-3x text-gray-200 mb-3"></i>
            <p class="text-muted">Aucun rapport généré récemment.</p>
        </div>
    </div>
</div>
@endsection
