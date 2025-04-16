<div class="container-fluid py-4">
    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="h4 mb-0 text-primary"><i class="fas fa-graduation-cap mr-2"></i>Gestion des Notes</h2>
            </div>
        </div>
        <div class="card-body">
            @if($message)
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle mr-2"></i>{{ $message }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Filtres de sélection -->
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="classe"><i class="fas fa-users mr-2"></i>Classe</label>
                        <select wire:model.live="selectedClasse" id="classe" class="form-control">
                            <option value="">Sélectionner une classe</option>
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
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
</style>
@endsection
