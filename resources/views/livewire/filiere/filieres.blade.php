<div>
    <div class="card">
        <div class="card-header row">
            <h3 class="col-md-8 card-title">{{$title}}</h3>
            <div class="col-md-4 text-right">
                @if($status == "list" && Auth::user()->hasPermission('filieres', 'create'))
                    <button wire:click='changeStatus("add")' class="btn btn-success">
                        <i class="fa fa-plus-circle mr-2"></i>Nouvelle Filière
                    </button>
                @else
                    <button wire:click='changeStatus("list")' class="btn btn-primary">
                        <i class="fa fa-list mr-2"></i>Liste des Filières
                    </button>
                @endif
            </div>
        </div>

        <div class="card-body">
            @if($status == "list")
              
                <!-- Filtres et recherche -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>
                            <input type="text" 
                                wire:model.live="search" 
                                class="form-control" 
                                placeholder="Rechercher une filière...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select wire:model.live="selectedDepartement" class="form-control">
                            <option value="">Tous les départements</option>
                            @foreach($depts as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="perPage" class="form-control">
                            <option value="10">10 par page</option>
                            <option value="25">25 par page</option>
                            <option value="50">50 par page</option>
                            <option value="100">100 par page</option>
                        </select>
                    </div>
                </div>

                <!-- Table des filières -->
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="">
                            <tr>
                                <th wire:click="sortBy('nom')" style="cursor: pointer;" class="text-primary">
                                    Nom
                                    @if($sortField === 'nom')
                                        <i class="fa fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>Département</th>
                                <th>Statistiques</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($filieres as $filiere)
                                <tr>
                                    <td class="font-weight-bold">{{ $filiere->nom }}</td>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ $filiere->departement->nom }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <span class="badge badge-info mr-2" title="Classes">
                                                <i class="fa fa-users"></i> {{ $filiere->classes_count ?? 0 }}
                                            </span>
                                            <span class="badge badge-success mr-2" title="Unités d'enseignement">
                                                <i class="fa fa-book"></i> {{ $filiere->unite_enseignements_count ?? 0 }}
                                            </span>
                                            <span class="badge badge-warning" title="Matières">
                                                <i class="fa fa-tasks"></i> {{ $filiere->matieres_count ?? 0 }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if(Auth::user()->hasPermission('filieres', 'view'))
                                            <button wire:click='getInfo({{$filiere->id}})' 
                                                class="btn btn-info btn-sm" 
                                                title="Détails">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        @endif

                                        @if(Auth::user()->hasPermission('filieres', 'edit'))
                                            <button wire:click='edit({{$filiere->id}})' 
                                                class="btn btn-warning btn-sm" 
                                                title="Modifier">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        @endif

                                        @if(Auth::user()->hasPermission('filieres', 'delete'))
                                            <button type="button" 
                                                data-toggle="modal" 
                                                data-target="#deleteModal{{$filiere->id}}" 
                                                class="btn btn-danger btn-sm"
                                                title="Supprimer">
                                                <i class="fa fa-trash"></i>
                                            </button>

                                            <!-- Modal de confirmation -->
                                            <div class="modal fade" id="deleteModal{{$filiere->id}}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title">Confirmation de suppression</h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal">
                                                                <span>&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Êtes-vous sûr de vouloir supprimer la filière <strong>{{ $filiere->nom }}</strong> ?</p>
                                                            <p class="text-danger">
                                                                <i class="fa fa-exclamation-triangle"></i>
                                                                Cette action est irréversible.
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                <i class="fa fa-times"></i> Annuler
                                                            </button>
                                                            <button type="button" 
                                                                wire:click='supprimer({{$filiere->id}})' 
                                                                class="btn btn-danger">
                                                                <i class="fa fa-trash"></i> Confirmer
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="fa fa-search fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">
                                                Aucune filière trouvée
                                                @if($search)
                                                    pour la recherche "{{ $search }}"
                                                @endif
                                            </p>
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
                            Affichage de <strong>{{ $filieres->firstItem() ?? 0 }}</strong> à 
                            <strong>{{ $filieres->lastItem() ?? 0 }}</strong> sur 
                            <strong>{{ $filieres->total() }}</strong> filières
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-right">
                            {{ $filieres->links() }}
                        </div>
                    </div>
                </div>
            @elseif($status == "info")
                @include('livewire.filiere.info')
            @else
                @include('livewire.filiere.add')
            @endif
        </div>
    </div>
</div>

@section('scripts')
<script>
    window.addEventListener('added', event => {
        iziToast.success({
            title: 'Filière',
            message: 'Ajoutée avec succès',
            position: 'topRight'
        });
    });

    window.addEventListener('update', event => {
        iziToast.success({
            title: 'Filière',
            message: 'Mise à jour effectuée avec succès',
            position: 'topRight'
        });
    });

    window.addEventListener('delete', event => {
        $('.modal').modal('hide');
        iziToast.warning({
            title: 'Filière',
            message: 'Supprimée avec succès',
            position: 'topRight'
        });
    });
</script>
@endsection

@section('css')
<style>
    .empty-state {
        text-align: center;
        padding: 40px;
    }
    
    .badge {
        padding: 8px 12px;
    }

    .table th {
        background-color: #f8f9fa;
    }

    .pagination {
        margin: 0;
    }

    .btn-sm {
        margin: 0 2px;
    }

    .modal-header .close {
        padding: 1rem;
        margin: -1rem -1rem -1rem auto;
    }
</style>
@endsection