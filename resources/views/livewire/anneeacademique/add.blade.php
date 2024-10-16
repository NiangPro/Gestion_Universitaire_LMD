<div>
    <form wire:submit='store' class="col-md-6 container">
        <div class="form-group">
            <label><strong>Date d'ouverture</strong></label>
            <input type="date" wire:model='debut' class="form-control @error('debut') error @enderror" placeholder="Veuillez entrer la date d'ouverture">
            @error('debut') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label><strong>Date de fermeture</strong></label>
            <input type="date" wire:model='fin' class="form-control @error('fin') error @enderror" placeholder="Veuillez entrer la date de fermeture">
            @error('fin') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-success">Ajouter</button>
    </form>
</div>