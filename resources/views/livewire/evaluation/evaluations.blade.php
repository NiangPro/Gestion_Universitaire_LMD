<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h3 mb-2">Gestion des Évaluations</h1>
                            <p class="text-muted mb-0">{{ date("d/m/Y", strtotime(Auth::user()->campus->currentAcademicYear()?->debut)) }} - {{ date("d/m/Y", strtotime(Auth::user()->campus->currentAcademicYear()?->fin)) ?? 'Année académique non définie' }}</p>
                            <small class="text-muted">Semestre en cours : {{ Auth::user()->campus->currentSemestre()?->nom ?? 'Non défini' }}</small>
                        </div>
                        @if(Auth::user()->hasPermission('evaluations', 'create'))
                            <button class="btn btn-primary d-flex align-items-center" wire:click="openModal">
                                <i class="fas fa-plus-circle mr-2"></i> Nouvelle évaluation
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Évaluations</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ App\Models\Evaluation::where('campus_id', Auth::user()->campus_id)->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Planifiées</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ App\Models\Evaluation::where('campus_id', Auth::user()->campus_id)->where('statut', 'planifié')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">En Cours</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ App\Models\Evaluation::where('campus_id', Auth::user()->campus_id)->where('statut', 'en_cours')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Classes Évaluées</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ DB::table('evaluation_classe')->distinct('classe_id')->count('classe_id') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-muted small mb-2">Rechercher</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" wire:model.live="search" class="form-control border-left-0 pl-0" placeholder="Rechercher une évaluation...">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-muted small mb-2">Type d'évaluation</label>
                        <select wire:model.live="type_filter" class="form-control">
                            <option value="">Tous les types</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-muted small mb-2">Année académique</label>
                        <select wire:model.live="year_filter" class="form-control">
                            <option value="">Toutes les années</option>
                            @foreach($academic_years as $year)
                                <option value="{{ $year->id }}">{{ date("Y", strtotime($year->debut)) }} - {{ date("Y", strtotime($year->fin)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-muted small mb-2">Date spécifique</label>
                        <input type="date" wire:model.live="date_filter" class="form-control">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-muted small mb-2">Semestre</label>
                        <select wire:model.live="semestre_filter" class="form-control">
                            <option value="">Tous les semestres</option>
                            @foreach(Auth::user()->campus->semestres as $semestre)
                                <option value="{{ $semestre->id }}">{{ $semestre->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des évaluations -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Titre</th>
                            <th class="border-0">Type</th>
                            <th class="border-0">Date & Heure</th>
                            <th class="border-0">Durée</th>
                            <th class="border-0">Classes</th>
                            <th class="border-0">Matière</th>
                            <th class="border-0">Statut</th>
                            <th class="border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($evaluations as $evaluation)
                            <tr class="align-middle">
                                <td class="fw-medium">{{ $evaluation->titre }}</td>
                                <td>
                                    <span class="badge badge-light text-dark">
                                        {{ $evaluation->typeEvaluation->nom }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-alt text-muted mr-2"></i>
                                        <div>
                                            {{ $evaluation->date_evaluation->format('d/m/Y') }}
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ $evaluation->heure_debut->format('H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-light text-dark">
                                        <i class="fas fa-hourglass-half mr-1"></i>
                                        {{ $evaluation->duree }} min
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($evaluation->classes as $classe)
                                            <span class="badge badge-info">
                                                {{ $classe->nom }} - {{ $classe->filiere->nom }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-light text-dark">
                                        {{ $evaluation->matiere->nom }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusClasses = [
                                            'planifié' => 'badge-primary',
                                            'en_cours' => 'badge-warning',
                                            'terminé' => 'badge-success',
                                            'annulé' => 'badge-danger'
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusClasses[$evaluation->statut] ?? 'bg-secondary' }}">
                                        {{ ucfirst($evaluation->statut) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        @if(Auth::user()->hasPermission('evaluations', 'edit'))
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    wire:click="edit({{ $evaluation->id }})" 
                                                    data-toggle="tooltip" 
                                                    title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif
                                        @if(Auth::user()->hasPermission('evaluations', 'delete'))
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    wire:click="delete({{ $evaluation->id }})" 
                                                    onclick="confirm('Êtes-vous sûr de vouloir supprimer cette évaluation ?') || event.stopImmediatePropagation()"
                                                    data-toggle="tooltip" 
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    Aucune évaluation trouvée
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-4">
                {{ $evaluations->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Évaluation -->
    <div class="modal fade" wire:ignore.self id="evaluationModal" tabindex="-1" role="dialog">
        <script>
            document.addEventListener('show-evaluation-modal', function() {
                $('#evaluationModal').modal('show');
            });
            document.addEventListener('hide-evaluation-modal', function() {
                $('#evaluationModal').modal('hide');
            });
        </script>
        <div class="modal-dialog modal-lg" role="document">
            <form id="evaluationForm" wire:submit.prevent="enregistrerEvaluation">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">
                        <i class="fas {{ $isEditing ? 'fa-edit' : 'fa-plus-circle' }} me-2"></i>
                        {{ $isEditing ? 'Modifier l\'évaluation' : 'Nouvelle évaluation' }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Informations principales -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0 text-muted">Informations principales</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Titre <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('titre') is-invalid @enderror" wire:model="titre" placeholder="Entrez le titre de l'évaluation">
                                        @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Type d'évaluation <span class="text-danger">*</span></label>
                                        <select class="form-control @error('type_evaluation_id') is-invalid @enderror" wire:model="type_evaluation_id">
                                            <option value="">Sélectionner un type...</option>
                                            @foreach($types as $type)
                                                <option value="{{ $type->id }}">{{ $type->nom }}</option>
                                            @endforeach
                                        </select>
                                        @error('type_evaluation_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date et durée -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0 text-muted">Planification</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Date <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            <input type="date" class="form-control @error('date_evaluation') is-invalid @enderror" wire:model="date_evaluation">
                                            @error('date_evaluation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Heure de début <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                            <input type="time" class="form-control @error('heure_debut') is-invalid @enderror" wire:model="heure_debut">
                                            @error('heure_debut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Durée (minutes) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-hourglass-half"></i></span>
                                            <input type="number" class="form-control @error('duree') is-invalid @enderror" wire:model="duree" min="15">
                                            @error('duree') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Matière et Année académique -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0 text-muted">Informations académiques</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Matière <span class="text-danger">*</span></label>
                                        <select class="form-control" wire:model="matiere_id">
                                            <option value="">Sélectionner une matière...</option>
                                            @foreach($matieres as $matiere)
                                                <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                                            @endforeach
                                        </select>
                                        @error('matiere_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Année Académique <span class="text-danger">*</span></label>
                                        <select class="form-control" wire:model="academic_year_id">
                                            <option value="">Sélectionner une année académique...</option>
                                            @foreach($academic_years as $year)
                                                <option value="{{ $year->id }}">{{ date('Y', strtotime($year->debut)) }} - {{ date('Y', strtotime($year->fin)) }}</option>
                                            @endforeach
                                        </select>
                                        @error('academic_year_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Semestre <span class="text-danger">*</span></label>
                                        <select class="form-control" wire:model="semestre_id">
                                            <option value="">Sélectionner un semestre...</option>
                                            @foreach(Auth::user()->campus->semestres as $semestre)
                                                <option value="{{ $semestre->id }}">{{ $semestre->nom }}</option>
                                            @endforeach
                                        </select>
                                        @error('semestre_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Classes -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0 text-muted">Sélection des classes</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach($classes as $classe)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" 
                                                   class="form-check-input" 
                                                   id="classe_{{ $classe->id }}"
                                                   wire:model="selectedClasses"
                                                   value="{{ $classe->id }}">
                                            <label class="form-check-label" for="classe_{{ $classe->id }}">
                                                {{ $classe->nom }} ({{ $classe->filiere->nom }})
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('selectedClasses') <span class="text-danger small mt-2">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Description et Statut -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0 text-muted">Informations complémentaires</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" wire:model="description" rows="3" placeholder="Ajoutez une description détaillée de l'évaluation..."></textarea>
                                        @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Statut <span class="text-danger">*</span></label>
                                        <select class="form-control" wire:model="statut">
                                            <option value="planifié">Planifié</option>
                                            <option value="en_cours">En cours</option>
                                            <option value="terminé">Terminé</option>
                                            <option value="annulé">Annulé</option>
                                        </select>
                                        @error('statut') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="closeModal">
                        <i class="fas fa-times mr-1"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-primary d-flex align-items-center" wire:loading.attr="disabled" wire:target="enregistrerEvaluation">
                        <span wire:loading.remove wire:target="enregistrerEvaluation">
                            <i class="fas fa-save mr-1"></i> {{ $isEditing ? 'Mettre à jour' : 'Enregistrer' }}
                        </span>
                        <span wire:loading wire:target="enregistrerEvaluation" class="d-flex align-items-center">
                            <span class="spinner-border spinner-border-sm mr-2" role="status"></span>
                            Traitement en cours...
                        </span>
                    </button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', function () {
        Livewire.on('success', function(event) {
            $('#evaluationModal').modal('hide');
            iziToast.success({
                title: 'Succès',
                message: event.message,
                position: 'topRight'
            });
        });

        Livewire.on('error', function(event) {
            iziToast.error({
                title: 'Erreur',
                message: event.message,
                position: 'topRight'
            });
        });
    });

    // Gestion du modal avec Bootstrap 4
    Livewire.on('showModal', function() {
        $('#evaluationModal').modal('show');
    });

    Livewire.on('hideModal', function() {
        $('#evaluationModal').modal('hide');
    });

    // Gérer la fermeture du modal
    $('#evaluationModal').on('hidden.bs.modal', function () {
        Livewire.dispatch('closeModal');
    });
</script>
@endpush
