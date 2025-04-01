<div class="container-fluid">
    <div class="row page-titles mx-0">
        <div class="col-sm-6">
            <div class="welcome-text">
                <h4>{{ $selectedProfesseur->prenom }} {{ $selectedProfesseur->nom }}</h4>
                <span class="ml-1">Détails du professeur</span>
            </div>
        </div>
        <div class="col-sm-6 text-right">
            <button wire:click="changeStatus('list')" class="btn btn-warning">
                <i class="fa fa-arrow-left mr-2"></i>Retour
            </button>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card">
                <div class="card-body p-4">
                    <div class="media ai-icon">
                        <span class="mr-3 bgl-warning text-warning">
                            <i class="fa fa-book"></i>
                        </span>
                        <div class="media-body">
                            <p class="mb-1">Cours</p>
                            <h4 class="mb-0">{{ $stats['total_cours'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card">
                <div class="card-body p-4">
                    <div class="media ai-icon">
                        <span class="mr-3 bgl-success text-success">
                            <i class="fa fa-graduation-cap"></i>
                        </span>
                        <div class="media-body">
                            <p class="mb-1">Classes</p>
                            <h4 class="mb-0">{{ $stats['total_classes'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card">
                <div class="card-body p-4">
                    <div class="media ai-icon">
                        <span class="mr-3 bgl-danger text-danger">
                            <i class="fa fa-chart-bar"></i>
                        </span>
                        <div class="media-body">
                            <p class="mb-1">Notes saisies</p>
                            <h4 class="mb-0">{{ $stats['total_notes'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informations personnelles -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ asset('storage/'.$selectedProfesseur->image) }}" 
                             class="rounded-circle mb-3" width="100" alt="photo">
                        <h4>{{ $selectedProfesseur->prenom }} {{ $selectedProfesseur->nom }}</h4>
                        <p class="text-muted">{{ $selectedProfesseur->specialite }}</p>
                    </div>
                    <div class="profile-info">
                        <div class="d-flex mb-3">
                            <span class="mr-3"><i class="fa fa-envelope text-primary"></i></span>
                            <span>{{ $selectedProfesseur->email }}</span>
                        </div>
                        <div class="d-flex mb-3">
                            <span class="mr-3"><i class="fa fa-phone text-success"></i></span>
                            <span>{{ $selectedProfesseur->tel }}</span>
                        </div>
                        <div class="d-flex mb-3">
                            <span class="mr-3"><i class="fa fa-map-marker-alt text-danger"></i></span>
                            <span>{{ $selectedProfesseur->adresse }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cours et statistiques -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="profile-tab">
                        <div class="custom-tab-1">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a href="#cours" data-toggle="tab" class="nav-link active show">Cours</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#notes" data-toggle="tab" class="nav-link">Notes</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="cours" class="tab-pane fade active show">
                                    <div class="table-responsive mt-3">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Cours</th>
                                                    <th>Classe</th>
                                                    <th>Coefficient</th>
                                                    <th>Volume horaire</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($selectedProfesseur->cours as $cour)
                                                <tr>
                                                    <td>{{ $cour->matiere->nom ?? 'N/A' }}</td>
                                                    <td>{{ $cour->classe->nom ?? 'N/A' }}</td>
                                                    <td>{{ $cour->matiere->coefficient ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($cour->heure_debut && $cour->heure_fin)
                                                            {{ \Carbon\Carbon::parse($cour->heure_fin)->diffInHours($cour->heure_debut) }}h
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">
                                                        Aucun cours assigné pour cette année académique
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div id="notes" class="tab-pane fade">
                                    <div class="table-responsive mt-3">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Cours</th>
                                                    <th>Classe</th>
                                                    <th>Notes saisies</th>
                                                    <th>Moyenne classe</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($selectedProfesseur->cours as $cours)
                                                @php
                                                    $notesCount = $cours->notes->count();
                                                    $notesAvg = $notesCount > 0 ? $cours->notes->avg('note') : 0;
                                                @endphp
                                                <tr>
                                                    <td>{{ optional($cours->matiere)->nom ?? 'N/A' }}</td>
                                                    <td>{{ optional($cours->classe)->nom ?? 'N/A' }}</td>
                                                    <td>{{ $notesCount }}</td>
                                                    <td>{{ number_format($notesAvg, 2) }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">
                                                        Aucune note saisie pour cette année académique
                                                    </td>
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
            </div>
        </div>
    </div>
</div> 