<div>
    <div class="container-fluid py-4">
        <!-- Dashboard Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $evaluations->total() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Planifiées</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $evaluations->where('statut', 'planifié')->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-left-warning shadow h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">En Cours</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $evaluations->where('statut', 'en_cours')->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Terminées</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $evaluations->where('statut', 'terminé')->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div class="form-group mb-md-0">
                            <label for="annee_academique" class="form-label">Année Académique</label>
                            <select wire:model.live="annee_academique_id" id="annee_academique" class="form-control">
                                <option value="">Toutes les années</option>
                                @foreach($anneeAcademiques as $annee)
                                    <option value="{{ $annee->id }}">{{ date("Y", strtotime($annee->debut)) . '-' . date("Y", strtotime($annee->fin)) }}{{ $annee->encours ? ' (en cours)' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-md-0">
                            <label for="semestre" class="form-label">Semestre</label>
                            <select wire:model.live="semestre_id" id="semestre" class="form-control">
                                <option value="">Tous les semestres</option>
                                @foreach($semestres as $semestre)
                                    <option value="{{ $semestre->id }}">{{ $semestre->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Rechercher une évaluation...">
                            <button class="btn btn-primary" wire:click="showModal">
                                <i class="fas fa-plus"></i> Nouvelle évaluation
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Liste des évaluations</h3>
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
                                            <button class="btn btn-sm btn-info" wire:click="showDetails({{ $evaluation->id }})" title="Voir les détails">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" wire:click="edit({{ $evaluation->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <!-- Modal de confirmation de suppression -->
                                            <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true" wire:ignore.self>
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirmation de suppression</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Êtes-vous sûr de vouloir supprimer cette évaluation ? <br> Cette action est irréversible.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                            <button type="button" class="btn btn-danger" wire:click="confirmDelete">Confirmer la suppression</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                document.addEventListener('livewire:initialized', () => {
                                                    Livewire.on('showDeleteModal', () => {
                                                        $('#deleteConfirmationModal').modal('show');
                                                    });
                                                    Livewire.on('closeDeleteModal', () => {
                                                        $('#deleteConfirmationModal').modal('hide');
                                                    });
                                                });
                                            </script>
                                            <button class="btn btn-sm btn-danger" wire:click="showDeleteModal({{ $evaluation->id }})" title="Supprimer">
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

        <!-- Modal Détails -->
        <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailsModalLabel">Détails de l'évaluation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if($selectedEvaluation)
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <p><strong>Titre:</strong> <span class="text-break">{{ $selectedEvaluation->titre }}</span></p>
                                <p><strong>Type:</strong> <span class="text-break">{{ $selectedEvaluation->typeEvaluation->nom }}</span></p>
                                <p><strong>Matière:</strong> <span class="text-break">{{ $selectedEvaluation->matiere->nom }}</span></p>
                                <p><strong>Date:</strong> {{ $selectedEvaluation->date_evaluation->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-12 col-md-6">
                                <p><strong>Heure de début:</strong> {{ \Carbon\Carbon::parse($selectedEvaluation->heure_debut)->format('H:i') }}</p>
                                <p><strong>Durée:</strong> {{ $selectedEvaluation->duree }} minutes</p>
                                <p><strong>Statut:</strong> 
                                    <span class="badge badge-{{ $selectedEvaluation->statut === 'planifié' ? 'info' : 
                                        ($selectedEvaluation->statut === 'en_cours' ? 'warning' : 
                                        ($selectedEvaluation->statut === 'terminé' ? 'success' : 'danger')) }}">
                                        {{ $selectedEvaluation->statut }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h6>Classes concernées:</h6>
                            <div class="row">
                                @foreach($selectedEvaluation->classes as $classe)
                                    <div class="col-6 col-sm-4 col-md-3 mb-2">
                                        <span class="badge badge-primary d-inline-block text-truncate w-100">{{ $classe->nom }} ({{ $classe->filiere->nom }})</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="evaluationModal" tabindex="-1" role="dialog" aria-labelledby="evaluationModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="evaluationModalLabel">{{ $mode === 'edit' ? 'Modifier l\'évaluation' : 'Nouvelle évaluation' }}</h5>
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
                                        <label>Classes <span class="text-danger">*</span></label>
                                        <div class="row">
                                            @foreach($allClasses as $classe)
                                                <div class="col-md-3">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" 
                                                               id="classe_{{ $classe->id }}" 
                                                               wire:model="classes" value="{{ $classe->id }}">
                                                        <label class="custom-control-label" for="classe_{{ $classe->id }}">{{ $classe->nom }} ({{ $classe->filiere->nom }})</label>
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

            Livewire.on('showModal', () => {
                $('#evaluationModal').modal('show');
            });
        });
    </script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('showDetailsModal', () => {
                $('#detailsModal').modal('show');
            });
            Livewire.on('closeDetailsModal', () => {
                $('#detailsModal').modal('hide');
            });
        });
    </script>
    @endpush
</div>