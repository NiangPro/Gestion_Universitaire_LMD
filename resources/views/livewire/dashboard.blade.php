<div>
    <div class="container-fluid py-4">
        <!-- En-tête -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h1 class="h3 mb-2">Tableau de bord - {{ $user->estProfesseur() ? 'Professeur' : 'Élève' }}</h1>
                        <p class="text-muted mb-0">Bienvenue, {{ $user->prenom }} {{ $user->nom }}</p>
                        <small class="text-muted">Année académique : {{ $currentAcademicYear->debut }} - {{ $currentAcademicYear->fin }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="row">
            @if($user->estProfesseur())
                <!-- Stats Professeur -->
                <div class="col-md-4 mb-4">
                    <div class="card border-left-primary h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Mes Cours</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCours }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-book fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card border-left-success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Mes Élèves</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalEtudiants }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card border-left-info h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Notes à saisir</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalNotes ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Stats Élève -->
                <div class="col-md-3 mb-4">
                    <div class="card border-left-primary h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Cours suivis</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCours }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-book fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card border-left-danger h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Absences</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAbsences }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card border-left-warning h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Retards</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalRetards }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card border-left-success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Moyenne Générale</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $moyenneGenerale ?? 'N/A' }}/20</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Activités récentes -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Activités récentes</h5>
                    </div>
                    <div class="card-body">
                        @if(count($recentActivities) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Détails</th>
                                            <th>Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentActivities as $activity)
                                            <tr>
                                                <td>{{ $activity->created_at->format('d/m/Y') }}</td>
                                                <td>{{ $activity->type_evaluation }}</td>
                                                <td>
                                                    @if($user->estProfesseur())
                                                        {{ $activity->etudiant->nom }} {{ $activity->etudiant->prenom }}
                                                    @else
                                                        {{ $activity->cours->matiere->nom ?? 'N/A' }}
                                                    @endif
                                                </td>
                                                <td>{{ $activity->note }}/20</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center text-muted my-4">Aucune activité récente</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Actions rapides</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($user->estProfesseur())
                                <div class="col-md-4">
                                    <a href="{{ route('cours') }}" class="btn btn-primary btn-block mb-3">
                                        <i class="fas fa-book mr-2"></i> Gérer mes cours
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('note') }}" class="btn btn-success btn-block mb-3">
                                        <i class="fas fa-pen mr-2"></i> Saisir des notes
                                    </a>
                                </div>
                            @else
                                <div class="col-md-4">
                                    <button wire:click="toggleEmploiModal" class="btn btn-primary btn-block mb-3">
                                        <i class="fas fa-calendar-alt mr-2"></i> Emploi du temps
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <button wire:click="toggleNotesModal" class="btn btn-success btn-block mb-3">
                                        <i class="fas fa-chart-bar mr-2"></i> Mes notes
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @if($showEmploiModal)
    <div class="modal fade show" style="display: block" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Emploi du temps</h5>
                    <button type="button" class="close" wire:click="toggleEmploiModal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if($emploiDuTemps)
                        @foreach($emploiDuTemps as $jour => $cours)
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">{{ $jour }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Horaire</th>
                                                    <th>Matière</th>
                                                    <th>Professeur</th>
                                                    <th>Salle</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($cours as $cours)
                                                    <tr>
                                                        <td>{{ substr($cours->heure_debut, 0, 5) }} - {{ substr($cours->heure_fin, 0, 5) }}</td>
                                                        <td>{{ $cours->matiere->nom }}</td>
                                                        <td>{{ $cours->professeur->nom }} {{ $cours->professeur->prenom }}</td>
                                                        <td>{{ $cours->salle->nom }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center text-muted">Aucun cours programmé</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="toggleEmploiModal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($showNotesModal)
    <div class="modal fade show" style="display: block" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mes notes</h5>
                    <button type="button" class="close" wire:click="toggleNotesModal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if(count($moyennesParMatiere) > 0)
                        @foreach($moyennesParMatiere as $matiere => $data)
                            <div class="card mb-3">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">{{ $matiere }}</h6>
                                        <span class="badge badge-primary">Moyenne: {{ $data['moyenne'] }}/20</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Type</th>
                                                    <th>Note</th>
                                                    <th>Date</th>
                                                    <th>Observation</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data['notes'] as $note)
                                                    <tr>
                                                        <td>{{ $note->type_evaluation }}</td>
                                                        <td>{{ $note->note }}/20</td>
                                                        <td>{{ $note->date_evaluation->format('d/m/Y') }}</td>
                                                        <td>{{ $note->observation ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center text-muted">Aucune note disponible</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="toggleNotesModal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Backdrop -->
    @if($showEmploiModal || $showNotesModal)
    <div class="modal-backdrop fade show"></div>
    <style>
        body { overflow: hidden; padding-right: 17px; }
    </style>
    @endif
</div>