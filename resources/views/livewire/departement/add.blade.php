<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent='store'>
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Nom du département</strong></label>
                        <input type="text" 
                            wire:model='nom' 
                            class="form-control @error('nom') is-invalid @enderror" 
                            placeholder="Entrez le nom du département"
                            @if(!Auth::user()->hasPermission('departements', 'edit') && $id) disabled @endif>
                        @error('nom') 
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Description</strong></label>
                        <textarea 
                            wire:model='description' 
                            class="form-control @error('description') is-invalid @enderror" 
                            rows="4"
                            placeholder="Décrivez le département"
                            @if(!Auth::user()->hasPermission('departements', 'edit') && $id) disabled @endif></textarea>
                        @error('description') 
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Assigné à</strong></label>
                        <select 
                            wire:model='user_id' 
                            class="form-control @error('user_id') is-invalid @enderror"
                            @if(!Auth::user()->hasPermission('departements', 'edit') && $id) disabled @endif>
                            <option value="">Sélectionner un responsable</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->prenom }} {{ $user->nom }}</option>
                            @endforeach
                        </select>
                        @error('user_id') 
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        @if($id)
                            @if(Auth::user()->hasPermission('departements', 'edit'))
                                <button type="submit" class="btn btn-warning">
                                    <i class="fa fa-save me-2"></i>Modifier
                                </button>
                            @endif
                        @else
                            @if(Auth::user()->hasPermission('departements', 'create'))
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-plus-circle me-2"></i>Ajouter
                                </button>
                            @endif
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>