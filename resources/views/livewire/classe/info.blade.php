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
                            <h2 class="text-primary">{{ $classe->etudiants->count() }}</h2>
                            <span>Étudiants</span>
                        </div>
                        <i class="fa fa-users fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
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
        <div class="col-xl-4">
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
    </div>

    <!-- Sélection de l'année académique -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <label class="mb-0"><strong>Sélectionner l'année académique</strong></label>
                </div>
                <div class="col-md-4">
                    <select wire:model.live="selectedAcademicYear" class="form-control">
                        <option value="">Choisir une année académique</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}">
                                {{ date('Y', strtotime($year->debut)) }} - {{ date('Y', strtotime($year->fin)) }}
                                @if($year->encours) (En cours) @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($selectedAcademicYear && Auth::user()->hasPermission('classes', 'print'))
                    <div class="col-md-2">
                        <button onclick="window.print()" class="btn btn-primary w-100">
                            <i class="fa fa-print mr-2"></i>Imprimer
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Liste des étudiants -->
    @if($selectedAcademicYear)
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">
                    Liste des étudiants - 
                    @if($currentAcademicYear)
                        {{ date('Y', strtotime($currentAcademicYear->debut)) }} - {{ date('Y', strtotime($currentAcademicYear->fin)) }}
                        @if($currentAcademicYear->encours) (En cours) @endif
                    @endif
                </h4>
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
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($etudiants as $index => $etudiant)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $etudiant->nom }}</td>
                                    <td>{{ $etudiant->prenom }}</td>
                                    <td>{{ $etudiant->email }}</td>
                                    <td>{{ $etudiant->telephone }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucun étudiant inscrit dans cette classe pour cette année académique</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
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