<div>
    <div class="container-fluid py-4">
        <!-- En-tête avec fond dégradé et sélecteur de semestre -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-gradient-primary shadow-lg">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <i class="fas fa-user-graduate fa-3x"></i>
                                    </div>
                                    <div>
                                        <h1 class="h3 mb-2">Bienvenue, {{ $user->prenom }} {{ $user->nom }}</h1>
                                        <p class="mb-0 opacity-8">Année académique : {{ date("d/m/Y", strtotime($currentAcademicYear->debut)) }} - {{ date("d/m/Y", strtotime($currentAcademicYear->fin)) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-white">Semestre</label>
                                    <select wire:model.live="selectedSemestre" class="form-control bg-white">
                                        @foreach($semestres as $semestre)
                                            <option value="{{ $semestre->id }}">{{ $semestre->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques principales -->
        <div class="row">
            <!-- Moyenne Générale -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Moyenne Générale</div>
                                <div class="h3 mb-0 font-weight-bold">{{ number_format($moyenneGenerale, 2) ?? 'N/A' }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Crédits Validés -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Crédits Validés</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold">{{ $creditsValides }}/{{ $creditsTotaux }}</div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-info" role="progressbar" 
                                                style="width: {{ $progressionCredits }}%" 
                                                aria-valuenow="{{ $progressionCredits }}" aria-valuemin="0" 
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Absences -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Absences</div>
                                <div class="h3 mb-0 font-weight-bold">{{ $totalAbsences }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-times fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Retards -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Retards</div>
                                <div class="h3 mb-0 font-weight-bold">{{ $totalRetards }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques par UE -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-pie mr-2"></i>Moyennes par Unité d'Enseignement
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($moyennesParUE as $ue => $data)
                                <div class="col-xl-4 col-md-6 mb-4">
                                    <div class="card border-left-{{ $data['moyenne'] >= 10 ? 'success' : 'danger' }} shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-{{ $data['moyenne'] >= 10 ? 'success' : 'danger' }} text-uppercase mb-1">{{ $ue }}</div>
                                                    <div class="h5 mb-0 font-weight-bold">{{ number_format($data['moyenne'], 2) }}/20</div>
                                                    <div class="text-xs text-muted mt-1">{{ $data['credits'] }} crédits</div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="h2 mb-0 font-weight-bold text-{{ $data['moyenne'] >= 10 ? 'success' : 'danger' }}">{{ round($data['moyenne']) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes Récentes et Emploi du temps -->
        <div class="row">
            <!-- Notes Récentes -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-graduation-cap mr-2"></i>Notes Récentes
                        </h6>
                        <button wire:click="toggleNotesModal" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye mr-1"></i>Voir toutes les notes
                        </button>
                    </div>
                    <div class="card-body">
                        @if(is_array($recentActivities) && count($recentActivities) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Matière</th>
                                            <th>Type</th>
                                            <th>Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentActivities as $note)
                                            <tr>
                                                <td>{{ date('d/m/Y', strtotime($note->created_at)) }}</td>
                                                <td>{{ $note->cours->matiere->nom }}</td>
                                                <td>{{ $note->type_evaluation }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $note->note >= 10 ? 'success' : 'danger' }} p-2">
                                                        {{ number_format($note->note, 2) }}/20
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-info-circle text-info mb-3 fa-2x"></i>
                                <p class="text-muted">Aucune note récente disponible</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Emploi du temps du jour -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-calendar-day mr-2"></i>Emploi du temps
                        </h6>
                        <button wire:click="toggleEmploiModal" class="btn btn-sm btn-primary">
                            <i class="fas fa-calendar-week mr-1"></i>Vue complète
                        </button>
                    </div>
                    <div class="card-body">
                        @if($emploiDuTemps && count($emploiDuTemps) > 0)
                            <div class="timeline">
                                @foreach($emploiDuTemps as $jour => $cours)
                                    <div class="timeline-item">
                                        <div class="timeline-date">{{ $jour }}</div>
                                        @foreach($cours as $seance)
                                            <div class="timeline-content">
                                                <div class="card bg-light mb-2">
                                                    <div class="card-body py-2">
                                                        <h6 class="mb-1">{{ $seance->matiere->nom }}</h6>
                                                        <p class="small text-muted mb-0">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            {{ date('H:i', strtotime($seance->heure_debut)) }} - 
                                                            {{ date('H:i', strtotime($seance->heure_fin)) }}
                                                        </p>
                                                        <p class="small text-muted mb-0">
                                                            <i class="fas fa-chalkboard-teacher mr-1"></i>
                                                            {{ $seance->professeur->nom }}
                                                        </p>
                                                        <p class="small text-muted mb-0">
                                                            <i class="fas fa-door-open mr-1"></i>
                                                            Salle {{ $seance->salle->nom }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times text-info mb-3 fa-2x"></i>
                                <p class="text-muted">Aucun cours programmé</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Notes -->
    <div class="modal fade" wire:ignore.self id="notesModal" tabindex="-1" role="dialog" aria-labelledby="notesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notesModalLabel">Mes Notes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if($moyennesParMatiere && count($moyennesParMatiere) > 0)
                        @foreach($moyennesParMatiere as $matiere => $data)
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">{{ $matiere }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Type</th>
                                                    <th>Note</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data['notes'] as $note)
                                                    <tr>
                                                        <td>{{ $note->type_evaluation }}</td>
                                                        <td>
                                                            <span class="badge badge-{{ $note->note >= 10 ? 'success' : 'danger' }} p-2">
                                                                {{ number_format($note->note, 2) }}/20
                                                            </span>
                                                        </td>
                                                        <td>{{ date('d/m/Y', strtotime($note->created_at)) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="bg-light">
                                                    <td colspan="2"><strong>Moyenne</strong></td>
                                                    <td>
                                                        <span class="badge badge-{{ $data['moyenne'] >= 10 ? 'success' : 'danger' }} p-2">
                                                            {{ number_format($data['moyenne'], 2) }}/20
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle text-info mb-3 fa-2x"></i>
                            <p class="text-muted">Aucune note disponible</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Emploi du temps -->
    <div class="modal fade" wire:ignore.self id="emploiModal" tabindex="-1" role="dialog" aria-labelledby="emploiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emploiModalLabel">Emploi du temps hebdomadaire</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if($emploiDuTemps && count($emploiDuTemps) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Horaire</th>
                                        @foreach($emploiDuTemps as $jour => $cours)
                                            <th>{{ $jour }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $heures = ['08:00', '10:00', '12:00', '14:00', '16:00'];
                                    @endphp

                                    @foreach($heures as $heure)
                                        <tr>
                                            <td>{{ $heure }}</td>
                                            @foreach($emploiDuTemps as $jour => $cours)
                                                <td>
                                                    @foreach($cours as $seance)
                                                        @if(date('H:i', strtotime($seance->heure_debut)) == $heure)
                                                            <div class="card bg-light mb-2">
                                                                <div class="card-body p-2">
                                                                    <h6 class="mb-1">{{ $seance->matiere->nom }}</h6>
                                                                    <p class="small text-muted mb-0">
                                                                        {{ date('H:i', strtotime($seance->heure_debut)) }} - 
                                                                        {{ date('H:i', strtotime($seance->heure_fin)) }}
                                                                    </p>
                                                                    <p class="small text-muted mb-0">
                                                                        {{ $seance->professeur->nom }}
                                                                    </p>
                                                                    <p class="small text-muted mb-0">
                                                                        Salle {{ $seance->salle->nom }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times text-info mb-3 fa-2x"></i>
                            <p class="text-muted">Aucun cours programmé</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Styles personnalisés -->
    <style>
        .timeline {
            position: relative;
            padding: 20px 0;
        }
        .timeline-item {
            position: relative;
            padding-left: 40px;
            margin-bottom: 20px;
        }
        .timeline-date {
            font-weight: bold;
            margin-bottom: 10px;
            color: #4e73df;
        }
        .timeline-item:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e3e6f0;
        }
        .timeline-item:after {
            content: '';
            position: absolute;
            left: -4px;
            top: 0;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #4e73df;
        }
        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }
    </style>
</div>