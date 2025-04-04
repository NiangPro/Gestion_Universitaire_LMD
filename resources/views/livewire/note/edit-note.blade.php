<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Modifier la note</h3>
        </div>
        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Étudiant</label>
                        <input type="text" class="form-control" readonly 
                            value="{{ $etudiant->prenom }} {{ $etudiant->nom }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Matière</label>
                        <input type="text" class="form-control" readonly 
                            value="{{ $matiere->nom }}">
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Type d'évaluation</label>
                        <select wire:model="type_evaluation" class="form-control">
                            <option value="CC">Contrôle Continu</option>
                            <option value="TP">Travaux Pratiques</option>
                            <option value="Examen">Examen</option>
                        </select>
                        @error('type_evaluation') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Note</label>
                        <input type="number" wire:model="valeur_note" class="form-control" 
                            step="0.01" min="0" max="20">
                        @error('valeur_note') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Semestre</label>
                        <select wire:model="semestre_id" class="form-control">
                            @foreach(\App\Models\Semestre::all() as $semestre)
                                <option value="{{ $semestre->id }}">{{ $semestre->nom }}</option>
                            @endforeach
                        </select>
                        @error('semestre_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <label>Observation</label>
                <textarea wire:model="observation" class="form-control" rows="3"></textarea>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                <button type="button" class="btn btn-secondary" wire:click="resetEdit">Retour</button>
            </div>
        </div>
    </div>
</div> 