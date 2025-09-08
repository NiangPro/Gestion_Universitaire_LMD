<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2 text-primary"></i>Emploi du temps - {{ $currentAcademicYear ? date('Y', strtotime($currentAcademicYear->debut)) . '-' . date('Y', strtotime($currentAcademicYear->fin)) : '' }}
                    </h5>
                    <div class="d-flex align-items-center">
                        <div class="form-group mb-0 me-3">
                            <select wire:model.live="selectedSemaine" class="form-control">
                                <option value="">Tous les jours</option>
                                @foreach($semaines as $semaine)
                                    <option value="{{ $semaine->id }}">{{ $semaine->jour }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-sm btn-primary" onclick="window.print()">
                            <i class="fas fa-print me-1"></i> Imprimer
                        </button>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if(!$currentClasse)
                        <div class="alert alert-warning mx-4 mt-4">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                                </div>
                                <div>
                                    <h5 class="text-warning">Aucune classe trouvée</h5>
                                    <p>Vous n'êtes pas inscrit dans une classe pour l'année académique en cours.</p>
                                </div>
                            </div>
                        </div>
                    @elseif(empty($emploiDuTemps))
                        <div class="alert alert-info mx-4 mt-4">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-info-circle fa-2x text-info"></i>
                                </div>
                                <div>
                                    <h5 class="text-info">Aucun cours programmé</h5>
                                    <p>Il n'y a pas de cours programmés pour votre classe {{ $currentClasse->nom }} pour le moment.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="table-responsive p-0">
                            <style>
                                .schedule-table {
                                    width: 100%;
                                    border-collapse: separate;
                                    border-spacing: 0;
                                }
                                .schedule-table th {
                                    background: linear-gradient(310deg, #2152ff 0%, #21d4fd 100%);
                                    color: white;
                                    font-weight: 600;
                                    text-align: center;
                                    padding: 15px;
                                    position: sticky;
                                    top: 0;
                                    z-index: 10;
                                }
                                .schedule-table td {
                                    padding: 8px;
                                    border: 1px solid #e9ecef;
                                    vertical-align: top;
                                    height: 100px;
                                    width: 14.28%;
                                }
                                .schedule-table td:first-child {
                                    background-color: #f8f9fa;
                                    font-weight: 600;
                                    text-align: center;
                                    width: 8%;
                                }
                                .course-card {
                                    background: linear-gradient(310deg, #2152ff 0%, #21d4fd 100%);
                                    color: white;
                                    border-radius: 10px;
                                    padding: 10px;
                                    margin-bottom: 5px;
                                    box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
                                    transition: all 0.2s;
                                }
                                .course-card:hover {
                                    transform: translateY(-2px);
                                    box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
                                }
                                .course-title {
                                    font-weight: 600;
                                    font-size: 0.9rem;
                                    margin-bottom: 5px;
                                }
                                .course-info {
                                    font-size: 0.75rem;
                                    display: flex;
                                    align-items: center;
                                    margin-bottom: 3px;
                                }
                                .course-info i {
                                    margin-right: 5px;
                                    width: 14px;
                                }
                                .time-slot {
                                    font-weight: 600;
                                    color: #344767;
                                }
                                @media print {
                                    .btn, select, .form-group {
                                        display: none !important;
                                    }
                                    .card {
                                        box-shadow: none !important;
                                        border: 1px solid #ddd;
                                    }
                                    .course-card {
                                        break-inside: avoid;
                                    }
                                }
                            </style>
                            <table class="table schedule-table">
                                <thead>
                                    <tr>
                                        <th>Heure</th>
                                        @foreach($jours as $jour)
                                            <th>{{ $jour }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($heures as $index => $heure)
                                        @if($index < count($heures) - 1)
                                            <tr>
                                                <td class="time-slot">{{ $heure }} - {{ $heures[$index + 1] }}</td>
                                                @foreach($jours as $jour)
                                                    <td>
                                                        @if(isset($emploiDuTemps[$jour]))
                                                            @foreach($emploiDuTemps[$jour] as $cours)
                                                                @php
                                                                    $debut = substr($cours->heure_debut, 0, 5);
                                                                    $fin = substr($cours->heure_fin, 0, 5);
                                                                @endphp
                                                                @if($debut <= $heure && $fin > $heure)
                                                                    <div class="course-card">
                                                                        <div class="course-title">{{ $cours->matiere->nom }}</div>
                                                                        <div class="course-info">
                                                                            <i class="fas fa-clock"></i>
                                                                            <span>{{ $debut }} - {{ $fin }}</span>
                                                                        </div>
                                                                        <div class="course-info">
                                                                            <i class="fas fa-chalkboard-teacher"></i>
                                                                            <span>{{ $cours->professeur->nom }} {{ $cours->professeur->prenom }}</span>
                                                                        </div>
                                                                        <div class="course-info">
                                                                            <i class="fas fa-door-open"></i>
                                                                            <span>Salle {{ $cours->salle->nom }}</span>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Vue mobile pour petits écrans -->
    <div class="d-md-none">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6 class="mb-0">Vue par jour</h6>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-pills nav-fill mb-3" id="pills-tab" role="tablist">
                            @foreach($jours as $index => $jour)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                            id="pills-{{ strtolower($jour) }}-tab" 
                                            data-bs-toggle="pill" 
                                            data-bs-target="#pills-{{ strtolower($jour) }}" 
                                            type="button" 
                                            role="tab" 
                                            aria-controls="pills-{{ strtolower($jour) }}" 
                                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                        {{ substr($jour, 0, 3) }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            @foreach($jours as $index => $jour)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                                     id="pills-{{ strtolower($jour) }}" 
                                     role="tabpanel" 
                                     aria-labelledby="pills-{{ strtolower($jour) }}-tab">
                                    @if(isset($emploiDuTemps[$jour]) && count($emploiDuTemps[$jour]) > 0)
                                        <div class="timeline">
                                            @foreach($emploiDuTemps[$jour] as $cours)
                                                <div class="timeline-item mb-3">
                                                    <div class="timeline-content">
                                                        <div class="course-card">
                                                            <div class="course-title">{{ $cours->matiere->nom }}</div>
                                                            <div class="course-info">
                                                                <i class="fas fa-clock"></i>
                                                                <span>{{ substr($cours->heure_debut, 0, 5) }} - {{ substr($cours->heure_fin, 0, 5) }}</span>
                                                            </div>
                                                            <div class="course-info">
                                                                <i class="fas fa-chalkboard-teacher"></i>
                                                                <span>{{ $cours->professeur->nom }} {{ $cours->professeur->prenom }}</span>
                                                            </div>
                                                            <div class="course-info">
                                                                <i class="fas fa-door-open"></i>
                                                                <span>Salle {{ $cours->salle->nom }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-calendar-times text-info mb-3 fa-2x"></i>
                                            <p class="text-muted">Aucun cours programmé pour {{ $jour }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
