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

                            <div class="form-group">
                                <label><strong>Type de période</strong></label>
                                <select wire:model.live='type_periode' 
                                    class="form-control @error('type_periode') is-invalid @enderror">
                                    <option value="">Sélectionner le type</option>
                                    <option value="annee">Année</option>
                                    <option value="mois">Mois</option>
                                </select>
                                @error('type_periode') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label><strong>Durée</strong></label>
                                <input type="number" 
                                    wire:model.live='duree' 
                                    class="form-control @error('duree') is-invalid @enderror" 
                                    min="1"
                                    placeholder="Durée">
                                @error('duree') <span class="invalid-feedback">{{ $message }}</span> @enderror
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
                                    wire:model.live='cout_inscription' 
                                    class="form-control @error('cout_inscription') is-invalid @enderror" 
                                    placeholder="Montant des frais d'inscription">
                                @error('cout_inscription') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label><strong>Mensualité</strong></label>
                                <input type="number" 
                                    wire:model.live='mensualite' 
                                    class="form-control @error('mensualite') is-invalid @enderror" 
                                    placeholder="Montant de la mensualité">
                                @error('mensualite') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label><strong>Coût total de la formation</strong></label>
                                <div class="input-group">
                                    <input type="text" 
                                        class="form-control" 
                                        readonly
                                        value="{{ number_format($this->calculated_cout_formation, 0, ',', ' ') }}"
                                        placeholder="Calculé automatiquement">
                                    <div class="input-group-append">
                                        <span class="input-group-text">FCFA</span>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    @if($type_periode === 'annee')
                                        Calcul : ({{ number_format($mensualite ?: 0) }} × 9 mois × {{ $duree ?: 0 }} années) + {{ number_format($cout_inscription ?: 0) }} FCFA d'inscription
                                    @else
                                        Calcul : ({{ number_format($mensualite ?: 0) }} × {{ $duree ?: 0 }} mois) + {{ number_format($cout_inscription ?: 0) }} FCFA d'inscription
                                    @endif
                                </small>
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