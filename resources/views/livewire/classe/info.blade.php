<div class="container-fluid">
    <!-- En-tête avec informations principales -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-primary-light">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="fa fa-users fa-3x text-primary"></i>
                        </div>
                        <div>
                            <h2 class="mb-1">{{ $classe->nom }}</h2>
                            <p class="mb-0">
                                <i class="fa fa-graduation-cap"></i> 
                                Filière: {{ $classe->filiere->nom }}
                            </p>
                        </div>
                        @if(Auth::user()->hasPermission('classes', 'print'))
                            <div class="ml-auto">
                                <button onclick="window.print()" class="btn btn-primary">
                                    <i class="fa fa-print mr-2"></i>Imprimer la liste
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row">
        <div class="col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body mr-3">
                            <h2 class="text-primary">{{ $classe->etudiants->count() }}</h2>
                            <span>Étudiants</span>
                        </div>
                        <i class="fa fa-users fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body mr-3">
                            <h2 class="text-success">{{ number_format($classe->cout_inscription) }}</h2>
                            <span>Frais d'inscription</span>
                        </div>
                        <i class="fa fa-money fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body mr-3">
                            <h2 class="text-warning">{{ number_format($classe->mensualite) }}</h2>
                            <span>Mensualité</span>
                        </div>
                        <i class="fa fa-calendar fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body mr-3">
                            <h2 class="text-info">{{ number_format($classe->cout_formation) }}</h2>
                            <span>Coût total formation</span>
                        </div>
                        <i class="fa fa-graduation-cap fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des étudiants -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Liste des étudiants</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="printTable">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Montant</th>
                            <th class="no-print">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classe->etudiants as $index => $etudiant)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $etudiant->nom }}</td>
                                <td>{{ $etudiant->prenom }}</td>
                                <td>{{ $etudiant->email }}</td>
                                <td>{{ $etudiant->telephone }}</td>
                                <td>
                                    {{ $etudiant->inscriptions->where('classe_id', $classe->id)->first()->montant ?? 'N/A' }} FCFA
                                </td>
                                <td class="no-print">
                                    @if(Auth::user()->hasPermission('etudiants', 'view'))
                                        <a href="{{ route('etudiants.show', $etudiant->id) }}" 
                                            class="btn btn-info btn-sm">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Aucun étudiant inscrit dans cette classe</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-header {
            background: none !important;
            border: none !important;
        }
        .container-fluid {
            padding: 0 !important;
        }
    }
</style> 