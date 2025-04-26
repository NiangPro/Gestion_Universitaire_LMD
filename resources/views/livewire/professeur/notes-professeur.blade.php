<div class="container-fluid py-4">
    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="h4 mb-0 text-primary"><i class="fas fa-graduation-cap mr-2"></i>Gestion des Notes</h2>
            </div>
            <ul class="nav nav-tabs mt-3" role="tablist" wire:ignore.self>
                <li class="nav-item" wire:ignore.self>
                    <a class="nav-link active" id="ajout-tab" data-toggle="tab" href="#ajout" role="tab" wire:ignore.self>
                        <i class="fas fa-plus-circle mr-2"></i>Ajout de Notes
                    </a>
                </li>
                <li class="nav-item" wire:ignore.self>
                    <a class="nav-link" id="visualisation-tab" data-toggle="tab" href="#visualisation" role="tab" wire:ignore.self>
                        <i class="fas fa-chart-bar mr-2"></i>Visualisation des Notes
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body tab-content">
            @if($message)
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle mr-2"></i>{{ $message }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Onglet Ajout de Notes -->
            <div class="tab-pane fade show active" id="ajout" role="tabpanel" wire:ignore.self>
                <!-- Filtres de sélection -->
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="classe"><i class="fas fa-users mr-2"></i>Classe</label>
                            <select wire:model.live="selectedClasse" id="classe" class="form-control">
                                <option value="">Sélectionner une classe</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}">{{ $classe->nom }}({{ $classe->filiere->nom}})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                @if($selectedClasse)
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="matiere"><i class="fas fa-book mr-2"></i>Matière</label>
                        <select wire:model.live="selectedMatiere" id="matiere" class="form-control">
                            <option value="">Sélectionner une matière</option>
                            @foreach($matieres as $matiere)
                                <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif

                @if($selectedMatiere)
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="typeEvaluation"><i class="fas fa-tasks mr-2"></i>Type d'évaluation</label>
                        <select wire:model.live="selectedTypeEvaluation" id="typeEvaluation" class="form-control">
                            <option value="">Sélectionner le type</option>
                            @foreach($typesEvaluation as $type)
                                <option value="{{ $type->id }}">{{ $type->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
            </div>

            <!-- Bouton de chargement des étudiants -->
            @if($selectedClasse && $selectedMatiere && $selectedTypeEvaluation)
            <div class="text-center mt-4">
                <button wire:click="chargerEtudiants" class="btn btn-primary btn-lg">
                    <i class="fas fa-sync-alt mr-2"></i>Charger les étudiants
                </button>
            </div>
            @endif

            <!-- Tableau des notes -->
            @if($etudiants && count($etudiants) > 0)
            <div class="table-responsive mt-4">
                <table class="table table-hover table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th style="width: 150px;">Note</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($etudiants as $etudiant)
                        <tr class="hover-shadow-sm transition-all">
                            <td class="font-monospace">{{ $etudiant->matricule }}</td>
                            <td>{{ $etudiant->nom }}</td>
                            <td>{{ $etudiant->prenom }}</td>
                            <td>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control" 
                                           min="0" 
                                           max="20" 
                                           step="0.25"
                                           value="{{ $notes[$etudiant->id]->note ?? '' }}"
                                           wire:change="sauvegarderNotes({{ $etudiant->id }}, $event.target.value)">
                                    <div class="input-group-append">
                                        <span class="input-group-text">/20</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if(isset($notes[$etudiant->id]))
                                    <span class="badge bg-success rounded-pill"><i class="fas fa-check me-1"></i>Enregistrée</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill"><i class="fas fa-clock me-1"></i>En attente</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @elseif($selectedClasse && $selectedMatiere && $selectedTypeEvaluation && $etudiants && count($etudiants) < 1)
            <div class="alert alert-info mt-4">
                <i class="fas fa-info-circle me-2"></i>Aucun étudiant trouvé pour cette sélection.
            </div>
            @endif
            </div>

            <!-- Onglet Visualisation des Notes -->
            <div class="tab-pane fade" id="visualisation" role="tabpanel" wire:ignore.self>
                <div class="row mb-4" wire:ignore.self>
                    <div class="col-md-4">
                        <div class="card border-left-primary shadow h-100" wire:ignore.self>
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Moyenne Générale</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($moyenneGenerale ?? 0, 2) }}/20</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calculator fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-left-success shadow h-100">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Meilleure Note</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($meilleureNote ?? 0, 2) }}/20</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-trophy fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-left-info shadow h-100">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Taux de Réussite</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($tauxReussite ?? 0, 1) }}%</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-percent fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtres pour la visualisation -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="classeVisu"><i class="fas fa-users mr-2"></i>Classe</label>
                            <select wire:model.live="selectedClasseVisu" id="classeVisu" class="form-control">
                                <option value="">Sélectionner une classe</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}">{{ $classe->nom }}({{ $classe->filiere->nom}})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if($selectedClasseVisu)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="matiereVisu"><i class="fas fa-book mr-2"></i>Matière</label>
                            <select wire:model.live="selectedMatiereVisu" id="matiereVisu" class="form-control">
                                <option value="">Sélectionner une matière</option>
                                @foreach($matieresVisu ?? [] as $matiere)
                                    <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    @if($selectedMatiereVisu)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="typeEvaluationVisu"><i class="fas fa-tasks mr-2"></i>Type d'évaluation</label>
                            <select wire:model.live="selectedTypeEvaluationVisu" id="typeEvaluationVisu" class="form-control">
                                <option value="">Sélectionner le type</option>
                                @foreach($typesEvaluation as $type)
                                    <option value="{{ $type->id }}">{{ $type->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Tableau des notes avec statistiques -->
                @if($notesVisu && count($notesVisu) > 0)
                <div class="table-responsive" wire:ignore.self>
                    <table class="table table-hover table-bordered" wire:ignore.self>
                        <thead class="thead-light">
                            <tr>
                                <th>Matricule</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Note</th>
                                <th>Statut</th>
                                <th>Date de modification</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notesVisu as $note)
                            <tr>
                                <td>{{ $note->etudiant->matricule }}</td>
                                <td>{{ $note->etudiant->nom }}</td>
                                <td>{{ $note->etudiant->prenom }}</td>
                                <td>
                                    <span class="badge {{ $note->note >= 10 ? 'bg-success' : 'bg-danger' }} rounded-pill">
                                        {{ number_format($note->note, 2) }}/20
                                    </span>
                                </td>
                                <td>
                                    @if($note->note >= 10)
                                        <span class="badge bg-success rounded-pill">Validé</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill">Non validé</span>
                                    @endif
                                </td>
                                <td>{{ $note->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @elseif($selectedClasseVisu && $selectedMatiereVisu && $selectedTypeEvaluationVisu)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>Aucune note trouvée pour cette sélection.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@section("css")
<style>
.hover-lift:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
}

.hover-shadow-sm {
    transition: all 0.2s ease-in-out;
}

.hover-shadow-sm:hover {
    background-color: rgba(0,0,0,0.02);
}

.transition-all {
    transition: all 0.2s ease-in-out;
}

.border-left-primary {
    border-left: 4px solid #4e73df;
}

.border-left-success {
    border-left: 4px solid #1cc88a;
}

.border-left-info {
    border-left: 4px solid #36b9cc;
}

.card-body {
    padding: 1.25rem;
}

.text-xs {
    font-size: .7rem;
}

.text-gray-300 {
    color: #dddfeb;
}

.text-gray-800 {
    color: #5a5c69;
}
</style>
@endsection
