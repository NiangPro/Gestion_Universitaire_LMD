<div wire:ignore.self class="modal fade" id="basicModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Formulaire d'ajout Campus</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="basic-form text-left">
                    <form wire:submit.prevent="submit">
                        @csrf
                        <div class="form-group">
                            <label class="text-label">Nom</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-file"></i> </span>
                                </div>
                                <input type="text" class="form-control" wire:model="nom" placeholder="Entrer le nom..">
                            </div>
                            @error('nom') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="text-label">Téléphone</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-phone"></i> </span>
                                </div>
                                <input type="text" class="form-control" wire:model="telephone" placeholder="Entrer le numéro téléphone..">
                            </div>
                            @error('telephone') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="text-label">Adresse</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-street-view"></i> </span>
                                </div>
                                <input type="text" class="form-control" wire:model="adresse" placeholder="Entrer l'adresse..">
                            </div>
                            @error('adresse') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="text-label">Email</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
                                </div>
                                <input type="email" class="form-control" wire:model="email" placeholder="Entrer l'adresse email..">
                            </div>
                            @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">Annuler</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


