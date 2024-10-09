<div class="card">
    <div class="card-header row">
        <h3 class="col-md-10">Formulaire d'@if($status == 'edit') édition @else ajout @endif pack</h3>
        <div class="col-md-2 text-right">
            <button wire:click='changeStatus("list")' class="btn btn-warning"><span class="btn-icon-left text-warning"><i class="fa fa-hand-o-left"></i></span>Retour</button>
        </div>
    </div>
    <div class="card-body">
       <form wire:submit='store'>
        <div class="row">
            <div class="form-group col-md-4">
                <label><strong>Nom</strong></label>
                <input type="text" wire:model='nom' class="form-control @error('nom') error @enderror" placeholder="Veuillez entrer le nom">
                @error('nom') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-md-4">
                <label><strong>Nombre total d'élèves</strong></label>
                <input type="number" wire:model='limite' min="0" class="form-control @error('limite') error @enderror" placeholder="Veuillez entrer le nombre total d'élèves">
                @error('limite') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-md-4">
                <label><strong>Montant mensuel</strong></label>
                <input type="number" min="0" wire:model='mensuel' class="form-control @error('mensuel') error @enderror" placeholder="Veuillez entrer le montant mensuel">
                @error('mensuel') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group col-md-4">
                <label><strong>Montant annuel</strong></label>
                <input type="number" min="0" wire:model='annuel' class="form-control @error('annuel') error @enderror" placeholder="Veuillez entrer le montant annuel">
                @error('annuel') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group col-md-4">
                <label><strong>Couleur d'arrière plan</strong></label>
                <input type="color" wire:model='couleur' class="form-control @error('couleur') error @enderror" placeholder="Veuillez entrer la couleur d'arrière du pack">
                @error('couleur') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-md-4">
                <label><strong>Couleur du texte</strong></label>
                <input type="color" wire:model='text' class="form-control @error('text') error @enderror" placeholder="Veuillez entrer la couleur du texte du pack">
                @error('text') <span class="text-danger">{{ $message }}</span> @enderror
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