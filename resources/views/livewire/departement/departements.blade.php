<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header row">
                        <h4 class="col-md-8 card-title">{{$title}}</h4>
                        <div class="col-md-4 text-right">
                            @if($status == "list")
                                @if(Auth::user()->hasPermission('departements', 'create'))
                                    <button wire:click='changeStatus("add")' class="btn btn-primary">
                                        <i class="fa fa-plus-circle me-2"></i>Nouveau département
                                    </button>
                                @endif
                            @else
                                <button wire:click='changeStatus("list")' class="btn btn-warning">
                                    <i class="fa fa-arrow-left me-2"></i>Retour
                                </button>
                            @endif
                        </div>
                </div>

                <div class="card-body">
                    @if($status == "list")
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" 
                                        wire:model.live.debounce.300ms="search" 
                                        class="form-control" 
                                        placeholder="Rechercher un département...">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <select wire:model.live="perPage" class="form-control">
                                    <option value="10">10 par page</option>
                                    <option value="25">25 par page</option>
                                    <option value="50">50 par page</option>
                                    <option value="100">100 par page</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select wire:model.live="sortBy" class="form-control">
                                    <option value="nom">Trier par nom</option>
                                    <option value="created_at">Trier par date</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select wire:model.live="sortDirection" class="form-control">
                                    <option value="asc">Ascendant</option>
                                    <option value="desc">Descendant</option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>Département</th>
                                        <th>Description</th>
                                        <th>Date création</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($departements as $dept)
                                        <tr wire:key="dept-{{ $dept->id }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-primary text-white p-2 me-3 text-center" 
                                                         style="width: 40px; height: 40px; line-height: 24px;">
                                                        {{ strtoupper(substr($dept->nom, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $dept->nom }}</h6>
                                                        <small class="text-muted">
                                                            @if($dept->responsable)
                                                                {{ $dept->responsable->prenom }} {{ $dept->responsable->nom }}
                                                            @else
                                                                Non assigné
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ Str::limit($dept->description, 100) }}</td>
                                            <td>{{ $dept->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    @if(Auth::user()->hasPermission('departements', 'view'))
                                                        <button wire:click="getInfo({{ $dept->id }})" 
                                                            class="btn btn-info btn-sm">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    @endif

                                                    @if(Auth::user()->hasPermission('departements', 'edit'))
                                                        <button wire:click="edit({{ $dept->id }})" 
                                                            class="btn btn-warning btn-sm">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                    @endif

                                                    @if(Auth::user()->hasPermission('departements', 'delete'))
                                                        <button wire:click="confirmDelete({{ $dept->id }})" 
                                                            class="btn btn-danger btn-sm">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <img src="{{ asset('images/empty.png') }}" 
                                                    style="max-width: 200px" class="mb-3">
                                                <p>Aucun département trouvé</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                {{-- Supprimer ou modifier cette partie qui cause l'erreur --}}
                                {{-- <small class="text-muted">
                                    @if($dept->responsable)
                                        {{ $dept->responsable->prenom }} {{ $dept->responsable->nom }}
                                    @else
                                        Non assigné
                                    @endif
                                </small> --}}
                            </div>
                            {{ $departements->links() }}
                        </div>
                    
                    @elseif($status == "info")
                                @include('livewire.departement.info')
                    @else
                        @include('livewire.departement.add')
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation de suppression</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <i class="fa fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                    <p>Êtes-vous sûr de vouloir supprimer ce département ?</p>
                    <p class="text-danger"><small>Cette action est irréversible.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Annuler</button>
                    <button type="button" wire:click="delete" class="btn btn-danger">
                        <i class="fa fa-trash me-2"></i>Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('show-delete-modal', event => {
        $('#deleteModal').modal('show');
    });

    window.addEventListener('hide-delete-modal', event => {
        $('#deleteModal').modal('hide');
    });

    window.addEventListener('added', event => {
        iziToast.success({
            title: 'Succès',
            message: 'Département ajouté avec succès',
            position: 'topRight'
        });
    });

    window.addEventListener('updated', event => {
        iziToast.success({
            title: 'Succès',
            message: 'Département mis à jour avec succès',
            position: 'topRight'
        });
    });

    window.addEventListener('deleted', event => {
        $('#deleteModal').modal('hide');
        iziToast.warning({
            title: 'Suppression',
            message: 'Département supprimé avec succès',
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