<div class="modal show d-block"  tabindex="-1" >
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouvelle Absence</h5>
                    <button type="button" class="close" wire:click="closeModal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            
                            <div class="col-md-6 form-group">
                                <label class="form-label">Cours</label>
                                <select class="form-control" wire:model.live="cours_id">
                                    <option value="">Sélectionner un cours</option>
                                    @foreach ($cours as $cour)
                                        <option value="{{ $cour->id }}">{{ $cour->matiere->nom }} - {{ $cour->classe->nom }} ({{ $cour->classe->filiere->nom }})</option>
                                    @endforeach
                                </select>
                                @error('cours_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @if ($cours_id)
                            <div class="col-md-6 form-group">
                                <label class="form-label">Étudiant</label>
                                <select class="form-control" wire:model="etudiant_id">
                                    <option value="">Sélectionner un étudiant</option>
                                    @foreach ($inscriptions as $inscription)
                                        <option value="{{ $inscription->etudiant->id }}">{{ $inscription->etudiant->nom }} {{ $inscription->etudiant->prenom }}</option>
                                    @endforeach
                                </select>
                                @error('etudiant_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @endif
                            <div class="col-md-6 form-group">
                                <label class="form-label">Date</label>
                                <input type="datetime-local" class="form-control" wire:model="date">
                                @error('date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="justifie">
                                    <label class="form-check-label">Absence justifiée</label>
                                </div>
                                @error('justifie')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 form-group">
                                <label class="form-label">Motif</label>
                                <input type="text" class="form-control" wire:model="motif">
                                @error('motif')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 form-group">
                                <label class="form-label">Commentaire</label>
                                <textarea class="form-control" wire:model="commentaire" rows="3"></textarea>
                                @error('commentaire')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" wire:click="closeModal">Annuler</button>
                    <button type="button" class="btn btn-primary" wire:click="save">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>