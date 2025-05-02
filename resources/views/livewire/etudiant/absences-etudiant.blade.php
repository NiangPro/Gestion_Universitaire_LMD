<div class="container-fluid py-4">
    <!-- En-tête avec statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card bg-primary text-white shadow h-100">
                <div class="card-body p-3">
                    <div class="row no-gutters align-items-center">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="mb-1 text-sm font-weight-bold text-white-50">Total Absences</p>
                                <h4 class="mb-0 font-weight-bold text-white">{{ $totalAbsences }}</h4>
                            </div>
                        </div>
                        <div class="col-4 text-right">
                            <div class="icon icon-shape bg-white shadow text-center rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="fas fa-calendar-times text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card bg-success text-white shadow h-100">
                <div class="card-body p-3">
                    <div class="row no-gutters align-items-center">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="mb-1 text-sm font-weight-bold text-white-50">Absences Justifiées</p>
                                <h4 class="mb-0 font-weight-bold text-white">{{ $absencesJustifiees }}</h4>
                            </div>
                        </div>
                        <div class="col-4 text-right">
                            <div class="icon icon-shape bg-white shadow text-center rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card bg-warning text-white shadow h-100">
                <div class="card-body p-3">
                    <div class="row no-gutters align-items-center">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="mb-1 text-sm font-weight-bold text-white-50">Absences Non Justifiées</p>
                                <h4 class="mb-0 font-weight-bold text-white">{{ $absencesNonJustifiees }}</h4>
                            </div>
                        </div>
                        <div class="col-4 text-right">
                            <div class="icon icon-shape bg-white shadow text-center rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="fas fa-exclamation-circle text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card bg-info text-white shadow h-100">
                <div class="card-body p-3">
                    <div class="row no-gutters align-items-center">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="mb-1 text-sm font-weight-bold text-white-50">Taux d'Assiduité</p>
                                <h4 class="mb-0 font-weight-bold text-white">{{ $tauxAssiduite }}%</h4>
                            </div>
                        </div>
                        <div class="col-4 text-right">
                            <div class="icon icon-shape bg-white shadow text-center rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="fas fa-chart-line text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et Recherche -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 font-weight-bold text-primary">Filtres de recherche</h6>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="form-group mb-0">
                        <label class="form-label text-muted small mb-1">Semestre</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0">
                                    <i class="fas fa-graduation-cap text-primary"></i>
                                </span>
                            </div>
                            <select wire:model.live="selectedSemestre" class="form-control border-left-0 pl-0">
                                <option value="">Tous les semestres</option>
                                @foreach($semestres as $semestre)
                                    <option value="{{ $semestre->id }}">{{ $semestre->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="form-group mb-0">
                        <label class="form-label text-muted small mb-1">Matière</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0">
                                    <i class="fas fa-book text-primary"></i>
                                </span>
                            </div>
                            <select wire:model.live="selectedMatiere" class="form-control border-left-0 pl-0">
                                <option value="">Toutes les matières</option>
                                @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="form-group mb-0">
                        <label class="form-label text-muted small mb-1">État</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0">
                                    <i class="fas fa-filter text-primary"></i>
                                </span>
                            </div>
                            <select wire:model.live="selectedEtat" class="form-control border-left-0 pl-0">
                                <option value="">Tous les états</option>
                                <option value="Justifié">Justifié</option>
                                <option value="Non justifié">Non justifié</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label class="form-label text-muted small mb-1">Période</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0">
                                    <i class="fa fa-calendar text-primary"></i>
                                </span>
                            </div>
                            <input wire:model.live="selectedDate" type="date" class="form-control border-left-0">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des absences -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold text-primary">
                <i class="fa fa-history mr-2"></i>Historique des Absences
            </h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Matière</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Professeur</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Durée</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">État</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absences as $absence)
                            <tr>
                                <td class="align-middle text-sm">
                                    <p class="text-sm font-weight-bold mb-0">{{ $absence->date->format('d/m/Y') }}</p>
                                </td>
                                <td class="align-middle text-sm">
                                    <p class="text-sm font-weight-bold mb-0">{{ $absence->cours->nom }}</p>
                                </td>
                                <td class="align-middle text-sm">
                                    <p class="text-sm font-weight-bold mb-0">{{ $absence->cours->professeur->name }}</p>
                                </td>
                                <td class="align-middle text-sm">
                                    <p class="text-sm font-weight-bold mb-0">{{ $absence->cours->duree }}h</p>
                                </td>
                                <td class="align-middle">
                                    <span class="badge badge-sm bg-gradient-{{ $absence->justifie ? 'success' : 'danger' }} text-white">
                                        {{ $absence->justifie ? 'Justifié' : 'Non justifié' }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <button class="btn btn-link text-info btn-sm mb-0" data-toggle="tooltip" title="Voir détails">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    @if(!$absence->justifie)
                                        <button class="btn btn-link text-primary btn-sm mb-0" data-toggle="tooltip" title="Soumettre justificatif">
                                            <i class="fa fa-upload"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fa fa-inbox fa-2x mb-3 d-block"></i>
                                    Aucune absence trouvée
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="small text-muted">
                    Affichage de {{ $absences->firstItem() ?? 0 }} à {{ $absences->lastItem() ?? 0 }} sur {{ $absences->total() }} entrées
                </div>
                <div class="pagination pagination-sm mb-0">
                    {{ $absences->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
