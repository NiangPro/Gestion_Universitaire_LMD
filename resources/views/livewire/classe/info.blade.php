<div class="print-header" style="display: none;">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 60px;">
        </div>
        <div class="text-center">
            <h3>{{ config('app.name') }}</h3>
            <p>Liste des étudiants</p>
        </div>
        <div>
            <p>Date: {{ date('d/m/Y') }}</p>
        </div>
    </div>
    <hr>
</div>

<div class="print-section" style="display: none;">
    @if($selectedAcademicYear && count($etudiants) > 0)
        <h4 class="text-center mb-4">
            Liste des étudiants - {{ $classe->nom }} ({{ $classe->filiere->nom }})
        </h4>
        
        <table class="table table-bordered">
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
                @foreach($etudiants as $index => $etudiant)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $etudiant->nom }}</td>
                        <td>{{ $etudiant->prenom }}</td>
                        <td>{{ $etudiant->email }}</td>
                        <td>{{ $etudiant->telephone }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="text-center">
            <p>Aucun étudiant trouvé pour cette année académique</p>
        </div>
    @endif
</div>

<div class="container-fluid">
    <!-- Contenu normal pour l'écran -->
    <div class="screen-content">
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
                    <div class="col-md-8">
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
                    @if($selectedAcademicYear && Auth::user()->hasPermission('classes', 'edit'))
                        <div class="col-md-4 text-right">
                            <button onclick="window.print()" class="btn btn-primary">
                                <i class="fa fa-print mr-2"></i>Imprimer la liste
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Table des étudiants -->
        @if($selectedAcademicYear)
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
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
                                    <td>{{ $etudiant->tel }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucun étudiant inscrit dans cette classe</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Version pour l'impression -->
    @if($selectedAcademicYear)
        <div class="print-section" style="display: none;">
            <h4 class="text-center mb-4">
                Liste des étudiants - {{ $classe->nom }} ({{ $classe->filiere->nom }})
            </h4>
            
            <table class="table table-bordered">
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
                            <td>{{ $etudiant->tel }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Aucun étudiant inscrit dans cette classe</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>

<style>
    @media screen {
        .print-section {
            display: none !important;
        }
    }

    @media print {
        .screen-content {
            display: none !important;
        }
        .print-section {
            display: block !important;
        }
        .table {
            width: 100% !important;
            border-collapse: collapse !important;
        }
        .table th,
        .table td {
            border: 1px solid #000 !important;
            padding: 8px !important;
        }
    }
</style> 