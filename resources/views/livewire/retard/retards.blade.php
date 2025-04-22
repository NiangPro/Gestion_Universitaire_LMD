<div class="container-fluid px-4">
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 font-weight-bold text-primary">
                <i class="fas fa-clock me-2"></i>Gestion des Retards
            </h5>
            @if(Auth::user()->hasPermission('retards', 'create'))
            <button type="button" wire:click="showAddRetardModal" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Nouveau Retard
            </button>
            @endif
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Année Académique</label>
                        <select wire:model.live="selectedYear" class="form-control">
                            <option value="">Toutes les années</option>
                            @foreach ($years as $year)
                                <option value="{{ $year->id }}">
                                    @if ($year->encours == 1)
                                        En cours
                                    @else
                                        {{ date("d/m/Y", strtotime($year->debut)) }} - {{ date("d/m/Y", strtotime($year->fin)) }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if($selectedYear)
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Classe</label>
                        <select wire:model.live="selectedClasse" class="form-control">
                            <option value="">Toutes les classes</option>
                            @foreach ($classes as $classe)
                                <option value="{{ $classe->id }}">{{ $classe->nom }} - {{ $classe->filiere->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
                @if($selectedClasse)
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Rechercher</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Rechercher un étudiant ou un cours...">
                        </div>
                    </div>
                </div>
                @endif
            </div>

            @if(count($retards) > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Étudiant</th>
                            <th>Cours</th>
                            <th>Date</th>
                            <th>Durée</th>
                            <th>Justifié</th>
                            <th>Motif</th>
                            @if(Auth::user()->hasPermission('retards', 'edit') || Auth::user()->hasPermission('retards', 'delete'))
                            <th class="text-center">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($retards as $retard)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                            {{ strtoupper(substr($retard->etudiant->prenom, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $retard->etudiant->prenom }} {{ $retard->etudiant->nom }}</h6>
                                        <small class="text-muted">{{ $retard->etudiant->matricule }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $retard->cours->matiere->nom }}</td>
                            <td>{{ $retard->date->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1" style="height: 6px;">
                                        <div class="progress-bar {{ $retard->minutes_retard > 30 ? 'bg-danger' : 'bg-warning' }}" 
                                             role="progressbar" 
                                             style="width: {{ min(($retard->minutes_retard/60)*100, 100) }}%">
                                        </div>
                                    </div>
                                    <span class="ms-2">{{ $retard->minutes_retard }} min</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $retard->justifie ? 'success' : 'warning' }}-subtle 
                                             text-{{ $retard->justifie ? 'success' : 'warning' }}">
                                    {{ $retard->justifie ? 'Oui' : 'Non' }}
                                </span>
                            </td>
                            <td>{{ $retard->motif ?: 'Non spécifié' }}</td>
                            @if(Auth::user()->hasPermission('retards', 'edit') || Auth::user()->hasPermission('retards', 'delete'))
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    @if(Auth::user()->hasPermission('retards', 'edit'))
                                    <button wire:click="edit({{ $retard->id }})" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @endif

                                    @if(Auth::user()->hasPermission('retards', 'delete'))
                                    <button wire:click="delete({{ $retard->id }})" 
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce retard ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <img src="{{ asset('images/empty.png') }}" alt="Aucun retard" class="img-fluid mb-3" style="max-width: 200px">
                <h5 class="text-muted">Aucun retard trouvé</h5>
                <p class="text-muted">Commencez par ajouter un nouveau retard</p>
            </div>
            @endif
        </div>
    </div>

    @if($isOpen)
        @include("livewire.retard.add")
    @endif
</div>

@push('styles')
<style>
    .avatar-sm {
        width: 36px;
        height: 36px;
    }
    .avatar-title {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
    }
    .progress {
        background-color: #e9ecef;
        border-radius: 0.25rem;
    }
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.5);
    }
</style>
@endpush

@push('scripts')
<script>
    window.addEventListener('added', event => {
        iziToast.success({
            title: 'Succès',
            message: 'Retard enregistré avec succès',
            position: 'topRight'
        });
    });

    window.addEventListener('updated', event => {
        iziToast.success({
            title: 'Succès',
            message: 'Retard mis à jour avec succès',
            position: 'topRight'
        });
    });

    window.addEventListener('deleted', event => {
        iziToast.warning({
            title: 'Suppression',
            message: 'Retard supprimé avec succès',
            position: 'topRight'
        });
    });

    window.addEventListener('error', event => {
        iziToast.error({
            title: 'Erreur',
            message: event.detail.message,
            position: 'topRight'
        });
    });
</script>
@endpush
