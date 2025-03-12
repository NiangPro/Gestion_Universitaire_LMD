<div>
    {{-- The Master doesn't talk, he acts. --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Gestion des Notes</h3>
        </div>
        
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Formulaire d'ajout de note -->
            <form wire:submit.prevent="sauvegarderNote">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Étudiant</label>
                            <select wire:model="etudiant_id" class="form-control">
                                <option value="">Sélectionner un étudiant</option>
                                @foreach($etudiants as $etudiant)
                                    <option value="{{ $etudiant->id }}">
                                        {{ $etudiant->nom }} {{ $etudiant->prenom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Autres champs du formulaire -->
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>

            <!-- Tableau des notes -->
            <table class="table mt-4">
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Cours</th>
                        <th>Note</th>
                        <th>Type</th>
                        <th>Coefficient</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notes as $note)
                        <tr>
                            <td>{{ $note->etudiant->nom }} {{ $note->etudiant->prenom }}</td>
                            <td>{{ $note->cours->nom }}</td>
                            <td>{{ $note->note }}/20</td>
                            <td>{{ $note->type_evaluation }}</td>
                            <td>{{ $note->coefficient }}</td>
                            <td>{{ $note->date_evaluation }}</td>
                            <td>
                                <!-- Boutons d'action -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
