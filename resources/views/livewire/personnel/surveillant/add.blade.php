<div class="card">
    <div class="card-header row">
        <h3 class="col-md-10">Formulaire d'@if($status == 'edit') édition @else ajout @endif surveillant</h3>
        <div class="col-md-2 text-right">
            <button wire:click='changeStatus("list")' class="btn btn-warning"><span class="btn-icon-left text-warning"><i class="fa fa-hand-o-left"></i></span>Retour</button>
        </div>
    </div>
    <div class="card-body">
       <form wire:submit='store'>
        <div class="row">
            <div class="form-group col-md-6">
                <label><strong>Prénom</strong></label>
                <input type="text" wire:model='prenom' class="form-control @error('prenom') error @enderror" placeholder="Veuillez entrer le prenom">
                @error('prenom') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-md-6">
                <label><strong>Nom</strong></label>
                <input type="text" wire:model='nom' class="form-control @error('nom') error @enderror" placeholder="Veuillez entrer le prenom">
                @error('nom') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-md-6">
                <label><strong>Nom d'utilisateur</strong></label>
                <input type="text" wire:model='username' class="form-control @error('username') error @enderror" placeholder="Veuillez entrer votre username">
                @error('username') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-md-6">
                <label><strong>Adresse</strong></label>
                <input type="text" wire:model='adresse' class="form-control @error('adresse') error @enderror" placeholder="Veuillez entrer votre adresse">
                @error('adresse') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-md-6">
                <label><strong>Sexe</strong></label>
                <select class="form-control @error('sexe') error @enderror" wire:model='sexe'>
                    <option>Veuillez selectionner</option>
                    <option value="Homme">Homme</option>
                    <option value="Femme">Femme</option>
                </select>
                @error('sexe') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-md-6">
                <label><strong>Téléphone</strong></label>
                <input type="tel" wire:model='tel' class="form-control @error('tel') error @enderror" placeholder="Veuillez entrer votre numéro de téléphone">
                @error('tel') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-md-6">
                <label><strong>Email</strong></label>
                <input type="email" wire:model='email' class="form-control @error('email') error @enderror" placeholder="Veuillez entrer votre email">
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            
        </div>
        @if($status == 'edit')
        <button type="submit" class="btn btn-warning">Modifier</button>
        @else 
        <button type="submit" class="btn btn-success">Ajouter</button>
        @endif
       </form>
    </div>
</div>
