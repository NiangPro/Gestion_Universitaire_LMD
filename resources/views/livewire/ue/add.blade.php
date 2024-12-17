<div>
    <form wire:submit='store' class="col-md-6 container">
        <div class="form-group">
            <label><strong>Nom</strong></label>
            <input type="text" wire:model='nom' class="form-control @error('nom') error @enderror" placeholder="Veuillez entrer le nom">
            @error('nom') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label><strong>Filière</strong></label>
            <select wire:model='filiere_id' class="form-control @error('filiere_id') error @enderror">
                <option value="">Veuillez selectionner un filière</option>
                @foreach($filieres as $f)
                    <option value="{{ $f->id}}">{{ $f->nom}}</option>
                @endforeach
            </select>
            @error('filiere_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label><strong>Niveau d'étude</strong></label>
            <select wire:model='niveau_etude_id' class="form-control @error('niveau_etude_id') error @enderror">
                <option value="">Veuillez selectionner un niveau d'étude</option>
                @foreach($niveauEtude as $n)
                    <option value="{{ $n->id}}">{{ $n->nom}}</option>
                @endforeach
            </select>
            @error('niveau_etude_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label><strong>Crédit</strong></label>
            <input type="number" min="0" wire:model='credit' class="form-control @error('credit') error @enderror" placeholder="Veuillez entrer le nombre de credit">
            @error('credit') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        @if($id)
            <button type="submit" class="btn btn-warning">Modifier</button>
        @else 
            <button type="submit" class="btn btn-success">Ajouter</button>
        @endif 
    </form>
</div>