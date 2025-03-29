<div class="row">
    <div class="col-md-6">
        <!-- Formulaire UE -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Informations de l'UE</h4>
            </div>
            <div class="card-body">
                <form wire:submit='store'>
                    <div class="form-group">
                        <label><strong>Nom</strong></label>
                        <input type="text" wire:model='nom' class="form-control @error('nom') is-invalid @enderror" placeholder="Nom de l'UE">
                        @error('nom') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label><strong>Filière</strong></label>
                        <select wire:model='filiere_id' class="form-control @error('filiere_id') is-invalid @enderror">
                            <option value="">Sélectionner une filière</option>
                            @foreach($filieres as $f)
                                <option value="{{ $f->id }}">{{ $f->nom }}</option>
                            @endforeach
                        </select>
                        @error('filiere_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label><strong>Crédit</strong></label>
                        <input type="number" wire:model='credit' class="form-control @error('credit') is-invalid @enderror" placeholder="Crédit total">
                        @error('credit') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="mt-3">
                        @if($id)
                            <button type="submit" class="btn btn-warning">Modifier</button>
                        @else 
                            <button type="submit" class="btn btn-success">Enregistrer</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <!-- Formulaire Matière -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Ajouter une matière</h4>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="addMatiere">
                    <div class="form-group">
                        <label>Nom de la matière</label>
                        <input type="text" wire:model="matiere.nom" class="form-control @error('matiere.nom') is-invalid @enderror" placeholder="Nom de la matière">
                        @error('matiere.nom') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Crédit</label>
                                <input type="number" wire:model="matiere.credit" class="form-control @error('matiere.credit') is-invalid @enderror" placeholder="Crédit">
                                @error('matiere.credit') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Coefficient</label>
                                <input type="number" wire:model="matiere.coefficient" class="form-control @error('matiere.coefficient') is-invalid @enderror" placeholder="Coefficient">
                                @error('matiere.coefficient') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Volume horaire</label>
                                <input type="number" wire:model="matiere.volume_horaire" class="form-control @error('matiere.volume_horaire') is-invalid @enderror" placeholder="Heures">
                                @error('matiere.volume_horaire') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Ajouter la matière
                    </button>
                    <button type="reset" class="btn btn-warning">
                        <i class="fa fa-refresh"></i> Annuler
                    </button>
                </form>
            </div>
        </div>

        <!-- Liste des matières -->
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="card-title">Liste des matières ({{ count($listeMatieres) }})</h4>
            </div>
            <div class="card-body">
                @if(empty($listeMatieres))
                    <div class="text-center text-muted py-3">
                        <i class="fa fa-book fa-2x mb-2"></i>
                        <p>Aucune matière ajoutée</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Crédit</th>
                                    <th>Coefficient</th>
                                    <th>Volume horaire</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($listeMatieres as $index => $mat)
                                    <tr>
                                        <td>{{ $mat['nom'] }}</td>
                                        <td>{{ $mat['credit'] }}</td>
                                        <td>{{ $mat['coefficient'] }}</td>
                                        <td>{{ $mat['volume_horaire'] }}h</td>
                                        <td>
                                            <button wire:click="removeMatiere({{ $index }})" class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('success', event => {
        iziToast.success({
            title: 'Succès',
            message: event.detail.message,
            position: 'topRight'
        });
    });
</script>