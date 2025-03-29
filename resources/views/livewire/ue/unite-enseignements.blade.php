<div>
    <div class="card">
        <div class="row card-header">
                    <h3 class="col-md-8 card-title">{{$title}}</h3>
                <div class="col-md-4 text-right">
                    @if($status == "list" && Auth::user()->hasPermission('ue', 'create'))
                        <button wire:click='changeStatus("add")' class="btn btn-success">
                            <i class="fa fa-plus-circle mr-2"></i>Nouvelle UE
                        </button>
                    @else
                        <button wire:click='changeStatus("list")' class="btn btn-primary">
                            <i class="fa fa-list mr-2"></i>Liste des UE
                        </button>
                    @endif
                </div>
        </div>

        <div class="card-body">
            @if($status != "list")
                @include('livewire.ue.add')
            @else
                <!-- Filtres -->
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
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="number" 
                                wire:model.live="creditMin" 
                                class="form-control" 
                                placeholder="Crédit min">
                            <div class="input-group-prepend">
                                <span class="input-group-text">-</span>
                            </div>
                            <input type="number" 
                                wire:model.live="creditMax" 
                                class="form-control" 
                                placeholder="Crédit max">
                        </div>
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
                        <thead class="thead-primary">
                            <tr>
                                <th wire:click="sortBy('nom')" style="cursor: pointer;">
                                    Nom
                                    @if($sortField === 'nom')
                                        <i class="fa fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>Filière</th>
                                <th>Crédit</th>
                                <th>Matières</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($uniteEnseignements as $ue)
                                <tr>
                                    <td>{{ $ue->nom }}</td>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ $ue->filiere->nom }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">
                                            {{ $ue->credit }} crédits
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $ue->matieres->count() }} matière(s)
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if(Auth::user()->hasPermission('ue', 'view'))
                                            <button wire:click='getInfo({{$ue->id}})' 
                                                class="btn btn-info btn-sm" 
                                                title="Détails">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        @endif

                                        @if(Auth::user()->hasPermission('ue', 'edit'))
                                            <button wire:click='edit({{$ue->id}})' 
                                                class="btn btn-warning btn-sm" 
                                                title="Modifier">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        @endif

                                        @if(Auth::user()->hasPermission('ue', 'delete'))
                                            <button type="button" 
                                                data-toggle="modal" 
                                                data-target="#deleteModal{{$ue->id}}" 
                                                class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </button>

                                            <!-- Modal de suppression -->
                                            @include('livewire.ue.delete-modal', ['ue' => $ue])
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="fa fa-search fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Aucune unité d'enseignement trouvée</p>
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
                            Affichage de <strong>{{ $uniteEnseignements->firstItem() ?? 0 }}</strong> à 
                            <strong>{{ $uniteEnseignements->lastItem() ?? 0 }}</strong> sur 
                            <strong>{{ $uniteEnseignements->total() }}</strong> UE
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-right">
                            {{ $uniteEnseignements->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>