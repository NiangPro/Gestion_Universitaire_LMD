<div class="modal fade show" style="display: block" tabindex="-1">
        <div class="modal-dialog modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-{{ $isEditing ? 'edit' : 'plus-circle' }} mr-2"></i>
                        {{ $isEditing ? 'Modifier le retard' : 'Nouveau retard' }}
                    </h5>
                    <button type="button" class="close" wire:click="closeModal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cours</label>
                                    <select class="form-control @error('cours_id') is-invalid @enderror" 
                                            wire:model.live="cours_id">
                                        <option value="">Sélectionner un cours</option>
                                        @foreach ($cours as $cour)
                                            <option value="{{ $cour->id }}">
                                                {{ $cour->matiere->nom }} - {{ $cour->classe->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cours_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @if($cours_id)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Étudiant</label>
                                    <select class="form-control @error('etudiant_id') is-invalid @enderror" 
                                            wire:model="etudiant_id">
                                        <option value="">Sélectionner un étudiant</option>
                                        @foreach ($inscriptions as $inscription)
                                            <option value="{{ $inscription->etudiant->id }}">
                                                {{ $inscription->etudiant->nom }} {{ $inscription->etudiant->prenom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('etudiant_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            @endif

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date et heure</label>
                                    <input type="datetime-local" 
                                           class="form-control @error('date') is-invalid @enderror"
                                           wire:model="date">
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Minutes de retard</label>
                                    <input type="number" 
                                           class="form-control @error('minutes_retard') is-invalid @enderror"
                                           wire:model="minutes_retard" 
                                           min="1">
                                    @error('minutes_retard')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Motif</label>
                                    <input type="text" 
                                           class="form-control @error('motif') is-invalid @enderror"
                                           wire:model="motif">
                                    @error('motif')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               wire:model="justifie"
                                               id="justifieSwitch">
                                        <label class="custom-control-label" for="justifieSwitch">
                                            Retard justifié
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Commentaire</label>
                                    <textarea class="form-control @error('commentaire') is-invalid @enderror"
                                              wire:model="commentaire" 
                                              rows="3"></textarea>
                                    @error('commentaire')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">
                        <i class="fas fa-times mr-2"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="save">
                        <i class="fas fa-save mr-2"></i>{{ $isEditing ? 'Mettre à jour' : 'Enregistrer' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>