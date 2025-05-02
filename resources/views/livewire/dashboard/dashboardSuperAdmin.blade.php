<div>
    <div class="container-fluid">
        <!-- En-tête avec statistiques principales -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Campus</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCampus ?? 0 }}</div>
                                <div class="text-xs text-gray-600 mt-1">Campus actifs</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-primary text-white">
                                    <i class="fas fa-university"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Revenus Mensuels</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($revenusMensuels ?? 0, 0, ',', ' ') }} FCFA</div>
                                <div class="text-xs text-gray-600 mt-1">Abonnements actifs</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-success text-white">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Packs Actifs</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPacks ?? 0 }}</div>
                                <div class="text-xs text-gray-600 mt-1">Types de packs disponibles</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-info text-white">
                                    <i class="fas fa-cube"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Utilisateurs Totaux</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUtilisateurs ?? 0 }}</div>
                                <div class="text-xs text-gray-600 mt-1">Tous campus confondus</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-warning text-white">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques et statistiques détaillées -->
        <div class="row">
            <!-- Statistiques des packs -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Distribution des Packs par Campus</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area" style="height: 300px;">
                            <!-- Ici viendra le graphique -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activité récente -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Activités Récentes</h6>
                    </div>
                    <div class="card-body">
                        <div class="activity-feed">
                            @forelse($activitesRecentes ?? [] as $activite)
                                <div class="feed-item pb-3">
                                    <div class="text-gray-600 small">{{ $activite->created_at->diffForHumans() }}</div>
                                    <div>{{ $activite->description }}</div>
                                </div>
                            @empty
                                <div class="text-center text-gray-500">Aucune activité récente</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des campus et leurs abonnements -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">État des Campus</h6>
                        <button class="btn btn-primary btn-sm" wire:click="ajouterCampus">
                            <i class="fas fa-plus fa-sm"></i> Nouveau Campus
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Campus</th>
                                        <th>Pack</th>
                                        <th>Utilisateurs</th>
                                        <th>État</th>
                                        <th>Expiration</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($campusList ?? [] as $campus)
                                        <tr>
                                            <td>{{ $campus->nom }}</td>
                                            <td>{{ $campus->pack->nom }}</td>
                                            <td>{{ $campus->total_users }}</td>
                                            <td>
                                                <span class="badge badge-{{ $campus->statut === 'actif' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($campus->statut) }}
                                                </span>
                                            </td>
                                            <td>{{ $campus->date_expiration->format('d/m/Y') }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-info mr-1" wire:click="editerCampus({{ $campus->id }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" wire:click="renouvelerAbonnement({{ $campus->id }})">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Aucun campus enregistré</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .icon-circle {
            height: 2.5rem;
            width: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .activity-feed .feed-item {
            position: relative;
            padding-left: 30px;
            margin-bottom: 15px;
            border-left: 2px solid #e3e6f0;
        }
        .activity-feed .feed-item:last-child {
            margin-bottom: 0;
        }
    </style>
</div>