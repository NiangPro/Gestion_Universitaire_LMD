<div>
    <div class="card">
        <div class="card-header row">
            <div class="col-md-6">
                <h4 class="card-title">Liste des classes</h4>
            </div>
            <div class="col-md-6 text-right">
                <button class="btn btn-primary" wire:click="openModal">Ajouter une classe</button>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Rechercher..." wire:model.live="search">
                </div>
                <div class="col-md-2">
                    <select class="form-control" wire:model.live="perPage">
                        <option value="10">10 par page</option>
                        <option value="25">25 par page</option>
                        <option value="50">50 par page</option>
                        <option value="100">100 par page</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Filière</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $classe)
                        <tr>
                            <td>{{ $classe->nom }}</td>
                            <td>{{ $classe->filiere->nom }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" wire:click="edit({{ $classe->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" wire:click="delete({{ $classe->id }})"
                                    onclick="confirm('Êtes-vous sûr de vouloir supprimer cette classe ?') || event.stopImmediatePropagation()">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Aucune classe trouvée</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    Affichage de {{ $classes->firstItem() ?? 0 }} à {{ $classes->lastItem() ?? 0 }} sur {{ $classes->total() }} entrées
                </div>
                <div class="col-md-6">
                    {{ $classes->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($isOpen)
    <div class="modal show d-block" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $modalTitle }}</h5>
                    <button type="button" class="close" wire:click="closeModal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit="save">
                        <div class="form-group">
                            <label>Nom de la classe</label>
                            <input type="text" class="form-control" wire:model="nom">
                            @error('nom') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Filière</label>
                            <select class="form-control" wire:model="filiere_id">
                                <option value="">Sélectionner une filière</option>
                                @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id }}">{{ $filiere->nom }}</option>
                                @endforeach
                            </select>
                            @error('filiere_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
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

    window.addEventListener('deleted', event => {
        iziToast.success({
            title: 'Classe',
            message: 'Supprimé avec succès',
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