<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center bg-white rounded-3 p-4 shadow-sm">
                <div>
                    <h4 class="mb-0 text-primary">
                        <i class="fas fa-calendar-check me-2"></i>Gestion des Absences
                    </h4>
                    <p class="text-muted mb-0">{{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</p>
                </div>
                <div>
                    <input type="date" wire:model.live="date" class="form-control" max="{{ date('Y-m-d') }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des Classes -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="text-muted mb-3">Classes ayant cours aujourd'hui</h5>
            @if($loading)
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            @else
                @if(empty($classes))
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Aucune classe n'a cours aujourd'hui
                    </div>
                @else
                    <div class="row">
                        @foreach($classes as $classe)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 {{ $selectedClasse === $classe['id'] ? 'border-primary' : '' }} hover-shadow cursor-pointer"
                                     wire:click="selectClasse('{{ $classe['id'] }}', '{{ $classe['cours_id'] }}')">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary mb-3">{{ $classe['nom'] }}</h5>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-book me-2 text-muted"></i>
                                            <span>{{ $classe['matiere'] }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-clock me-2 text-muted"></i>
                                            <span>{{ substr($classe['heure_debut'], 0, 5) }} - {{ substr($classe['heure_fin'], 0, 5) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Liste des Étudiants -->
    @if($selectedClasse && !empty($etudiants))
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 row">
                <h5 class="col-md-8">Liste des Étudiants</h5>
                <div class="col-md-4 text-right">
                    <button class="btn btn-primary" wire:click="saveAbsences">
                        <i class="fas fa-save me-2"></i>Enregistrer les absences
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Matricule</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th class="text-center">Absent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($etudiants as $index => $etudiant)
                                <tr>
                                    <td>{{ $etudiant['matricule'] }}</td>
                                    <td>{{ $etudiant['nom'] }}</td>
                                    <td>{{ $etudiant['prenom'] }}</td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input cursor-pointer" 
                                                   type="checkbox" 
                                                   wire:model.live="etudiants.{{ $index }}.absent"
                                                   wire:click="toggleAbsence({{ $index }})">
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <style>
        .hover-shadow:hover {
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            transform: translateY(-2px);
            transition: all .2s ease-in-out;
        }
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
</div>
