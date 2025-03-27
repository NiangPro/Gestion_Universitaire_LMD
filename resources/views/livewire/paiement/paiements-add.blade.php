<div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-{{ $isEditing ? 'edit' : 'plus-circle' }}"></i> 
                        {{ $isEditing ? 'Modifier le Paiement' : 'Nouveau Paiement' }}
                    </h5>
                    <button type="button" class="close text-white" wire:click="$set('showModal', false)">
                        <span>&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="{{ $isEditing ? 'updatePaiement' : 'savePaiement' }}">
                    <div class="modal-body">
                        <!-- Recherche étudiant -->
                        <div class="form-group position-relative">
                            <label>Rechercher un étudiant</label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       wire:model.live="searchMatricule" 
                                       placeholder="Rechercher par matricule, nom ou prénom..."
                                       autocomplete="off">
                                @if($selectedEtudiant)
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-success text-white">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Liste des suggestions -->
                        @if(!empty($suggestions) && !$selectedEtudiant)
                            <div class="position-absolute w-90 mt-1 shadow-sm  bg-primary text-white" style="z-index: 1000;">
                                <div class="list-group">
                                    @foreach($suggestions as $etudiant)
                                        <button type="button" 
                                                class="list-group-item list-group-item-action" 
                                                wire:click="selectEtudiant({{ $etudiant->id }})">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $etudiant->matricule }}</strong><br>
                                                    <small>{{ $etudiant->nom }} {{ $etudiant->prenom }}</small>
                                                </div>
                                                @if($etudiant->classe)
                                                    <span class="badge badge-info">
                                                        {{ $etudiant->classe->nom }}
                                                    </span>
                                                @endif
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Affichage de l'étudiant sélectionné -->
                        @if($selectedEtudiant)
                            <div class="mt-2 p-2 border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $selectedEtudiant->nom }} {{ $selectedEtudiant->prenom }}</strong>
                                        <br>
                                        <small class="text-muted">Matricule: {{ $selectedEtudiant->matricule }}</small>
                                    </div>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            wire:click="resetEtudiant">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                        <!-- Type de paiement -->
                        <div class="form-group col-md-6">
                            <label>Type de Paiement</label>
                            <select class="form-control" wire:model="type_paiement">
                                <option value="">Sélectionner le type</option>
                                <option value="inscription">Inscription</option>
                                <option value="mensualite">Mensualité</option>
                                <option value="complement">Complément</option>
                            </select>
                            @error('type_paiement') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Montant -->
                        <div class="form-group col-md-6">
                            <label>Montant</label>
                            <div class="input-group">
                                <input type="number" class="form-control" wire:model="montant">
                                <div class="input-group-append">
                                    <span class="input-group-text">FCFA</span>
                                </div>
                            </div>
                            @error('montant') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <!-- Mode de paiement -->
                        <div class="form-group col-md-5">
                            <label>Mode de Paiement</label>
                            <select class="form-control" wire:model="mode_paiement">
                                <option value="">Sélectionner le mode</option>
                                <option value="espece">Espèces</option>
                                <option value="cheque">Chèque</option>
                                <option value="virement">Virement</option>
                                <option value="mobile_money">Mobile Money</option>
                            </select>
                            @error('mode_paiement') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Observation -->
                        <div class="form-group col-md-7">
                            <label>Observation</label>
                            <textarea class="form-control" wire:model="observation" rows="3"></textarea>
                        </div>
                    </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" wire:click="$set('showModal', false)">
                            Annuler
                        </button>
                        <button type="submit" class="btn btn-primary" wire:click.prevent="{{ $isEditing ? 'updatePaiement' : 'savePaiement' }}">
                            <i class="fas fa-save"></i> {{ $isEditing ? 'Modifier' : 'Enregistrer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>