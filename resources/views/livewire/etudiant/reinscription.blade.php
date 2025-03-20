<div class="container mt-5">
    @if($matricule)
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5>Informations de l'étudiant</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Matricule:</strong> {{ $etudiant->matricule }}</p>
                                <p><strong>Nom:</strong> {{ $etudiant->nom }}</p>
                                <p><strong>Prénom:</strong> {{ $etudiant->prenom }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Dernière classe:</strong> {{ $derniere_inscription->classe->nom ?? 'N/A' }}</p>
                                <p><strong>Année académique:</strong> {{ $derniere_inscription->academicYear->nom ?? 'N/A' }}</p>
                                <p><strong>Campus:</strong> {{ $derniere_inscription->campus->nom ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                 <form wire:submit.prevent="reinscrire">
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h5>Réinscription</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Classe</label>
                                    <select class="form-control" wire:model="classe_id">
                                        <option value="">Sélectionner une classe</option>
                                        @foreach($classes as $classe)
                                            <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                                        @endforeach
                                    </select>
                                    @error('classe_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6 mb-3">
                                    <label class="text-label">Paiements</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-wallet" style="font-size:15px;"></i> </span>
                                        </div>
                                        <select class="form-control" wire:model.live="etat">
                                            <option value="">Sélectionner</option>
                                            <option value="Payé">Payé</option>
                                            <option value="Avance">Avance</option>
                                        </select>
                                    </div>
                                    @error('etat') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                @if($etat === "Payé")
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="text-label">Montant</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa-solid fa-money-bill-wave" style="font-size:15px;"></i> </span>
                                            </div>
                                            <input type="number" wire:model.live="montant" class="form-control">
                                        </div>
                                        @error('montant') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                 @endif
                            </div>
                            @if($etat === "Avance")
                                <div class="row">
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="text-label">Montant</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa-solid fa-money-bill-wave" style="font-size:15px;"></i> </span>
                                            </div>
                                            <input type="number" wire:model.live="montant" class="form-control">
                                        </div>
                                        @error('montant') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="text-label">Restant</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa-solid fa-money-bill-wave" style="font-size:15px;"></i> </span>
                                            </div>
                                            <input type="number" wire:model.live="restant" class="form-control">
                                        </div>
                                        @error('restant') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tenue scolaire</label>
                                    <select class="form-control" wire:model="tenue">
                                        <option value="">Sélectionner une option</option>
                                        <option value="1">Oui</option>
                                        <option value="0">Non</option>
                                    </select>
                                    @error('tenue') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Commentaire</label>
                                    <textarea class="form-control" wire:model="commentaire" rows="3"></textarea>
                                    @error('commentaire') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success">Réinscrire l'étudiant</button>
                                    <button type="reset" class="btn btn-warning">Réinitialiser</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    @endif
</div>
@push('scripts')
<script>
    window.addEventListener('saved', event => {
        iziToast.success({
            title: 'Inscription',
            message: 'Enregistré avec succès',
            position: 'topRight'
        });
    });

    window.addEventListener('deleted', event => {
        iziToast.success({
            title: 'Inscription',
            message: 'Supprimé avec succès',
            position: 'topRight'
        });
    });
</script>
@endpush
