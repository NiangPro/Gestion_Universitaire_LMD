<div>
    <div class="container-fluid py-4">
        <!-- En-tête -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h1 class="h3 mb-2">Tableau de bord Administrateur</h1>
                        <p class="text-muted mb-0">Bienvenue, {{ $user->prenom }} {{ $user->nom }}</p>
                        <small class="text-muted">Année académique : {{ date("d/m/Y", strtotime($currentAcademicYear->debut)) }} - {{ date("d/m/Y", strtotime($currentAcademicYear->fin)) }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques Principales -->
        <div class="row">
            <!-- Étudiants -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Étudiants</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalEtudiants }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professeurs -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Professeurs</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProfesseurs }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Classes -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Classes</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalClasses }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-door-open fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cours -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Cours</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCours }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-book fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques Financières -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Statistiques des Inscriptions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Total des inscriptions :</span>
                                    <strong>{{ $totalInscriptions }}</strong>
                                </div>
                                <div class="progress mb-3" style="height: 20px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Montant total :</span>
                                    <strong>{{ number_format($montantTotal, 0, ',', ' ') }} FCFA</strong>
                                </div>
                                <div class="progress mb-3" style="height: 20px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inscriptions Récentes -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Inscriptions Récentes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Étudiant</th>
                                        <th>Classe</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inscriptionsRecentes as $inscription)
                                        <tr>
                                            <td>{{ $inscription->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $inscription->etudiant->nom }} {{ $inscription->etudiant->prenom }}</td>
                                            <td>{{ $inscription->classe->nom }}</td>
                                            <td>{{ number_format($inscription->montant, 0, ',', ' ') }} FCFA</td>
                                            <td>
                                                <span class="badge badge-{{ $inscription->status === 'validé' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($inscription->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Rapides -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Actions Rapides</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="{{ route('etudiant') }}" class="btn btn-primary btn-block mb-3">
                                    <i class="fas fa-user-graduate mr-2"></i> Gestion des Étudiants
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('professeur') }}" class="btn btn-success btn-block mb-3">
                                    <i class="fas fa-chalkboard-teacher mr-2"></i> Gestion des Professeurs
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('classe') }}" class="btn btn-info btn-block mb-3">
                                    <i class="fas fa-door-open mr-2"></i> Gestion des Classes
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('cours') }}" class="btn btn-warning btn-block mb-3">
                                    <i class="fas fa-book mr-2"></i> Gestion des Cours
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>