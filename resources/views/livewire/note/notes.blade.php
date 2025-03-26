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
            <!-- Indicateur de chargement -->
            <div wire:loading class="text-center my-2">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
            </div>

            @if($showModal)
                @include('livewire.note.add-note')
            @else
                <table class="table table-bordered table-striped">
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
                                <button class="btn btn-sm btn-primary btn-sm rounded-pill" wire:click="edit({{ $note->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-warning btn-sm rounded-pill" wire:click="confirmDelete({{ $note->id }})">
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
