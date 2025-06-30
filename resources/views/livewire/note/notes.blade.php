<div>
    {{-- The Master doesn't talk, he acts. --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Gestion des Évaluations</h3>
            <div>
                @if(!$showModal && Auth::user()->hasPermission('evaluations', 'create'))
                    <button type="button" class="btn btn-primary" wire:click="changeStatut('add')">
                        <i class="fas fa-plus"></i> Nouvelle évaluation
                    </button>
                @else
                    <button type="button" class="btn btn-warning" wire:click="changeStatut('list')">
                        <i class="fas fa-list"></i> Voir la liste
                    </button>
                @endif
            </div>
        </div>
        
        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
                    <select wire:model.live="classe_id" class="form-control" wire:change="loadEtudiants">
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

            <div class="row mb-3">
                <div class="col-md-2">
                    <select wire:model.live="perPage" class="form-control">
                        <option value="10">10 par page</option>
                        <option value="25">25 par page</option>
                        <option value="50">50 par page</option>
                        <option value="100">100 par page</option>
                    </select>
                </div>
            </div>

            
            @elseif(!$isEditing)
                <!-- Filtres pour l'ajout de notes -->
                <div class="row mb-3">
                                            <!-- Classe -->
                                            <div class="col-md-6 mb-3">
                                                <select wire:model.live="classe_id" class="form-control" wire:change="loadEtudiants">
                                                    <option value="">1. Sélectionner une classe</option>
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}">
                                    {{ $classe->nom }} - {{ strtolower($classe->filiere->nom) }}
                                </option>
                            @endforeach
                        </select>
                        @error('classe_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                                            <!-- UE (visible uniquement si classe sélectionnée) -->
                                            @if(!empty($classe_id))
                                                <div class="col-md-6 mb-3">
                                                    <select wire:model.live="ue_id" class="form-control" wire:change="loadMatieres">
                                                        <option value="">2. Sélectionner une UE</option>
                            @foreach($uniteEnseignements as $ue)
                                <option value="{{ $ue->id }}">{{ $ue->nom }}</option>
                            @endforeach
                        </select>
                        @error('ue_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @endif

                                            <!-- Matière (visible uniquement si UE sélectionnée) -->
                                            @if(!empty($ue_id))
                                                <div class="col-md-4 mb-3">
                                                    <select wire:model.live="matiere_id" class="form-control">
                                                        <option value="">3. Sélectionner une matière</option>
                            @foreach($matieres as $matiere)
                                <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                            @endforeach
                        </select>
                        @error('matiere_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @endif

                                            <!-- Type d'évaluation (visible uniquement si matière sélectionnée) -->
                                            @if(!empty($matiere_id))
                                                <div class="col-md-4 mb-3">
                                                    <select wire:model.live="type_evaluation_id" class="form-control">
                                                        <option value="">4. Sélectionner le type d'évaluation</option>
                                                        @foreach($typeEvaluations as $typeEval)
                                                            <option value="{{ $typeEval->id }}">{{ $typeEval->nom }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('type_evaluation_id') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            @endif

                    <!-- Semestre (visible uniquement si type d'évaluation sélectionné) -->
                    @if(!empty($matiere_id) && !empty($type_evaluation_id))
                        <div class="col-md-4 mb-3">
                            <select wire:model.live="semestre_id" class="form-control">
                                <option value="">5. Sélectionner le semestre</option>
                            @foreach($semestres as $semestre)
                                <option value="{{ $semestre->id }}">{{ $semestre->nom }}</option>
                            @endforeach
                        </select>
                        @error('semestre_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @endif
                </div>
            @endif
            <!-- Indicateur de chargement -->
            <div wire:loading class="text-center my-2">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
            </div>

            @if($showModal)
                @if($isEditing && $currentNote)
                    <!-- Formulaire d'édition -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4>Modifier la note</h4>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <strong>Modification de la note</strong><br>
                                Étudiant : {{ $currentNote->etudiant->prenom }} {{ $currentNote->etudiant->nom }}<br>
                                Matière : {{ $currentNote->matiere->nom }}
                            </div>

                            <form wire:submit.prevent="updateNote">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Type d'évaluation</label>
                                            <select wire:model="editNote.type_evaluation_id" class="form-control">
                                                <option value="">Sélectionner le type d'évaluation</option>
                                                @foreach($typeEvaluations as $typeEval)
                                                    <option value="{{ $typeEval->id }}">{{ $typeEval->nom }}</option>
                                                @endforeach
                                            </select>
                                            @error('editNote.type_evaluation_id') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Note /20</label>
                                            <input type="number" wire:model="editNote.valeur" class="form-control" 
                                                   step="0.01" min="0" max="20">
                                            @error('editNote.valeur') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Semestre</label>
                                            <select wire:model="editNote.semestre_id" class="form-control">
                                                <option value="">Sélectionner le semestre</option>
                                                @foreach($semestres as $semestre)
                                                    <option value="{{ $semestre->id }}">{{ $semestre->nom }}</option>
                                                @endforeach
                                            </select>
                                            @error('editNote.semestre_id') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <label>Observation</label>
                                    <textarea wire:model="editNote.observation" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                                    <button type="button" class="btn btn-secondary" wire:click="resetEdit">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Formulaire d'ajout existant -->
                    @if(!empty($classe_id) && !empty($ue_id) && !empty($matiere_id) && !empty($type_evaluation) && !empty($semestre_id))
                        <div class="mt-4">
                            <div class="alert alert-info">
                                <strong>Saisie des notes pour :</strong><br>
                                Classe: {{ $classes->where('id', $classe_id)->first()->nom }}<br>
                                Matière: {{ $matieres->where('id', $matiere_id)->first()->nom }}<br>
                                Type: {{ $type_evaluation }}<br>
                                Semestre: {{ $semestres->where('id', $semestre_id)->first()->nom }}
                            </div>
                @include('livewire.note.add-note')
                        </div>
                    @endif
                @endif
            @else
                <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Matière</th>
                        <th>Note</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                        @forelse($notesList as $note)
                        <tr>
                            <td>
                                <h6 class="mb-0">{{ $note->etudiant->prenom }} {{ $note->etudiant->nom }}</h6>
                                <small class="text-muted">
                                    {{ $note->etudiant->matricule }}
                                </small>
                            </td>
                            <td>{{ $note->matiere->nom }}</td>
                            <td>{{ $note->note }}/20</td>
                            <td>{{ $note->type_evaluation }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    @if(Auth::user()->hasPermission('evaluations', 'edit'))
                                        <button class="btn btn-sm btn-primary" wire:click="edit({{ $note->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endif
                                    
                                    @if(Auth::user()->hasPermission('evaluations', 'delete'))
                                        <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $note->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Aucune note trouvée</td>
                            </tr>
                        @endforelse
                </tbody>
            </table>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        Affichage de {{ $notesList->firstItem() ?? 0 }} à {{ $notesList->lastItem() ?? 0 }} sur {{ $notesList->total() }} notes
                    </div>
                    <div>
                        @if ($notesList->hasPages())
                            <nav aria-label="Page navigation">
                                <ul class="pagination mb-0">
                                    {{-- Lien Previous --}}
                                    <li class="page-item {{ $notesList->onFirstPage() ? 'disabled' : '' }}">
                                        <button class="page-link" wire:click="previousPage" wire:loading.attr="disabled" rel="prev">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                    </li>

                                    {{-- Numéros de pages --}}
                                    @foreach ($notesList->getUrlRange(1, $notesList->lastPage()) as $page => $url)
                                        <li class="page-item {{ $page == $notesList->currentPage() ? 'active' : '' }}">
                                            <button class="page-link" wire:click="gotoPage({{ $page }})">{{ $page }}</button>
                                        </li>
                                    @endforeach

                                    {{-- Lien Next --}}
                                    <li class="page-item {{ !$notesList->hasMorePages() ? 'disabled' : '' }}">
                                        <button class="page-link" wire:click="nextPage" wire:loading.attr="disabled" rel="next">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </li>
                                </ul>
                            </nav>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Confirmation de suppression
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if($noteToDelete)
                        <div class="alert alert-info mb-3">
                            <h6 class="font-weight-bold">Détails de la note :</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td width="120"><strong>Étudiant :</strong></td>
                                    <td>{{ $noteToDelete->etudiant->prenom }} {{ $noteToDelete->etudiant->nom }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Matricule :</strong></td>
                                    <td>{{ $noteToDelete->etudiant->matricule }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Classe :</strong></td>
                                    <td>
                                        @if($noteToDelete->etudiant->inscriptions->first())
                                            {{ $noteToDelete->etudiant->inscriptions->first()->classe->nom }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Matière :</strong></td>
                                    <td>{{ $noteToDelete->matiere->nom }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Note :</strong></td>
                                    <td>{{ $noteToDelete->note }}/20</td>
                                </tr>
                                <tr>
                                    <td><strong>Type :</strong></td>
                                    <td>{{ $noteToDelete->type_evaluation }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Semestre :</strong></td>
                                    <td>{{ $noteToDelete->semestre->nom }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="alert alert-danger mb-0">
                            <i class="fas fa-exclamation-circle"></i>
                            Êtes-vous sûr de vouloir supprimer cette note ? Cette action est irréversible !
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Annuler
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="deleteNote">
                        <i class="fas fa-trash mr-1"></i> Confirmer la suppression
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('showDeleteModal', () => {
            $('#deleteModal').modal('show');
        });

        Livewire.on('hideDeleteModal', () => {
            $('#deleteModal').modal('hide');
        });
    });
</script>
@endpush
