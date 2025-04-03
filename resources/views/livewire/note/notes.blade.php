<div>
    {{-- The Master doesn't talk, he acts. --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Gestion des Évaluations</h3>
            <div>
                @if(!$showModal && Auth::user()->hasPermission('evaluations', 'create'))
                    <button type="button" class="btn btn-primary" wire:click="$set('showModal', true)">
                        <i class="fas fa-plus"></i> Nouvelle évaluation
                    </button>
                @else
                    <button type="button" class="btn btn-warning" wire:click="$set('showModal', false)">
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

            
            @else
                <!-- Filtres pour l'ajout de notes -->
                                        <div class="row mb-3">
                                            <!-- Classe -->
                                            <div class="col-md-12 mb-3">
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
                                                <div class="col-md-12 mb-3">
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
                                                <div class="col-md-12 mb-3">
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
                                                <div class="col-md-12 mb-3">
                                                    <select wire:model.live="type_evaluation" class="form-control">
                                                        <option value="">4. Sélectionner le type d'évaluation</option>
                                                        <option value="CC">Contrôle Continu</option>
                                                        <option value="TP">Travaux Pratiques</option>
                                                        <option value="Examen">Examen</option>
                                                    </select>
                                                    @error('type_evaluation') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            @endif

                    <!-- Semestre (visible uniquement si type d'évaluation sélectionné) -->
                    @if(!empty($matiere_id) && !empty($type_evaluation))
                        <div class="col-md-12 mb-3">
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
                                        <button class="btn btn-sm btn-danger" 
                                                wire:click="confirmDelete({{ $note->id }})"
                                                onclick="confirm('Êtes-vous sûr ?') || event.stopImmediatePropagation()">
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
