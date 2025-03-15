<div>
    {{-- The Master doesn't talk, he acts. --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Gestion des Notes</h3>
            <button type="button" class="btn btn-primary" wire:click="$set('showModal', true)">
                <i class="fas fa-plus"></i> Ajouter une note
            </button>
        </div>
        
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Filtres -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Rechercher un étudiant..." 
                           wire:model.debounce.300ms="searchTerm">
                </div>
                <div class="col-md-3">
                    <select class="form-control" wire:model="filterType">
                        <option value="">Tous les types</option>
                        <option value="CC">Contrôle Continu</option>
                        <option value="TP">Travaux Pratiques</option>
                        <option value="Examen">Examen</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" wire:model="filterSemestre">
                        <option value="">Tous les semestres</option>
                        <option value="1">Semestre 1</option>
                        <option value="2">Semestre 2</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-secondary" wire:click="resetFilters">
                        Réinitialiser
                    </button>
                </div>
            </div>

            <!-- Modal d'ajout de note -->
            @if($showModal)
            <div class="modal show d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Ajouter une note</h5>
                            <button type="button" class="close" wire:click="$set('showModal', false)">
                                <span>&times;</span>
                            </button>
                        </div>
                        <form wire:submit.prevent="sauvegarderNote">
                            <div class="modal-body">
                                <div class="row">
                                    <!-- Sélection de la classe -->
                                    <div class="form-group col-md-6">
                                        <label>Classe</label>
                                        <select wire:model="classe_id" class="form-control" wire:change="loadEtudiants">
                                            <option value="">Sélectionner une classe</option>
                                            @foreach($classes as $classe)
                                                <option value="{{ $classe->id }}">
                                                    {{ $classe->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('classe_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Sélection de l'étudiant (apparaît après sélection de la classe) -->
                                    @if($classe_id)
                                    <div class="form-group col-md-6">
                                        <label>Étudiant</label>
                                        <select wire:model="etudiant_id" class="form-control">
                                            <option value="">Sélectionner un étudiant</option>
                                            @foreach($etudiants as $etudiant)
                                                <option value="{{ $etudiant->id }}">
                                                    {{ $etudiant->nom }} {{ $etudiant->prenom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('etudiant_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    @endif

                                    <div class="form-group col-md-6">
                                        <label>Note (/20)</label>
                                        <input type="number" class="form-control" wire:model="note" min="0" max="20" step="0.25">
                                        @error('note') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Coefficient</label>
                                        <select  wire:model="coefficient" class="form-control">
                                            <option value="">Sélectionner un coefficient</option>
                                            @foreach($coefficients as $c)
                                                <option value="{{ $c->id }}">
                                                    {{ $c->valeur }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('coefficient') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Type d'évaluation</label>
                                        <select wire:model="type_evaluation" class="form-control">
                                            <option value="">Sélectionner le type</option>
                                            <option value="CC">Contrôle Continu</option>
                                            <option value="TP">Travaux Pratiques</option>
                                            <option value="Examen">Examen</option>
                                        </select>
                                        @error('type_evaluation') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Date d'évaluation</label>
                                        <input type="date" class="form-control" wire:model="date_evaluation">
                                        @error('date_evaluation') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Semestre</label>
                                        <select wire:model="semestre" class="form-control">
                                            <option value="">Sélectionner le semestre</option>
                                            <option value="1">Semestre 1</option>
                                            <option value="2">Semestre 2</option>
                                        </select>
                                        @error('semestre') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Observation</label>
                                        <textarea class="form-control" wire:model="observation"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Fermer</button>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
            @endif

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
                                <button class="btn btn-sm btn-info" wire:click="edit({{ $note->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $note->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $notes->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('swal:confirm', event => {
        Swal.fire({
            title: event.detail.title,
            text: event.detail.text,
            icon: event.detail.type,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteNote', { noteId: event.detail.id });
            }
        })
    });
</script>
@endpush
