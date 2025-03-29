<div class="row">
    <div class="col-md-12">
        <form wire:submit='store'>
            <div class="row">
                <!-- Informations de l'UE -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Informations de l'UE</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="font-weight-bold">Nom de l'UE</label>
                                <input type="text" 
                                    wire:model='nom' 
                                    class="form-control @error('nom') is-invalid @enderror" 
                                    placeholder="Nom de l'unité d'enseignement">
                                @error('nom') 
                                    <span class="invalid-feedback">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Filière</label>
                                <select wire:model='filiere_id' 
                                    class="form-control @error('filiere_id') is-invalid @enderror">
                                    <option value="">Sélectionner une filière</option>
                                    @foreach($filieres as $filiere)
                                        <option value="{{ $filiere->id }}">{{ $filiere->nom }}</option>
                                    @endforeach
                                </select>
                                @error('filiere_id') 
                                    <span class="invalid-feedback">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Crédit total</label>
                                <input type="number" 
                                    wire:model='credit' 
                                    class="form-control @error('credit') is-invalid @enderror" 
                                    placeholder="Nombre de crédits">
                                @error('credit') 
                                    <span class="invalid-feedback">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gestion des matières -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Matières</h4>
                            <button type="button" class="btn btn-primary btn-sm" wire:click="addNewMatiere">
                                <i class="fa fa-plus"></i> Ajouter une matière
                            </button>
                        </div>
                        <div class="card-body">
                            @foreach($matieres as $index => $matiere)
                                <div class="border p-3 mb-3 rounded">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h5>Matière #{{ $index + 1 }}</h5>
                                        <button type="button" 
                                            class="btn btn-danger btn-sm" 
                                            wire:click="removeMatiere({{ $index }})">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" 
                                            wire:model="matieres.{{ $index }}.nom" 
                                            class="form-control @error('matieres.'.$index.'.nom') is-invalid @enderror" 
                                            placeholder="Nom de la matière">
                                        @error('matieres.'.$index.'.nom') 
                                            <span class="invalid-feedback">{{ $message }}</span> 
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="number" 
                                                    wire:model="matieres.{{ $index }}.credit" 
                                                    class="form-control @error('matieres.'.$index.'.credit') is-invalid @enderror" 
                                                    placeholder="Crédits">
                                                @error('matieres.'.$index.'.credit') 
                                                    <span class="invalid-feedback">{{ $message }}</span> 
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="number" 
                                                    wire:model="matieres.{{ $index }}.coefficient" 
                                                    class="form-control @error('matieres.'.$index.'.coefficient') is-invalid @enderror" 
                                                    placeholder="Coefficient">
                                                @error('matieres.'.$index.'.coefficient') 
                                                    <span class="invalid-feedback">{{ $message }}</span> 
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="number" 
                                                    wire:model="matieres.{{ $index }}.volume_horaire" 
                                                    class="form-control @error('matieres.'.$index.'.volume_horaire') is-invalid @enderror" 
                                                    placeholder="Volume horaire">
                                                @error('matieres.'.$index.'.volume_horaire') 
                                                    <span class="invalid-feedback">{{ $message }}</span> 
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save mr-2"></i>
                        {{ $id ? 'Modifier' : 'Enregistrer' }}
                    </button>
                    <button type="button" wire:click="changeStatus('list')" class="btn btn-secondary ml-2">
                        <i class="fa fa-times mr-2"></i>Annuler
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>