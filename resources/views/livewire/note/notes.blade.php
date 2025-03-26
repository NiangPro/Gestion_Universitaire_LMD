<div>
    {{-- The Master doesn't talk, he acts. --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Gestion des Notes</h3>
            <div>
                @if(!$showModal)
            <button type="button" class="btn btn-primary" wire:click="$set('showModal', true)">
                        <i class="fas fa-plus"></i> Ajouter des notes
                    </button>
                @else
                    <button type="button" class="btn btn-warning" wire:click="$set('showModal', false)">
                        <i class="fas fa-list"></i> Voir la liste
            </button>
                @endif
            </div>
        </div>
        
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filtres différents selon le mode -->
            @if(!$showModal)
                <!-- Filtres pour la liste des notes -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <select wire:model.live="academic_year_id" class="form-control">
                        <option value="">Sélectionner l'année académique</option>
                        @foreach($academic_years as $year)
                            <option value="{{ $year->id }}">
                                @if($year->en_cours)
                                    {{ $year->nom }} (En cours)
                                @else
                                    {{ date('Y', strtotime($year->debut)) }} - {{ date('Y', strtotime($year->fin)) }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select wire:model.live="classe_id" class="form-control">
                        <option value="">Sélectionner une classe</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}">
                                {{ $classe->nom }} - {{ strtolower($classe->filiere->nom) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select wire:model.live="semestre_id" class="form-control">
                        <option value="">Sélectionner le semestre</option>
                        @foreach($semestres as $semestre)
                            <option value="{{ $semestre->id }}">{{ $semestre->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Indicateur de chargement -->
            <div wire:loading class="text-center my-2">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
            </div>
            @else
                <!-- Filtres pour l'ajout de notes -->
                <div class="row mb-3">
                    <div class="col-md-3 mb-3">
                        <select wire:model="classe_id" class="form-control" wire:change="loadEtudiants">
                            <option value="">Sélectionner une classe</option>
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}">
                                    {{ $classe->nom }} - {{ strtolower($classe->filiere->nom) }}
                                </option>
                            @endforeach
                        </select>
                        @error('classe_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @if($classe_id)
                    <div class="col-md-3 mb-3">
                        <select wire:model="ue_id" class="form-control" wire:change="loadMatieres">
                            <option value="">Sélectionner une UE</option>
                            @foreach($uniteEnseignements as $ue)
                                <option value="{{ $ue->id }}">{{ $ue->nom }}</option>
                            @endforeach
                        </select>
                        @error('ue_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @endif
                    @if($ue_id)
                    <div class="col-md-3 mb-3">
                        <select wire:model="matiere_id" class="form-control">
                            <option value="">Sélectionner une matière</option>
                            @foreach($matieres as $matiere)
                                <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                            @endforeach
                        </select>
                        @error('matiere_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @endif
                    <div class="col-md-3 mb-3">
                        <select wire:model="semestre_id" class="form-control">
                            <option value="">Sélectionner le semestre</option>
                            @foreach($semestres as $semestre)
                                <option value="{{ $semestre->id }}">{{ $semestre->nom }}</option>
                            @endforeach
                        </select>
                        @error('semestre_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            @endif

            @if($showModal)
                <form wire:submit.prevent="sauvegarderNote">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Note (/20)</th>
                                    <th>Coefficient</th>
                                    <th>Type</th>
                                    <th>Observation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($etudiants as $etudiant)
                                    <tr>
                                        <td>{{ $etudiant->nom }} {{ $etudiant->prenom }}</td>
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
                                                    wire:model="notes.{{ $etudiant->id }}.coefficient_id">
                                                <option value="">Coefficient</option>
                                                @foreach($coefficients as $coef)
                                                    <option value="{{ $coef->id }}">{{ $coef->valeur }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm" 
                                                    wire:model="notes.{{ $etudiant->id }}.type_evaluation">
                                                <option value="CC">CC</option>
                                                <option value="TP">TP</option>
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
            @else
                <table class="table">
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Matière</th>
                        <th>Note</th>
                        <th>Type</th>
                        <th>Coefficient</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                        @forelse($notesList as $note)
                        <tr>
                            <td>{{ $note->etudiant->nom }} {{ $note->etudiant->prenom }}</td>
                            <td>{{ $note->matiere->nom }}</td>
                            <td>{{ $note->note }}/20</td>
                            <td>{{ $note->type_evaluation }}</td>
                            <td>{{ $note->coefficient->valeur }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" wire:click="edit({{ $note->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $note->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Aucune note trouvée</td>
                            </tr>
                        @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $notesList->links() }}
            </div>
            @endif
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
