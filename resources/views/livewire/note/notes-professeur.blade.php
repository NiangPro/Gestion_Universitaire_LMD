<div class="container-fluid py-4">
    <!-- En-tête avec titre et message de statut -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="h4 mb-3">Gestion des Notes</h2>
            @if($message)
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ $message }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Filtres de sélection -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <label for="classe" class="form-label">Classe</label>
            <select wire:model.live="selectedClasse" id="classe" class="form-control">
                <option value="">Sélectionner une classe</option>
                @foreach($classes as $classe)
                    <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                @endforeach
            </select>
        </div>
        @if($selectedClasse)
        <div class="col-md-4 mb-3">
            <label for="matiere" class="form-label">Matière</label>
            <select wire:model.live="selectedMatiere" id="matiere" class="form-control" >
                <option value="">Sélectionner une matière</option>
                @foreach($matieres as $matiere)
                    <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                @endforeach
            </select>
        </div>
        @endif
        @if($selectedMatiere)
        <div class="col-md-4 mb-3">
            <label for="typeEvaluation" class="form-label">Type d'évaluation</label>
            <select wire:model.live="selectedTypeEvaluation" id="typeEvaluation" class="form-control" >
                <option value="">Sélectionner le type</option>
                @foreach($typesEvaluation as $type)
                    <option value="{{ $type->id }}">{{ $type->nom }}</option>
                @endforeach
            </select>
        </div>
        @endif
    </div>

    <!-- Bouton de chargement des étudiants -->
    @if($selectedClasse && $selectedMatiere && $selectedTypeEvaluation)
    <div class="row mb-4">
        <div class="col-12">
            <button wire:click="chargerEtudiants" class="btn btn-primary" >
                <i class="fas fa-sync-alt mr-2"></i>Charger les étudiants
            </button>
        </div>
    </div>
    @endif

    <!-- Tableau des notes -->
    @if($etudiants && count($etudiants) > 0)
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Note</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($etudiants as $etudiant)
                        <tr>
                            <td>{{ $etudiant->matricule }}</td>
                            <td>{{ $etudiant->nom }}</td>
                            <td>{{ $etudiant->prenom }}</td>
                            <td>
                                <input type="number" 
                                       class="form-control form-control-sm" 
                                       min="0" 
                                       max="20" 
                                       step="0.25"
                                       value="{{ $notes[$etudiant->id]->note ?? '' }}"
                                       wire:change="sauvegarderNotes({{ $etudiant->id }}, $event.target.value)"
                                       style="width: 80px">
                            </td>
                            <td>
                                @if(isset($notes[$etudiant->id]))
                                    <span class="badge badge-success">Note enregistrée</span>
                                @else
                                    <span class="badge badge-warning">En attente</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @elseif($selectedClasse && $selectedMatiere && $selectedTypeEvaluation)
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                Aucun étudiant trouvé pour cette sélection.
            </div>
        </div>
    </div>
    @endif
</div>
