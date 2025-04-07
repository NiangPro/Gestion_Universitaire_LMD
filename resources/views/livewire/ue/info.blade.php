<div class="container-fluid">
    <!-- En-tête avec les informations principales -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-primary-light">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="fa fa-book fa-3x text-primary"></i>
                        </div>
                        <div>
                            <h2 class="mb-1">{{ $uniteEnseignement->nom }}</h2>
                            <p class="mb-0">
                                <i class="fa fa-graduation-cap"></i> 
                                Filière: {{ $uniteEnseignement->filiere->nom }}
                            </p>
                        </div>
                        <div class="ml-auto">
                            <span class="badge badge-success badge-lg">
                                {{ $uniteEnseignement->credit }} Crédits
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body mr-3">
                            <h2 class="text-primary">{{ $uniteEnseignement->matieres->count() }}</h2>
                            <span>Matières</span>
                        </div>
                        <i class="fa fa-book fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body mr-3">
                            <h2 class="text-success">
                                {{ $uniteEnseignement->matieres->sum('credit') }}
                            </h2>
                            <span>Total Crédits Matières</span>
                        </div>
                        <i class="fa fa-star fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body mr-3">
                            <h2 class="text-warning">
                                {{ $uniteEnseignement->matieres->sum('volume_horaire') }}
                            </h2>
                            <span>Total Heures</span>
                        </div>
                        <i class="fa fa-clock-o fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des matières -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Liste des matières</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Crédits</th>
                                    <th>Coefficient</th>
                                    <th>Volume Horaire</th>
                                    @if(Auth::user()->hasPermission('matieres', 'edit') || 
                                        Auth::user()->hasPermission('matieres', 'delete'))
                                        <th>Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($uniteEnseignement->matieres as $matiere)
                                    <tr>
                                        <td>{{ $matiere->nom }}</td>
                                        <td>
                                            <span class="badge badge-success">
                                                {{ $matiere->credit }} crédits
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">
                                                Coef. {{ $matiere->coefficient }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-warning">
                                                {{ $matiere->volume_horaire }}h
                                            </span>
                                        </td>
                                        @if(Auth::user()->hasPermission('matieres', 'edit') || 
                                            Auth::user()->hasPermission('matieres', 'delete'))
                                            <td>
                                                @if(Auth::user()->hasPermission('matieres', 'edit'))
                                                    <button wire:click="editMatiere({{ $matiere->id }})" 
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                @endif
                                                @if(Auth::user()->hasPermission('matieres', 'delete'))
                                                    <button wire:click="deleteMatiere({{ $matiere->id }})" 
                                                        class="btn btn-danger btn-sm">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            Aucune matière n'est associée à cette UE
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>