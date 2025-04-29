<div class="container-fluid py-3">
    <div class="row">
        <div class="col-12">
            <div class="card border-left-primary shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary-soft p-3 mr-3">
                                    <i class="fas fa-calendar-check text-primary fa-lg"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0 text-primary font-weight-bold">Gestion des Absences</h4>
                                    <p class="text-muted mb-0 mt-1">{{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white">
                                        <i class="fas fa-calendar text-primary"></i>
                                    </span>
                                </div>
                                <input type="date" wire:model.live="date" class="form-control" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des Classes -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary-soft p-2 mr-2">
                            <i class="fas fa-chalkboard text-primary"></i>
                        </div>
                        <h5 class="mb-0 font-weight-bold text-dark">Classes ayant cours le {{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('D MMMM YYYY') }}</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if($loading)
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Chargement...</span>
                            </div>
                        </div>
                    @else
                        @if(empty($classes))
                            <div class="alert alert-info mb-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <span>Aucune classe n'a cours aujourd'hui</span>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                @foreach($classes as $classe)
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100 class-card {{ $selectedClasse === $classe['id'] ? 'selected' : '' }}"
                                             wire:click="selectClasse('{{ $classe['id'] }}', '{{ $classe['cours_id'] }}')">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div class="d-flex flex-column">
                                                        <h5 class="card-title text-dark mb-1 font-weight-bold">{{ $classe['nom'] }}</h5>
                                                        <p class="text-muted small mb-0">({{ $classe['filiere'] }})</p>
                                                    </div>
                                                    @php
                                                        $now = \Carbon\Carbon::now();
                                                        $debut = \Carbon\Carbon::createFromFormat('H:i:s', $classe['heure_debut']);
                                                        $fin = \Carbon\Carbon::createFromFormat('H:i:s', $classe['heure_fin']);
                                                        $status = $now->between($debut, $fin) ? ['primary', 'En cours'] :
                                                                 ($now->lt($debut) ? ['info', 'À venir'] : ['secondary', 'Terminé']);
                                                    @endphp
                                                    <span class="badge badge-{{ $status[0] }}">{{ $status[1] }}</span>
                                                </div>
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="rounded-circle bg-success-soft p-2 mr-2">
                                                        <i class="fas fa-book text-success"></i>
                                                    </div>
                                                    <span class="text-muted">{{ $classe['matiere'] }}</span>
                                                </div>
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="rounded-circle bg-warning-soft p-2 mr-2">
                                                        <i class="fas fa-clock text-warning"></i>
                                                    </div>
                                                    <span class="text-muted">{{ substr($classe['heure_debut'], 0, 5) }} - {{ substr($classe['heure_fin'], 0, 5) }}</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-info-soft p-2 mr-2">
                                                        <i class="fas fa-users text-info"></i>
                                                    </div>
                                                    <span class="text-muted">Effectif : <span class="badge badge-info">{{ $classe['effectif'] }}</span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Liste des Étudiants -->
    @if($selectedClasse && !empty($etudiants))
        <div class="card border-0 shadow-sm">
            <div class="card-header row bg-white py-4 border-bottom">
                        <h5 class="mb-0 fw-bold col-md-8"><i class="fas fa-users text-primary"></i>Liste des Étudiants</h5>
                    <div class="col-md-4 text-md-right mt-3 mt-md-0">
                        <button class="btn btn-primary btn-lg px-4 rounded-pill" wire:click="saveAbsences">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>

                        <button class="btn btn-warning btn-lg px-4 rounded-pill ms-2" wire:click="resetAbsences">
                            <i class="fas fa-undo me-2"></i>Réinitialiser
                        </button>
                    </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 px-4 border-0">Matricule</th>
                                <th class="py-3 px-4 border-0">Nom</th>
                                <th class="py-3 px-4 border-0">Prénom</th>
                                <th class="py-3 px-4 border-0 text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($etudiants as $index => $etudiant)
                                <tr class="align-middle">
                                    <td class="py-3 px-4">{{ $etudiant['matricule'] }}</td>
                                    <td class="py-3 px-4 fw-medium">{{ $etudiant['nom'] }}</td>
                                    <td class="py-3 px-4">{{ $etudiant['prenom'] }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" 
                                                   class="custom-control-input" 
                                                   id="absence-{{ $index }}"
                                                   wire:model.live="etudiants.{{ $index }}.absent">
                                            <label class="custom-control-label" for="absence-{{ $index }}"></label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
<script>
    window.addEventListener('alert', event => {
        if (event.detail.type === 'success') {
            iziToast.success({
                title: 'Succès',
                message: event.detail.message,
                position: 'topRight'
            });
        } else if (event.detail.type === 'warning') {
            iziToast.warning({
                title: 'Attention',
                message: event.detail.message,
                position: 'topRight'
            });
        } else if (event.detail.type === 'error') {
            iziToast.error({
                title: 'Erreur',
                message: event.detail.message,
                position: 'topRight'
            });
        }
    });
</script>
@endpush

<style>
        .border-left-primary {
            border-left: 4px solid #4e73df !important;
        }

        .bg-primary-soft {
            background-color: rgba(78, 115, 223, 0.1) !important;
        }

        .bg-success-soft {
            background-color: rgba(40, 167, 69, 0.1) !important;
        }

        .bg-warning-soft {
            background-color: rgba(255, 193, 7, 0.1) !important;
        }

        .class-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 0.35rem;
            border: 1px solid #e3e6f0;
        }

        .class-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        }

        .class-card.selected {
            background-color: #4e73df;
            border-color: #4e73df;
            color: #fff !important;
        }

        .class-card.selected .text-dark,
        .class-card.selected .text-muted,
        .class-card.selected .badge {
            color: #fff !important;
        }

        .class-card.selected .bg-success-soft,
        .class-card.selected .bg-warning-soft {
            background-color: rgba(255, 255, 255, 0.2) !important;
        }

        .class-card.selected .text-success,
        .class-card.selected .text-warning {
            color: #fff !important;
        }

        .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #4e73df;
            border-color: #4e73df;
        }

        .custom-control-input:focus ~ .custom-control-label::before {
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .custom-checkbox .custom-control-label::before {
            border-radius: 0.25rem;
        }

        .table > tbody > tr > td,
        .table > thead > tr > th {
            padding: 0.75rem;
            vertical-align: middle;
        }

        .font-weight-medium {
            font-weight: 500;
        }
    </style>
</div>
