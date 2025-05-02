<div class="container-fluid py-4">
    <!-- En-tête avec statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="mb-0 text-sm font-weight-bold">Total des retards</p>
                                <h5 class="font-weight-bolder mb-0 text-white">
                                    {{ $totalRetards }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                <i class="fas fa-clock text-primary opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="mb-0 text-sm font-weight-bold">Retards justifiés</p>
                                <h5 class="font-weight-bolder mb-0 text-white">
                                    {{ $retardsJustifies }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                <i class="fas fa-check text-success opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card bg-danger text-white">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="mb-0 text-sm font-weight-bold">Retards non justifiés</p>
                                <h5 class="font-weight-bolder mb-0 text-white">
                                    {{ $retardsNonJustifies }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                <i class="fas fa-times text-danger opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="mb-0 text-sm font-weight-bold">Moyenne des retards</p>
                                <h5 class="font-weight-bolder mb-0 text-white">
                                    {{ number_format($moyenneRetardMinutes, 0) }} min
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                <i class="fas fa-chart-line text-info opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-header pb-0">
            <h6>Filtres</h6>
        </div>
        <div class="card-body pt-0 pb-3">
            <div class="row align-items-center">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Rechercher un cours</label>
                    <input wire:model.live="search" type="text" class="form-control" placeholder="Nom du cours...">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Semestre</label>
                    <select wire:model.live="selectedSemestre" class="form-select">
                        <option value="">Tous les semestres</option>
                        @foreach($semestres as $semestre)
                            <option value="{{ $semestre->id }}">{{ $semestre->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Date début</label>
                    <input wire:model.live="dateDebut" type="date" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Date fin</label>
                    <input wire:model.live="dateFin" type="date" class="form-control">
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des retards -->
    <div class="card">
        <div class="card-header pb-0">
            <h6>Mes retards</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Cours</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Durée</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Motif</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($retards as $retard)
                            <tr>
                                <td>
                                    <div class="d-flex px-3 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $retard->date->format('d/m/Y') }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $retard->date->format('H:i') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ $retard->cours->matiere->nom }}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <p class="text-sm font-weight-bold mb-0">{{ $retard->minutes_retard }} min</p>
                                </td>
                                <td class="align-middle text-center">
                                    @if($retard->justifie)
                                        <span class="badge badge-sm bg-success">Justifié</span>
                                    @else
                                        <span class="badge badge-sm bg-danger">Non justifié</span>
                                    @endif
                                </td>
                                <td>
                                    <p class="text-sm text-secondary mb-0">{{ $retard->motif ?: 'Non spécifié' }}</p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <p class="text-sm mb-0">Aucun retard enregistré</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
