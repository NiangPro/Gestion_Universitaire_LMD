<div class="container-fluid">
    <div class="row page-titles mx-0">
        <div class="col-sm-6">
            <div class="welcome-text">
                <h4>{{ $title }}</h4>
            </div>
        </div>
        <div class="col-sm-6 text-right">
            <button wire:click="changeStatus('list')" class="btn btn-warning">
                <i class="fa fa-arrow-left mr-2"></i>Retour
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="store" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Prénom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="prenom">
                                    @error('prenom')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="nom">
                                    @error('nom')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nom d'utilisateur <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="username">
                                    @error('username')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" wire:model="email">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Téléphone <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="tel">
                                    @error('tel')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sexe <span class="text-danger">*</span></label>
                                    <select class="form-control" wire:model="sexe">
                                        <option value="">Sélectionner</option>
                                        <option value="Homme">Homme</option>
                                        <option value="Femme">Femme</option>
                                    </select>
                                    @error('sexe')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Adresse <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="adresse">
                                    @error('adresse')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Spécialité <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="specialite">
                                    @error('specialite')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Photo</label>
                                    <input type="file" class="form-control" wire:model="photo" accept="image/*">
                                    @error('photo')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @if($photo)
                                        <img src="{{ $photo->temporaryUrl() }}" class="mt-2" width="100">
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save mr-2"></i>
                                {{ $id ? 'Modifier' : 'Enregistrer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>