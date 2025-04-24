<div>
    @if ($status == "list")
    <div class="container-fluid">
        <!-- En-tête -->
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Gestion des Surveillants</h4>
                    <span class="ml-1">Année académique {{ Auth::user()->campus->currentAcademicYear()->nom }}</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                @if(Auth::user()->hasPermission('professeurs', 'create'))
                <button wire:click='changeStatus("add")' class="btn btn-primary">
                    <i class="fa fa-plus-circle mr-2"></i>Nouveau surveillant
                </button>
                @endif
            </div>
        </div>

        <!-- Liste des professeurs -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                <div class="card-header row">
                    <h4 class="card-title col-md-8">{{ $title }}</h4>
                    <div class="input-group col-md-4">
                        <input type="text" class="form-control" wire:model.live="search" 
                            placeholder="Rechercher...">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="thead-primary">
                                    <tr>
                                        <th class="text-center">Photo</th>
                                        <th wire:click="$set('sortField', 'nom')" style="cursor: pointer;">
                                            Nom complet
                                            @if($sortField === 'nom')
                                                <i class="fa fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </th>
                                        <th>Contact</th>
                                        <th>Spécialité</th>
                                        <th>Classes assignés</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @forelse($surveillants as $monitor)
                                    <tr>
                                        <td class="text-center">
                                            <img src="{{ asset('storage/images/'.$monitor->image) }}" 
                                                 class="rounded-circle" width="35" alt="photo">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h5 class="mb-0">{{ $monitor->prenom }} {{ $monitor->nom }}</h5>
                                                    <small class="text-muted">{{ $monitor->username }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <i class="fa fa-envelope text-primary mr-1"></i> {{ $monitor->email }}
                                            </div>
                                            <div>
                                                <i class="fa fa-phone text-success mr-1"></i> {{ $monitor->tel }}
                                            </div>
                                        </td>
                                        <td>{{ $monitor->specialite }}</td>
                                        <td>
                                            <div class="d-flex">
                                                @if(Auth::user()->hasPermission('professeurs', 'view'))
                                                <button class="btn btn-info btn-sm mr-1" 
                                                        wire:click="changeStatus('details', {{ $monitor->id }})">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                @endif

                                                @if(Auth::user()->hasPermission('professeurs', 'edit'))
                                                <button class="btn btn-primary btn-sm mr-1" 
                                                        wire:click="edit({{ $monitor->id }})">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                @endif

                                                @if(Auth::user()->hasPermission('professeurs', 'delete'))
                                                <button class="btn btn-danger btn-sm" 
                                                        wire:click="confirmDelete({{ $monitor->id }})">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Aucun professeur trouvé</td>
                                    </tr>
                                    @endforelse
                            </tbody>
                        </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                Affichage de {{ $surveillants->firstItem() ?? 0 }} à {{ $surveillants->lastItem() ?? 0 }} 
                                sur {{ $surveillants->total() }} entrées
                            </div>
                            <div>
                                {{ $surveillants->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @elseif($status === "details")
    @include('livewire.personnel.surveillant.details')
    @else       
    <!-- Formulaire d'ajout/modification -->
    @include('livewire.personnel.surveillant.form')
    @endif

    @if($showDeleteModal)
    <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation de suppression</h5>
                    <button type="button" class="close" wire:click="cancelDelete">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if($surveillantToDelete)
                    <p>Êtes-vous sûr de vouloir supprimer le surveillant 
                       <strong>{{ $surveillantToDelete->prenom }} {{ $surveillantToDelete->nom }}</strong> ?</p>
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle mr-2"></i>
                        Cette action est irréversible
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cancelDelete">
                        <i class="fa fa-times mr-2"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="delete">
                        <i class="fa fa-trash mr-2"></i>Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>


@section('script')
    <script>
      window.addEventListener('addSuccessful', event =>{
        iziToast.success({
        title: 'Surveillant',
        message: 'Ajout avec succes',
        position: 'topRight'
        });
    });
    window.addEventListener('updateSuccessful', event =>{
        iziToast.success({
        title: 'Surveillant',
        message: 'Mis à jour avec succes',
        position: 'topRight'
        });
    });

    </script>
@endsection