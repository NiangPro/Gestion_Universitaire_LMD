<div>
    <form wire:submit='store' class="col-md-6 container">
        <div class="form-group">
            <label><strong>Nom</strong></label>
            <input type="text" wire:model='nom' class="form-control @error('nom') error @enderror" placeholder="Veuillez entrer le nom">
            @error('nom') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label><strong>Déscription</strong></label>
            <textarea wire:model='description' class="form-control @error('description') error @enderror" placeholder="Veuillez entrer la description"></textarea>
            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        @if($id)
        <button type="submit" class="btn btn-warning">Modifier</button>
        @else 
        <button type="submit" class="btn btn-success">Ajouter</button>
        @endif 
    </form>
</div>