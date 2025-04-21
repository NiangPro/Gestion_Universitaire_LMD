<div class="container-fluid">
    <!-- En-tête avec les informations principales -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-primary-light">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="fa fa-graduation-cap fa-3x text-primary"></i>
                        </div>
                        <div>
                            <h2 class="mb-1">{{ $filiere->nom }}</h2>
                            <p class="mb-0">
                                <i class="fa fa-building-o"></i> 
                                Département: {{ $filiere->departement->nom }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes statistiques -->
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body mr-3">
                            <h2 class="text-primary">{{ $filiere->classes->count() }}</h2>
                            <span>Classes</span>
                        </div>
                        <i class="fa fa-users fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body mr-3">
                            <h2 class="text-success">{{ $filiere->uniteEnseignements->count() }}</h2>
                            <span>Unités d'Enseignement</span>
                        </div>
                        <i class="fa fa-book fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body mr-3">
                            <h2 class="text-warning">{{ $filiere->matieres->count() }}</h2>
                            <span>Matières</span>
                        </div>
                        <i class="fa fa-tasks fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des Classes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Classes</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Niveau</th>
                                    <th>Effectif</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($filiere->classes as $classe)
                                <tr>
                                    <td>{{ $classe->nom }}</td>
                                    <td>{{ $classe->niveau }}</td>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ $classe->etudiants_count ?? 0 }} étudiants
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

    <!-- Unités d'Enseignement -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Unités d'Enseignement</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($filiere->uniteEnseignements as $ue)
                        <div class="col-md-6 col-xl-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $ue->nom }}</h5>
                                    <p class="mb-2">
                                        <i class="fa fa-clock-o text-primary"></i> 
                                        {{ $ue->credits }} crédits
                                    </p>
                                    <p class="mb-0">
                                        <i class="fa fa-book text-info"></i>
                                        {{ $ue->matieres->count() }} matières
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Matières -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Matières</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Coefficient</th>
                                    <th>Volume Horaire</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($filiere->matieres as $matiere)
                                <tr>
                                    <td>{{ $matiere->nom }}</td>
                                    <td>
                                        <span class="badge badge-info">
                                            Coef. {{ $matiere->coefficient }}
                                        </span>
                                    </td>
                                    <td>{{ $matiere->volume_horaire }}h</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>