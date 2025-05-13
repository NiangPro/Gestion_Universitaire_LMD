<div class="container-fluid py-4">
    <style>
        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .stat-icon i {
            font-size: 1.5rem;
            color: white;
        }
        .numbers h5 {
            font-size: 1.5rem;
            margin-top: 0.5rem;
        }
        .numbers p {
            color: #67748e;
            letter-spacing: 0.3px;
        }
    </style>
    <div class="row">
        <!-- Statistiques -->
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card stat-card">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Retards</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $retardStats['total'] }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="stat-icon bg-gradient-primary shadow">
                                <i class="fas fa-clock text-dark" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card stat-card">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Justifiés</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $retardStats['justifies'] }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="stat-icon bg-gradient-success shadow">
                                <i class="fas fa-check text-dark" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card stat-card">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Non Justifiés</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $retardStats['non_justifies'] }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="stat-icon bg-gradient-danger shadow">
                                <i class="fas fa-times text-dark" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card stat-card">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Ce Mois</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $retardStats['mois_courant'] }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="stat-icon bg-gradient-warning shadow">
                                <i class="fas fa-calendar text-dark" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des retards -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 stat-card">
                <div class="card-header pb-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-0 font-weight-bolder">Mes Retards</h6>
                            <p class="text-sm text-secondary mb-0">Historique détaillé de vos retards</p>
                        </div>
                        <div class="col-md-6">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-primary"></i>
                                        </span>
                                        <input wire:model.live="search" type="text" class="form-control ps-0 border-start-0" placeholder="Rechercher un cours...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-calendar text-primary"></i>
                                        </span>
                                        <input wire:model.live="filterDate" type="date" class="form-control ps-0 border-start-0">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-filter text-primary"></i>
                                        </span>
                                        <select wire:model.live="filterJustifie" class="form-control ps-0 border-start-0">
                                            <option value="">Tous les retards</option>
                                            <option value="1">Justifiés</option>
                                            <option value="0">Non justifiés</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-primary text-xxs font-weight-bolder opacity-9 px-3">Date</th>
                                    <th class="text-uppercase text-primary text-xxs font-weight-bolder opacity-9 ps-2">Cours</th>
                                    <th class="text-center text-uppercase text-primary text-xxs font-weight-bolder opacity-9">Durée</th>
                                    <th class="text-center text-uppercase text-primary text-xxs font-weight-bolder opacity-9">Statut</th>
                                    <th class="text-uppercase text-primary text-xxs font-weight-bolder opacity-9 ps-2">Motif</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @forelse($retards as $retard)
                                <tr class="border-bottom">
                                    <td>
                                        <div class="d-flex px-3 py-2">
                                            <div class="icon-sm me-3 bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="fas fa-clock text-primary opacity-10"></i>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $retard->date->format('d/m/Y') }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $retard->date->format('H:i') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0 ps-2">{{ $retard->cours->matiere->nom }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-primary bg-opacity-10 text-primary">{{ $retard->minutes_retard }} min</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($retard->justifie)
                                            <span class="badge badge-sm bg-success bg-opacity-10 text-success">Justifié</span>
                                        @else
                                            <span class="badge badge-sm bg-danger bg-opacity-10 text-danger">Non justifié</span>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-sm text-secondary mb-0 ps-2">{{ $retard->motif ?: 'Non spécifié' }}</p>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-clock fa-3x text-secondary opacity-5 mb-3"></i>
                                            <p class="text-sm text-secondary mb-0">Aucun retard enregistré</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-3 py-3">
                        {{ $retards->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>