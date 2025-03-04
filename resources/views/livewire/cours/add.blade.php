<div>
    <div class="card">
        <div class="card-body">
            <form wire:submit.prevent="save">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="professeur_id">Professeur</label>
                        <select class="form-control @error('professeur_id') is-invalid @enderror"
                            id="professeur_id" wire:model="professeur_id">
                            <option value="">Sélectionner un professeur</option>
                            @foreach($professeurs as $professeur)
                            <option value="{{ $professeur->id }}">{{ $professeur->prenom }} {{ $professeur->nom }}</option>
                            @endforeach
                        </select>
                        @error('professeur_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="classe_id">Classe</label>
                        <select class="form-control @error('classe_id') is-invalid @enderror"
                            id="classe_id" wire:model.live="classe_id">
                            <option value="">Sélectionner une classe</option>
                            @foreach($classes as $classe)
                            <option value="{{ $classe->id }}">{{ $classe->nom }} - {{ $classe->filiere->nom }}</option>
                            @endforeach
                        </select>
                        @error('classe_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>



                </div>
                @if($classe_id)
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="matiere_id">Matière</label>
                        <select class="form-control @error('matiere_id') is-invalid @enderror"
                            id="matiere_id" wire:model="matiere_id">
                            <option value="">Sélectionner une matière</option>
                            @foreach($matieres as $matiere)
                            <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                            @endforeach
                        </select>
                        @error('matiere_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="salle_id">Salle</label>
                        <select class="form-control @error('salle_id') is-invalid @enderror"
                            id="salle_id" wire:model="salle_id">
                            <option value="">Sélectionner une salle</option>
                            @foreach($salles as $salle)
                            <option value="{{ $salle->id }}">{{ $salle->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="heure_debut">Heure de début</label>
                        <input type="time" class="form-control @error('heure_debut') is-invalid @enderror"
                            id="heure_debut" wire:model="heure_debut">
                        @error('heure_debut')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="heure_fin">Heure de fin</label>
                        <input type="time" class="form-control @error('heure_fin') is-invalid @enderror"
                            id="heure_fin" wire:model="heure_fin">
                        @error('heure_fin')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="statut">Statut</label>
                        <select class="form-control @error('statut') is-invalid @enderror"
                            id="statut" wire:model="statut">
                            <option value="en attente">En attente</option>
                            <option value="encours">En cours</option>
                            <option value="terminé">Terminé</option>
                        </select>
                        @error('statut')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="semaine_id">Semaine</label>
                        <select class="form-control @error('semaine_id') is-invalid @enderror"
                            id="semaine_id" wire:model="semaine_id">
                            <option value="">Sélectionner le jour</option>
                            @foreach($semaines as $semaine)
                            <option value="{{ $semaine->id }}">{{ $semaine->jour }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-right">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Annuler</button>
                    @if($id)
                    <button type="submit" class="btn btn-warning">Modifier</button>
                    @else
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>