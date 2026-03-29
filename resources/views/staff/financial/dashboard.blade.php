@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Tableau de Bord Financier</h1>
            <p class="text-muted small">Suivi des revenus, dépenses et rentabilité de {{ $hostel->name }}</p>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary btn-sm"><i class="fas fa-file-export mr-1"></i> Exporter Rapport</button>
            <button class="btn btn-outline-danger btn-sm"><i class="fas fa-lock mr-1"></i> Clôturer Caisse</button>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Revenu Mensuel</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($monthlyRevenue, 2) }} TND</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Dépenses Mensuelles</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($monthlyExpenses, 2) }} TND</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Bénéfice Net</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($netProfit, 2) }} TND</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Lits</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBeds }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bed fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts / Lists placeholder -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Aperçu des Revenus</h6>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="fas fa-chart-line fa-4x text-gray-200 mb-3"></i>
                        <p class="text-muted">Graphique de performance (V2.1)</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Dernières Opérations</h6>
                </div>
                <div class="card-body px-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <small class="text-muted">Aucune opération récente ce mois-ci.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
