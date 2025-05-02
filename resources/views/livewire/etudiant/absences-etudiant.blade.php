<div class="container-fluid">
    <!-- En-tête avec statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="card-data">
                        <h5>Total Absences</h5>
                        <h2 class="fs-40 font-w600">{{ $totalAbsences }}</h2>
                    </div>
                    <div class="icon-box bg-primary-light rounded-circle">
                        <i class="fa fa-calendar-xmark fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="card-data">
                        <h5>Absences Justifiées</h5>
                        <h2 class="fs-40 font-w600">{{ $absencesJustifiees }}</h2>
                    </div>
                    <div class="icon-box bg-success-light rounded-circle">
                        <i class="fa fa-check-circle fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="card-data">
                        <h5>Absences Non Justifiées</h5>
                        <h2 class="fs-40 font-w600">{{ $absencesNonJustifiees }}</h2>
                    </div>
                    <div class="icon-box bg-warning-light rounded-circle">
                        <i class="fa fa-exclamation-circle fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="card-data">
                        <h5>Taux d'Assiduité</h5>
                        <h2 class="fs-40 font-w600">{{ $tauxAssiduite }}%</h2>
                    </div>
                    <div class="icon-box bg-info-light rounded-circle">
                        <i class="fa fa-chart-line fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et Recherche -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Semestre</label>
                        <select wire:model.live="selectedSemestre" class="form-control">
                            <option value="">Tous les semestres</option>
                            @foreach($semestres as $semestre)
                                <option value="{{ $semestre->id }}">{{ $semestre->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Matière</label>
                        <select wire:model.live="selectedMatiere" class="form-control">
                            <option value="">Toutes les matières</option>
                            @foreach($matieres as $matiere)
                                <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">État</label>
                        <select wire:model.live="selectedEtat" class="form-control">
                            <option value="">Tous les états</option>
                            <option value="Justifié">Justifié</option>
                            <option value="Non justifié">Non justifié</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Période</label>
                        <input wire:model.live="selectedDate" type="date" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des absences -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Historique des Absences</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Date</th>
                            <th>Matière</th>
                            <th>Professeur</th>
                            <th>Durée</th>
                            <th>État</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absences as $absence)
                            <tr>
                                <td>{{ $absence->date->format('d/m/Y') }}</td>
                                <td>{{ $absence->cours->nom }}</td>
                                <td>{{ $absence->cours->professeur->name }}</td>
                                <td>{{ $absence->cours->duree }}h</td>
                                <td>
                                    <span class="badge badge-{{ $absence->justifie ? 'success' : 'danger' }}">
                                        {{ $absence->justifie ? 'Justifié' : 'Non justifié' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-toggle="tooltip" title="Voir détails">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    @if(!$absence->justifie)
                                        <button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Soumettre justificatif">
                                            <i class="fa fa-upload"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Aucune absence trouvée</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="dataTables_info">
                    Affichage de {{ $absences->firstItem() ?? 0 }} à {{ $absences->lastItem() ?? 0 }} sur {{ $absences->total() }} entrées
                </div>
                <div>
                    {{ $absences->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
