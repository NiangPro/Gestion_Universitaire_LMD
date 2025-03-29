<div class="container-fluid">
    <!-- Contenu visible uniquement à l'écran -->
    <div class="screen-only">
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
    </div>

    <!-- Zone d'impression -->
    @if($selectedAcademicYear)
        <div class="print-content">
            <!-- Titre pour l'impression -->
            <h4 class="text-center mb-3">
                Liste des étudiants - {{ $classe->nom }} ({{ $classe->filiere->nom }})
            </h4>

            <!-- Table des étudiants -->
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
                            <td>{{ $etudiant->telephone }}</td>
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
        .print-content {
            /* Visible à l'écran normalement */
        }
    }

    @media print {
        /* Reset des positions fixes */
        body {
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Ajustement du conteneur principal */
        .main-wrapper {
            margin: 0 !important;
            padding: 0 !important;
            position: static !important;
        }

        /* Style de la sidebar */
        .dlabnav {
            width: 100% !important;
            position: static !important;
            margin-bottom: 20px !important;
            print-color-adjust: exact !important;
            -webkit-print-color-adjust: exact !important;
        }

        /* Cache les éléments non nécessaires de la sidebar */
        .dlabnav .dlabnav-scroll,
        .dlabnav .nav-header {
            position: static !important;
            width: 100% !important;
        }

        /* Ajustement du contenu principal */
        .content-body {
            margin: 0 !important;
            padding: 20px !important;
            position: static !important;
            min-height: auto !important;
            width: 100% !important;
        }

        /* Style du footer */
        .footer {
            position: static !important;
            width: 100% !important;
            margin-top: 20px !important;
            padding: 10px !important;
        }

        /* Cache les éléments spécifiques à l'écran */
        .screen-only {
            display: none !important;
        }

        /* Styles pour la table */
        .table {
            width: 100% !important;
            margin-bottom: 1rem !important;
            border-collapse: collapse !important;
        }

        .table th,
        .table td {
            padding: 8px !important;
            border: 1px solid #000 !important;
        }

        /* Force l'affichage du contenu imprimable */
        .print-content {
            display: block !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 15px !important;
            position: static !important;
        }

        /* Assure que les éléments s'affichent dans le bon ordre */
        * {
            overflow: visible !important;
            position: relative !important;
            float: none !important;
        }

        /* Supprime les backgrounds et ombres */
        * {
            background: transparent !important;
            box-shadow: none !important;
            color: #000 !important;
        }
    }
</style> 