<div>
    <div class="container-fluid py-4">
        <!-- En-tête avec fond dégradé -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary text-white shadow-lg">
                    <div class="card-body py-4">
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <i class="fas fa-user-circle fa-3x"></i>
                            </div>
                            <div>
                                <h1 class="h3 mb-2 text-warning">Bienvenue, {{ $user->prenom }} {{ $user->nom }}</h1>
                                <p class="mb-0 opacity-8">Année académique : {{ date("d/m/Y", strtotime($currentAcademicYear->debut)) }} - {{ date("d/m/Y", strtotime($currentAcademicYear->fin)) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques avec design moderne -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100 py-2 border-0">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h6 font-weight-bold text-primary text-uppercase mb-2">Mes Cours</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h2 mb-0 mr-3 font-weight-bold text-gray-800">{{ $totalCours }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-primary text-white">
                                    <i class="fas fa-book"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100 py-2 border-0">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h6 font-weight-bold text-success text-uppercase mb-2">Mes Élèves</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h2 mb-0 mr-3 font-weight-bold text-gray-800">{{ $totalEtudiants }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-success text-white">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100 py-2 border-0">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h6 font-weight-bold text-info text-uppercase mb-2">Notes à saisir</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h2 mb-0 mr-3 font-weight-bold text-gray-800">{{ $totalNotes ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-info text-white">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100 py-2 border-0">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h6 font-weight-bold text-warning text-uppercase mb-2">Cours Aujourd'hui</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h2 mb-0 mr-3 font-weight-bold text-gray-800">{{ count($coursAujourdhui) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-warning text-white">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cours du jour et Activités récentes -->
        <div class="row">
            <!-- Cours du jour -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-calendar-day mr-2"></i>Cours du jour
                        </h6>
                        <span class="badge badge-primary">{{ now()->format('d/m/Y') }}</span>
                    </div>
                    <div class="card-body">
                        @if(count($coursAujourdhui) > 0)
                            <div class="timeline-wrapper">
                                @foreach($coursAujourdhui as $cours)
                                    <div class="timeline-item">
                                        <div class="timeline-badge bg-primary">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">{{ $cours->matiere->nom }}</h6>
                                                <small>{{ $cours->heure_debut }} - {{ $cours->heure_fin }}</small>
                                            </div>
                                            <p class="mb-1">{{ $cours->classe->nom }}</p>
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $cours->salle->nom }}
                                            </small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-xmark fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun cours prévu aujourd'hui</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Activités récentes -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow border-0">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-history mr-2"></i>Activités récentes
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @if(count($recentActivities) > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentActivities as $activity)
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $activity->etudiant->nom }} {{ $activity->etudiant->prenom }}</h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-graduation-cap mr-1"></i>{{ $activity->cours->matiere->nom }}
                                                </small>
                                            </div>
                                            <div class="text-right">
                                                <span class="badge badge-{{ $activity->note >= 10 ? 'success' : 'danger' }} badge-pill">
                                                    {{ $activity->note }}/20
                                                </span>
                                                <br>
                                                <small class="text-muted">{{ $activity->created_at->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-clipboard fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucune activité récente</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        

        <!-- Actions rapides -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow border-0">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-bolt mr-2"></i>Actions rapides
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('absenceprofesseur') }}" class="card action-card h-100">
                                    <div class="card-body text-center">
                                        <div class="action-icon bg-danger text-white mb-3">
                                            <i class="fas fa-user-clock"></i>
                                        </div>
                                        <h6 class="card-title mb-0">Gestion des absences</h6>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('noteprofesseur') }}" class="card action-card h-100">
                                    <div class="card-body text-center">
                                        <div class="action-icon bg-success text-white mb-3">
                                            <i class="fas fa-pen"></i>
                                        </div>
                                        <h6 class="card-title mb-0">Saisir des notes</h6>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="#" wire:click="toggleEmploiModal" class="card action-card h-100">
                                    <div class="card-body text-center">
                                        <div class="action-icon bg-info text-white mb-3">
                                            <i class="fas fa-calendar-week"></i>
                                        </div>
                                        <h6 class="card-title mb-0">Emploi du temps</h6>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('retardprofesseur') }}" class="card action-card h-100">
                                    <div class="card-body text-center">
                                        <div class="action-icon bg-warning text-white mb-3">
                                            <i class="fa fa-clock"></i>
                                        </div>
                                        <h6 class="card-title mb-0">Gestion des retards</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .icon-circle {
                height: 3rem;
                width: 3rem;
                border-radius: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .timeline-wrapper {
                position: relative;
                padding: 1rem;
            }
            
            .timeline-item {
                position: relative;
                padding-left: 3rem;
                margin-bottom: 1.5rem;
            }
            
            .timeline-badge {
                position: absolute;
                left: 0;
                width: 2rem;
                height: 2rem;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
            }
            
            .timeline-content {
                padding: 1rem;
                border-radius: .5rem;
                background-color: #f8f9fc;
            }

            .action-card {
                transition: all .3s ease;
                border: none;
                box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            }
            
            .action-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
            }
            
            .action-icon {
                width: 4rem;
                height: 4rem;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto;
                font-size: 1.5rem;
            }

            .bg-gradient-primary {
                background: linear-gradient(45deg, #4e73df 0%, #224abe 100%);
            }
        </style>
    </div>

</div>