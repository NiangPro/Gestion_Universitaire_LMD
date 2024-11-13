<div>
    <form wire:submit='store' class="col-md-6 container">
        <div class="form-group">
            <label><strong>Nom</strong></label>
            <input type="text" wire:model='nom' class="form-control @error('nom') error @enderror" placeholder="Veuillez entrer le nom">
            @error('nom') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label><strong>Département</strong></label>
            <select wire:model='departement_id' class="form-control @error('departement_id') error @enderror">
                <option value="">Veuillez selectionner un departement</option>
                @foreach($depts as $d)
                    <option value="{{ $d->id}}">{{ $d->nom}}</option>
                @endforeach
            </select>
            @error('departement_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        @if($id)
        <button type="submit" class="btn btn-warning">Modifier</button>
        @else 
        <button type="submit" class="btn btn-success">Ajouter</button>
        @endif 
    </form>
</div>