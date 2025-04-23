<div class="container-fluid">
    <!-- Dashboard Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Évaluations</h5>
                    <h2 class="mb-0">{{ $evaluations->total() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Planifiées</h5>
                    <h2 class="mb-0">{{ $evaluations->where('statut', 'planifié')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">En Cours</h5>
                    <h2 class="mb-0">{{ $evaluations->where('statut', 'en_cours')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Terminées</h5>
                    <h2 class="mb-0">{{ $evaluations->where('statut', 'terminé')->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des évaluations</h3>
                    <div class="card-tools">
                        <button class="btn btn-primary btn-sm mr-2" wire:click="showModal">
                            <i class="fas fa-plus"></i> Nouvelle évaluation
                        </button>
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" wire:model.debounce.300ms="search" 
                                   class="form-control float-right" placeholder="Rechercher...">
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Matière</th>
                                <th>Date</th>
                                <th>Heure</th>
                                <th>Durée</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($evaluations as $evaluation)
                                <tr>
                                    <td>{{ $evaluation->titre }}</td>
                                    <td>{{ $evaluation->typeEvaluation->nom }}</td>
                                    <td>{{ $evaluation->matiere->nom }}</td>
                                    <td>{{ $evaluation->date_evaluation->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($evaluation->heure_debut)->format('H:i') }}</td>
                                    <td>{{ $evaluation->duree }} min</td>
                                    <td>
                                        <span class="badge badge-{{ $evaluation->statut === 'planifié' ? 'info' : 
                                            ($evaluation->statut === 'en_cours' ? 'warning' : 
                                            ($evaluation->statut === 'terminé' ? 'success' : 'danger')) }}">
                                            {{ $evaluation->statut }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" wire:click="edit({{ $evaluation->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" 
                                                wire:click="delete({{ $evaluation->id }})" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette évaluation ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Aucune évaluation trouvée</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $evaluations->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('showToast', (event) => {
                iziToast[event.type]({
                    message: event.message,
                    position: 'topRight'
                });
            });

            Livewire.on('evaluation-saved', () => {
                $('#evaluationModal').modal('hide');
            });

            Livewire.on('closeModal', () => {
                $('#evaluationModal').modal('hide');
            });
        });
    </script>
    @endpush

    <!-- Modal -->
    <div class="modal fade" id="evaluationModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $mode === 'edit' ? 'Modifier l\'évaluation' : 'Nouvelle évaluation' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="titre">Titre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('titre') is-invalid @enderror" 
                                           id="titre" wire:model="titre" placeholder="Titre de l'évaluation">
                                    @error('titre') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type_evaluation_id">Type d'évaluation <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type_evaluation_id') is-invalid @enderror" 
                                            id="type_evaluation_id" wire:model="type_evaluation_id">
                                        <option value="">Sélectionner un type</option>
                                        @foreach($typeEvaluations as $type)
                                            <option value="{{ $type->id }}">{{ $type->nom }}</option>
                                        @endforeach
                                    </select>
                                    @error('type_evaluation_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_evaluation">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date_evaluation') is-invalid @enderror" 
                                           id="date_evaluation" wire:model="date_evaluation">
                                    @error('date_evaluation') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="heure_debut">Heure de début <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('heure_debut') is-invalid @enderror" 
                                           id="heure_debut" wire:model="heure_debut">
                                    @error('heure_debut') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="duree">Durée (minutes) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('duree') is-invalid @enderror" 
                                           id="duree" wire:model="duree" min="1">
                                    @error('duree') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="matiere_id">Matière <span class="text-danger">*</span></label>
                                    <select class="form-control @error('matiere_id') is-invalid @enderror" 
                                            id="matiere_id" wire:model="matiere_id">
                                        <option value="">Sélectionner une matière</option>
                                        @foreach($matieres as $matiere)
                                            <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                                        @endforeach
                                    </select>
                                    @error('matiere_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="statut">Statut <span class="text-danger">*</span></label>
                                    <select class="form-control @error('statut') is-invalid @enderror" 
                                            id="statut" wire:model="statut">
                                        <option value="planifié">Planifié</option>
                                        <option value="en_cours">En cours</option>
                                        <option value="terminé">Terminé</option>
                                        <option value="annulé">Annulé</option>
                                    </select>
                                    @error('statut') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" wire:model="description" rows="3"></textarea>
                                    @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Classes <span class="text-danger">*</span></label>
                                    <div class="row">
                                        @foreach($allClasses as $classe)
                                            <div class="col-md-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" 
                                                           id="classe_{{ $classe->id }}" 
                                                           wire:model="classes" value="{{ $classe->id }}">
                                                    <label class="custom-control-label" for="classe_{{ $classe->id }}">{{ $classe->nom }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('classes') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">
                            {{ $mode === 'edit' ? 'Mettre à jour' : 'Enregistrer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('closeModal', () => {
            $('#evaluationModal').modal('hide');
        });
        Livewire.on('showModal', () => {
            $('#evaluationModal').modal('show');
        });
    });
</script>
@endpush