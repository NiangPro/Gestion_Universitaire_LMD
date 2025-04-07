<div>
    <form wire:submit='store' class="col-md-6 container">
        <div class="form-group">
            <label class="font-weight-bold">Nom de la filière</label>
            <input type="text" 
                wire:model='nom' 
                class="form-control @error('nom') is-invalid @enderror" 
                placeholder="Veuillez entrer le nom">
            @error('nom') 
                <span class="invalid-feedback">{{ $message }}</span> 
            @enderror
        </div>

        <div class="form-group">
            <label class="font-weight-bold">Département</label>
            <select wire:model='departement_id' 
                class="form-control @error('departement_id') is-invalid @enderror">
                <option value="">Sélectionner un département</option>
                @foreach($depts as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->nom }}</option>
                @endforeach
            </select>
            @error('departement_id') 
                <span class="invalid-feedback">{{ $message }}</span> 
            @enderror
        </div>

        <div class="form-group mt-4">
            @if($id)
                <button type="submit" class="btn btn-warning">
                    <i class="fa fa-edit mr-2"></i>Modifier
                </button>
            @else 
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-plus-circle mr-2"></i>Ajouter
                </button>
            @endif
            <button type="button" wire:click="changeStatus('list')" class="btn btn-secondary ml-2">
                <i class="fa fa-times mr-2"></i>Annuler
            </button>
        </div>
    </form>
</div>