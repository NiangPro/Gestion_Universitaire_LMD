<div>
    <div class="card">
        <div class="row card-header">
            <h3 class="col-md-8 card-title">{{$title}}</h3>
            <div class="col-md-4 text-right">
                @if($status == "list" && Auth::user()->hasPermission('classes', 'create'))
                    <button wire:click='changeStatus("add")' class="btn btn-success">
                        <i class="fa fa-plus-circle mr-2"></i>Nouvelle Classe
                    </button>
                @else
                    <button wire:click='changeStatus("list")' class="btn btn-primary">
                        <i class="fa fa-list mr-2"></i>Liste des Classes
                    </button>
                @endif
            </div>
        </div>

        <div class="card-body">
            @if($status == "list")
                <!-- Filtres -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>
                            <input type="text" 
                                wire:model.live="search" 
                                class="form-control" 
                                placeholder="Rechercher...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select wire:model.live="selectedFiliere" class="form-control">
                            <option value="">Toutes les filières</option>
                            @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id }}">{{ $filiere->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="perPage" class="form-control">
                            <option value="10">10 par page</option>
                            <option value="25">25 par page</option>
                            <option value="50">50 par page</option>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th wire:click="sortBy('nom')" style="cursor: pointer;">
                                    Nom
                                    @if($sortField === 'nom')
                                        <i class="fa fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>Filière</th>
                                <th>Effectif</th>
                                <th>Frais</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classes as $classe)
                                <tr>
                                    <td>{{ $classe->nom }}</td>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ $classe->filiere->nom }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $classe->etudiants_count }} étudiants
                                        </span>
                                    </td>
                                    <td>
                                        <small>
                                            <div>Inscription: {{ number_format($classe->cout_inscription) }} FCFA</div>
                                            <div>Mensualité: {{ number_format($classe->mensualite) }} FCFA</div>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        @if(Auth::user()->hasPermission('classes', 'view'))
                                            <button wire:click='getInfo({{$classe->id}})' 
                                                class="btn btn-info btn-sm">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        @endif

                                        @if(Auth::user()->hasPermission('classes', 'edit'))
                                            <button wire:click='edit({{$classe->id}})' 
                                                class="btn btn-warning btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        @endif

                                        @if(Auth::user()->hasPermission('classes', 'delete'))
                                            <button type="button" 
                                                data-toggle="modal" 
                                                data-target="#deleteModal{{$classe->id}}" 
                                                class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </button>

                                            <!-- Inclure le modal -->
                                            @include('livewire.classe.delete-modal', ['classe' => $classe])
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="fa fa-search fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Aucune classe trouvée</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="row mt-4">
                    <div class="col-sm-6">
                        <div class="text-muted">
                            Affichage de {{ $classes->firstItem() ?? 0 }} à {{ $classes->lastItem() ?? 0 }} 
                            sur {{ $classes->total() }} classes
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-right">
                            {{ $classes->links() }}
                        </div>
                    </div>
                </div>
            @elseif($status == "info")
                @include('livewire.classe.info')
            @else
                @include('livewire.classe.add')
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('saved', event => {
        iziToast.success({
            title: 'Classe',
            message: 'Enregistré avec succès',
            position: 'topRight'
        });
    });

    window.addEventListener('delete', event => {
        $('.modal').modal('hide');
        $('.modal-backdrop').remove();
        
        iziToast.warning({
            title: 'Classe',
            message: 'Supprimée avec succès',
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

@push('styles')
<style>
    /* Style pour la pagination */
    .pagination {
        justify-content: flex-end;
    }

    .page-item.active .page-link {
        background-color: #4e73df;
        border-color: #4e73df;
    }

    .page-link {
        color: #4e73df;
    }

    .page-link:hover {
        color: #2e59d9;
    }

    /* Style pour le sélecteur d'entrées par page */
    select.form-control {
        width: auto;
    }
</style>
@endpush