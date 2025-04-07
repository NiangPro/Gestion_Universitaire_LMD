<div class="row">
    <!-- En-tête avec les informations principales -->
    <div class="col-12 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">{{ $nom }}</h2>
                        <p class="mb-0 opacity-75">{{ $description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte du responsable -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    @if($departement->responsable)
                        <div class="mx-auto rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mb-3"
                             style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ strtoupper(substr($departement->responsable->prenom, 0, 1)) }}
                        </div>
                        <h4>{{ $departement->responsable->prenom }} {{ $departement->responsable->nom }}</h4>
                        <p class="text-muted mb-3">Responsable du département</p>
                        <div class="d-flex justify-content-center gap-2">
                            @if($departement->responsable->email)
                                <span class="badge bg-info">
                                    <i class="fa fa-envelope me-1"></i>{{ $departement->responsable->email }}
                                </span>
                            @endif
                            @if($departement->responsable->telephone)
                                <span class="badge bg-success">
                                    <i class="fa fa-phone me-1"></i>{{ $departement->responsable->telephone }}
                                </span>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            Aucun responsable assigné
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="col-md-8 mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card bg-info text-white h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-white text-info p-3 me-3">
                                <i class="fa fa-graduation-cap fa-2x"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">{{ $departement->filieres->count() }}</h3>
                                <p class="mb-0">Filières</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-white text-success p-3 me-3">
                                <i class="fa fa-users fa-2x"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">{{ $professeurs->count() }}</h3>
                                <p class="mb-0">Professeurs</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-white text-warning p-3 me-3">
                                <i class="fa fa-book fa-2x"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">{{ $departement->classes->count() }}</h3>
                                <p class="mb-0">Classes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des filières -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fa fa-sitemap text-primary me-2"></i>
                    Filières
                </h5>
                <span class="badge bg-primary">{{ $departement->filieres->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($departement->filieres->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($departement->filieres as $filiere)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $filiere->nom }}</h6>
                                        <small class="text-muted">
                                            {{ $filiere->classes->count() }} classes
                                        </small>
                                    </div>
                                    <span class="badge bg-info rounded-pill">
                                        {{ $filiere->classes->count() }} classes
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fa fa-folder-open text-muted fa-3x mb-3"></i>
                        <p class="text-muted mb-0">Aucune filière dans ce département</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Liste des professeurs -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fa fa-chalkboard-teacher text-primary me-2"></i>
                    Professeurs
                </h5>
                <span class="badge bg-primary">{{ $professeurs->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($professeurs->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($professeurs as $prof)
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white p-3 me-3"
                                         style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                        {{ strtoupper(substr($prof->prenom, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $prof->prenom }} {{ $prof->nom }}</h6>
                                        <div class="d-flex gap-2">
                                            @if($prof->email)
                                                <small class="text-muted">
                                                    <i class="fa fa-envelope me-1"></i>
                                                    {{ $prof->email }}
                                                </small>
                                            @endif
                                            @if($prof->telephone)
                                                <small class="text-muted">
                                                    <i class="fa fa-phone me-1"></i>
                                                    {{ $prof->telephone }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fa fa-users text-muted fa-3x mb-3"></i>
                        <p class="text-muted mb-0">Aucun professeur assigné</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>