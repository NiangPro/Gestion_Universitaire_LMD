<div class="container-fluid">
    <!-- En-tête avec statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Évaluations</h5>
                    <h2>{{ App\Models\Evaluation::where('campus_id', Auth::user()->campus_id)->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Planifiées</h5>
                    <h2>{{ App\Models\Evaluation::where('campus_id', Auth::user()->campus_id)->where('statut', 'planifié')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">En Cours</h5>
                    <h2>{{ App\Models\Evaluation::where('campus_id', Auth::user()->campus_id)->where('statut', 'en_cours')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Classes Évaluées</h5>
                    <h2>{{ DB::table('evaluation_classe')->distinct('classe_id')->count('classe_id') }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre d'outils -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" wire:model.live="search" class="form-control" placeholder="Rechercher...">
                    </div>
                </div>
                <div class="col-md-5">
                    <select wire:model.live="type_filter" class="form-control">
                        <option value="">Tous les types</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}">{{ $type->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 text-right">
                    @if(Auth::user()->hasPermission('evaluations', 'create'))
                        <button class="btn btn-primary" wire:click="openModal">
                            <i class="fas fa-plus"></i> Nouvelle évaluation
                        </button>
                    @endif
                </div>
                <div class="col-md-6 mt-3">
                    <select wire:model.live="year_filter" class="form-control">
                        <option value="">Toutes les années</option>
                        @foreach($academic_years as $year)
                            <option value="{{ $year->id }}">{{ date("Y", strtotime($year->debut)) }} - {{ date("Y", strtotime($year->fin)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mt-3">
                    <input type="date" wire:model.live="date_filter" class="form-control">
                </div>
                
            </div>
        </div>
    </div>

    <!-- Liste des évaluations -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Date & Heure</th>
                            <th>Durée</th>
                            <th>Classes</th>
                            <th>Matière</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($evaluations as $evaluation)
                            <tr>
                                <td>{{ $evaluation->titre }}</td>
                                <td>{{ $evaluation->typeEvaluation->nom }}</td>
                                <td>
                                    {{ $evaluation->date_evaluation->format('d/m/Y') }}
                                    <br>
                                    <small class="text-muted">{{ $evaluation->heure_debut->format('H:i') }}</small>
                                </td>
                                <td>{{ $evaluation->duree }} min</td>
                                <td>
                                    @foreach($evaluation->classes as $classe)
                                        <span class="badge badge-info">{{ $classe->nom }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $evaluation->matiere->nom }}</td>
                                <td>
                                    <span class="badge badge-{{ $evaluation->statut === 'planifié' ? 'primary' : 
                                        ($evaluation->statut === 'en_cours' ? 'warning' : 
                                        ($evaluation->statut === 'terminé' ? 'success' : 'danger')) }}">
                                        {{ ucfirst($evaluation->statut) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @if(Auth::user()->hasPermission('evaluations', 'edit'))
                                            <button class="btn btn-sm btn-info" wire:click="edit({{ $evaluation->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif
                                        @if(Auth::user()->hasPermission('evaluations', 'delete'))
                                            <button class="btn btn-sm btn-danger" 
                                                    wire:click="delete({{ $evaluation->id }})"
                                                    onclick="confirm('Êtes-vous sûr de vouloir supprimer cette évaluation ?') || event.stopImmediatePropagation()">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Aucune évaluation trouvée</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $evaluations->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Évaluation -->
    <div class="modal fade" wire:ignore.self id="evaluationModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $isEditing ? 'Modifier l\'évaluation' : 'Nouvelle évaluation' }}
                    </h5>
                    <button type="button" class="close" wire:click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Titre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="titre">
                                @error('titre') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Type d'évaluation <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model="type_evaluation_id">
                                    <option value="">Sélectionner...</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->nom }}</option>
                                    @endforeach
                                </select>
                                @error('type_evaluation_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" wire:model="date_evaluation">
                                @error('date_evaluation') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Heure de début <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" wire:model="heure_debut">
                                @error('heure_debut') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Durée (minutes) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" wire:model="duree">
                                @error('duree') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Matière <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model="matiere_id">
                                    <option value="">Sélectionner...</option>
                                    @foreach($matieres as $matiere)
                                        <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                                    @endforeach
                                </select>
                                @error('matiere_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Année Académique</label>
                                <input type="text" class="form-control" value="{{ $current_year->nom ?? 'Non définie' }}" readonly>
                                <input type="hidden" wire:model="academic_year_id">
                                @if(!$current_year)
                                    <small class="text-danger">Aucune année académique active. Veuillez en définir une.</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Classes <span class="text-danger">*</span></label>
                        <div class="row">
                            @foreach($classes as $classe)
                                <div class="col-md-4">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="classe_{{ $classe->id }}"
                                               wire:model="selectedClasses"
                                               value="{{ $classe->id }}">
                                        <label class="custom-control-label" for="classe_{{ $classe->id }}">
                                            {{ $classe->nom }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('selectedClasses') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" wire:model="description" rows="3"></textarea>
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Statut <span class="text-danger">*</span></label>
                        <select class="form-control" wire:model="statut">
                            <option value="planifié">Planifié</option>
                            <option value="en_cours">En cours</option>
                            <option value="terminé">Terminé</option>
                            <option value="annulé">Annulé</option>
                        </select>
                        @error('statut') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Annuler</button>
                    <button type="button" class="btn btn-primary" wire:click="saveEvaluation">
                        {{ $isEditing ? 'Modifier' : 'Enregistrer' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', function () {
        Livewire.on('success', function(event) {
            $('#evaluationModal').modal('hide');
            toastr.success(event.message);
        });

        Livewire.on('error', function(event) {
            toastr.error(event.message);
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
