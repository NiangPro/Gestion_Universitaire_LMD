<form wire:submit.prevent="sauvegarderNote">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Étudiant</th>
                    <th>Note (/20)</th>
                    <th>Type</th>
                    <th>Observation</th>
                </tr>
            </thead>
            <tbody>
                @foreach($etudiants as $etudiant)
                    <tr>
                        <td>
                            <h6 class="mb-0">{{ $etudiant->prenom }} {{ $etudiant->nom }}</h6>
                            <small class="text-muted">
                                {{ $etudiant->matricule }}
                            </small>
                        </td>
                        <td>
                            <input type="number" 
                                   class="form-control form-control-sm" 
                                   wire:model="notes.{{ $etudiant->id }}.note"
                                   min="0" 
                                   max="20" 
                                   step="0.25">
                        </td>
                        <td>
                            <select class="form-control form-control-sm" 
                                    wire:model="notes.{{ $etudiant->id }}.type_evaluation">
                                <option value="">Sélectionner le type</option>
                                <option value="CC">Contrôle Continu</option>
                                <option value="TP">Travaux Pratiques</option>
                                <option value="Examen">Examen</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" 
                                   class="form-control form-control-sm" 
                                   wire:model="notes.{{ $etudiant->id }}.observation">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="text-right mt-3">
        <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Annuler</button>
        <button type="submit" class="btn btn-primary">Enregistrer les notes</button>
    </div>
</form>