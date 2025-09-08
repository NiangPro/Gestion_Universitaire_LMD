@if(!Auth::user() || !Auth::user()->estEtudiant())
    <div class="alert alert-warning" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Vous devez être connecté en tant qu'étudiant pour voir vos retards.
    </div>
@elseif(!Auth::user()->inscriptions()->where('academic_year_id', Auth::user()->campus->currentAcademicYear()->id)->where('status', 'en_cours')->first())
    <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        Vous n'avez pas d'inscription active pour l'année académique en cours.
    </div>
@else
    <div class="container-fluid py-4">
        <style>
            .stat-card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                border: none;
                border-radius: 15px;
                overflow: hidden;
            }
            .stat-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 15px rgba(0,0,0,0.1);
            }
            .stat-icon {
                width: 48px;
                height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 12px;
                transition: all 0.3s ease;
            }
            .stat-icon i {
                font-size: 1.5rem;
                color: white;
            }
            .numbers h5 {
                font-size: 1.5rem;
                margin-top: 0.5rem;
            }
            .numbers p {
                color: #67748e;
                letter-spacing: 0.3px;
            }
            .filter-input {
                background-color: #f8f9fa !important;
                border: none !important;
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
                border-radius: 0.5rem !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            }
            .filter-input:focus {
                box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
            }
            .input-group-text {
                background-color: transparent !important;
                border: none !important;
                padding-right: 0;
            }
            .filter-box {
                margin-bottom: 0;
            }
        </style>

        <div class="row">
            <!-- Statistiques -->
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card stat-card">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Retards</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $retardStats['total'] }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="stat-icon bg-gradient-primary shadow">
                                    <i class="fas fa-clock text-dark" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card stat-card">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Justifiés</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $retardStats['justifies'] }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="stat-icon bg-gradient-success shadow">
                                    <i class="fas fa-check text-dark" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card stat-card">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Non Justifiés</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $retardStats['non_justifies'] }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="stat-icon bg-gradient-danger shadow">
                                    <i class="fas fa-times text-dark" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card stat-card">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Ce Mois</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $retardStats['mois_courant'] }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="stat-icon bg-gradient-warning shadow">
                                    <i class="fas fa-calendar text-dark" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des retards -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white pb-3 border-0">
                        <div class="row align-items-center">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <h5 class="mb-1 font-weight-bolder text-primary">Mes Retards</h5>
                                <p class="text-sm text-muted mb-0">Historique détaillé de vos retards</p>
                            </div>
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="date-box">
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-calendar text-primary"></i>
                                                </span>
                                                <input wire:model.live="date" type="date" class="form-control filter-input" placeholder="Filtrer par date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="filter-box">
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-filter text-primary"></i>
                                                </span>
                                                <select wire:model.live="justification" class="form-select filter-input">
                                                    <option value="">Tous les retards</option>
                                                    <option value="justifie">Justifiés</option>
                                                    <option value="non_justifie">Non justifiés</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        @if($retards->isEmpty())
                            <div class="alert alert-info mx-4 border-0 shadow-sm" role="alert">
                                <div class="d-flex align-items-center">
                                    <div class="alert-icon me-3">
                                        <i class="fas fa-info-circle fa-lg text-info"></i>
                                    </div>
                                    <div>
                                        Vous n'avez aucun retard enregistré pour l'année académique en cours.
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-9 px-3">Date</th>
                                            <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-9 ps-2">Cours</th>
                                            <th class="text-center text-uppercase text-primary text-xs font-weight-bolder opacity-9">Durée</th>
                                            <th class="text-center text-uppercase text-primary text-xs font-weight-bolder opacity-9">Statut</th>
                                            <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-9 ps-2">Motif</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($retards as $retard)
                                            <tr class="border-bottom">
                                                <td>
                                                    <div class="d-flex px-3 py-3">
                                                        <div class="icon-box me-3 bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-clock text-primary"></i>
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm font-weight-semibold">{{ optional($retard->date)->format('d/m/Y') ?? 'Date non spécifiée' }}</h6>
                                                            <p class="text-xs text-muted mb-0">{{ optional($retard->date)->format('H:i') ?? '--:--' }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bold mb-0 ps-2">{{ optional(optional($retard->cours)->matiere)->nom ?? 'Non spécifié' }}</p>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <div class="badge bg-primary text-white px-3 py-2">{{ $retard->minutes_retard ?? 0 }} min</div>
                                                </td>
                                                <td class="align-middle text-center">
                                                    @if($retard->justifie)
                                                        <div class="badge bg-success text-white px-3 py-2">Justifié</div>
                                                    @else
                                                        <div class="badge bg-danger text-white px-3 py-2">Non justifié</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <p class="text-sm text-muted mb-0 ps-2">{{ $retard->motif ?? 'Non spécifié' }}</p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-4 py-3">
                                {{ $retards->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif