<div class="row">
    <div class="col-md-12">
        <!-- Formulaire principal pour l'UE -->
        <form wire:submit.prevent="store" wire:key="ue-form-{{$id ?? 'new'}}" onsubmit="console.log('Form submitted')">
            <div class="row">
                <!-- Informations de l'UE -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Informations de l'UE</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label><strong>Nom</strong></label>
                                <input type="text" 
                                    wire:model.defer='nom' 
                                    class="form-control @error('nom') is-invalid @enderror" 
                                    placeholder="Nom de l'UE">
                                @error('nom') 
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label><strong>Filière</strong></label>
                                <select wire:model.defer='filiere_id' 
                                    class="form-control @error('filiere_id') is-invalid @enderror">
                                    <option value="">Sélectionner une filière</option>
                                    @foreach($filieres as $f)
                                        <option value="{{ $f->id }}">{{ $f->nom }}</option>
                                    @endforeach
                                </select>
                                @error('filiere_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label><strong>Crédit</strong></label>
                                <input type="number" 
                                    wire:model.defer='credit' 
                                    class="form-control @error('credit') is-invalid @enderror" 
                                    placeholder="Crédit total">
                                @error('credit') 
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste des matières -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Matières ({{ count($listeMatieres) }})</h4>
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
                                                <th>Actions</th>
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
                                                        <button type="button" 
                                                            wire:click="editMatiere({{ $index }})" 
                                                            class="btn btn-warning btn-sm">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button type="button" 
                                                            wire:click="removeMatiere({{ $index }})" 
                                                            class="btn btn-danger btn-sm ml-1">
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

            <!-- Boutons de soumission -->
            <div class="row mt-4">
                <div class="col-12">
                    <button type="submit" class="btn {{ $id ? 'btn-warning' : 'btn-success' }}">
                        <i class="fa {{ $id ? 'fa-edit' : 'fa-save' }} mr-2"></i>
                        {{ $id ? 'Modifier' : 'Enregistrer' }}
                    </button>
                    <button type="button" wire:click="changeStatus('list')" class="btn btn-secondary ml-2">
                        <i class="fa fa-times mr-2"></i>Annuler
                    </button>
                </div>
            </div>
        </form>

        <!-- Formulaire d'ajout/modification de matière -->
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="card-title">{{ $editingMatiere ? 'Modifier la matière' : 'Ajouter une matière' }}</h4>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="{{ $editingMatiere ? 'updateMatiere' : 'addMatiere' }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nom de la matière</label>
                                <input type="text" 
                                    wire:model.defer="matiere.nom" 
                                    class="form-control @error('matiere.nom') is-invalid @enderror" 
                                    placeholder="Nom de la matière">
                                @error('matiere.nom') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Crédit</label>
                                <input type="number" 
                                    wire:model.defer="matiere.credit" 
                                    class="form-control @error('matiere.credit') is-invalid @enderror" 
                                    placeholder="Crédit">
                                @error('matiere.credit') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Coefficient</label>
                                <input type="number" 
                                    wire:model.defer="matiere.coefficient" 
                                    class="form-control @error('matiere.coefficient') is-invalid @enderror" 
                                    placeholder="Coefficient">
                                @error('matiere.coefficient') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Volume horaire</label>
                                <input type="number" 
                                    wire:model.defer="matiere.volume_horaire" 
                                    class="form-control @error('matiere.volume_horaire') is-invalid @enderror" 
                                    placeholder="Heures">
                                @error('matiere.volume_horaire') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn {{ $editingMatiere ? 'btn-warning' : 'btn-primary' }}">
                            <i class="fa {{ $editingMatiere ? 'fa-save' : 'fa-plus' }} mr-2"></i>
                            {{ $editingMatiere ? 'Modifier' : 'Ajouter' }} la matière
                        </button>
                        @if($editingMatiere)
                            <button type="button" wire:click="cancelEdit" class="btn btn-secondary ml-2">
                                <i class="fa fa-times mr-2"></i>Annuler
                            </button>
                        @endif
                    </div>
                </form>
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

    window.addEventListener('error', event => {
        iziToast.error({
            title: 'Erreur',
            message: event.detail.message,
            position: 'topRight'
        });
    });

    window.addEventListener('livewire:initialized', () => {
        console.log('Livewire initialized');
    });

    document.addEventListener('livewire:init', () => {
        console.log('Livewire initialized');
        
        Livewire.on('success', (event) => {
            console.log('Success event:', event);
        });
        
        Livewire.on('error', (event) => {
            console.log('Error event:', event);
        });
    });
</script>

<!-- Pour la liste des matières -->
@error('listeMatieres') 
    <div class="alert alert-danger">
        {{ $message }}
    </div>
@enderror