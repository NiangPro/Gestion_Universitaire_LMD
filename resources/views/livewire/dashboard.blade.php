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
                                <a href="{{ route('emploisdutemps') }}" class="btn btn-primary btn-block mb-3">
                                    <i class="fas fa-calendar-alt mr-2"></i> Emploi du temps
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('note') }}" class="btn btn-success btn-block mb-3">
                                    <i class="fas fa-chart-bar mr-2"></i> Mes notes
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>