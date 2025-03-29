<div class="row">
    <div class="col-md-12">
        <form wire:submit.prevent="store">
            <div class="row">
                <!-- Informations principales -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Informations de la classe</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label><strong>Nom</strong></label>
                                <input type="text" 
                                    wire:model.defer='nom' 
                                    class="form-control @error('nom') is-invalid @enderror" 
                                    placeholder="Nom de la classe">
                                @error('nom') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label><strong>Filière</strong></label>
                                <select wire:model.defer='filiere_id' 
                                    class="form-control @error('filiere_id') is-invalid @enderror">
                                    <option value="">Sélectionner une filière</option>
                                    @foreach($filieres as $filiere)
                                        <option value="{{ $filiere->id }}">{{ $filiere->nom }}</option>
                                    @endforeach
                                </select>
                                @error('filiere_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations financières -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Informations financières</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label><strong>Frais d'inscription</strong></label>
                                <input type="number" 
                                    wire:model.defer='cout_inscription' 
                                    class="form-control @error('cout_inscription') is-invalid @enderror" 
                                    placeholder="Montant des frais d'inscription">
                                @error('cout_inscription') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label><strong>Mensualité</strong></label>
                                <input type="number" 
                                    wire:model.defer='mensualite' 
                                    class="form-control @error('mensualite') is-invalid @enderror" 
                                    placeholder="Montant de la mensualité">
                                @error('mensualite') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label><strong>Coût total de la formation</strong></label>
                                <input type="number" 
                                    wire:model.defer='cout_formation' 
                                    class="form-control @error('cout_formation') is-invalid @enderror" 
                                    placeholder="Coût total de la formation">
                                @error('cout_formation') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
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
    </div>
</div> 