<div>
    <!-- En-tête de la page -->
    <div class="row">
        <div class="col-12">
            <div class="card border-left-warning shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-warning-soft p-3 mr-3">
                                    <i class="fas fa-clock text-warning fa-lg"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0 text-warning font-weight-bold">Gestion des Retards</h4>
                                    <p class="text-muted mb-0 mt-1">
                                     {{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white">
                                        <i class="fas fa-calendar text-warning"></i>
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
                        <div class="rounded-circle bg-warning-soft p-2 mr-2">
                            <i class="fas fa-chalkboard text-warning"></i>
                        </div>
                        <h5 class="mb-0 font-weight-bold text-dark">Classes ayant cours le {{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('D MMMM YYYY') }}</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if($loading)
                        <div class="text-center py-4">
                            <div class="spinner-border text-warning" role="status">
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
                                        <div class="card h-100 class-card {{ $selectedClasse === $classe['id'] ? 'selected-warning' : '' }}"
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
                                                        $status = $now->between($debut, $fin) ? ['warning', 'En cours'] :
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
        </div>
    </div>

    <!-- Liste des Étudiants -->
    @if($selectedClasse && !empty($etudiants))
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white py-4 row">
                <h5 class="mb-0 font-weight-bold col-md-8"><i class="fas fa-users text-warning mr-2"></i>Liste des Étudiants</h5>
                <div class="col-md-4 text-right">
                    <button class="btn btn-outline-warning btn-sm" wire:click="resetRetards" title="Réinitialiser les retards">
                        <i class="fas fa-undo-alt mr-1"></i> Réinitialiser
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
                                <th class="py-3 px-4 border-0 text-center">Minutes de retard</th>
                                <th class="py-3 px-4 border-0 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($etudiants as $index => $etudiant)
                                <tr class="align-middle">
                                    <td class="py-3 px-4">{{ $etudiant['matricule'] }}</td>
                                    <td class="py-3 px-4 font-weight-medium">{{ $etudiant['nom'] }}</td>
                                    <td class="py-3 px-4">{{ $etudiant['prenom'] }}</td>
                                    <td class="py-3 px-4 text-center">
                                        @if($etudiant['minutes_retard'])
                                            <span class="badge badge-warning px-3 py-2">
                                                {{ $etudiant['minutes_retard'] }} min
                                            </span>
                                        @else
                                            <span class="badge badge-light px-3 py-2">À l'heure</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if($etudiant['minutes_retard'])
                                            <button class="btn btn-sm btn-danger" wire:click="deleteRetard('{{ $etudiant['id'] }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-warning" wire:click="openRetardModal({{ $index }})">
                                                <i class="fas fa-clock"></i> Marquer en retard
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de Retard -->
    @if($retardModal)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title">Enregistrer un retard</h5>
                        <button type="button" class="close text-white" wire:click="$set('retardModal', false)">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-user mr-2"></i>
                            <strong>{{ $selectedEtudiant['nom'] }} {{ $selectedEtudiant['prenom'] }}</strong>
                        </div>
                        <div class="form-group">
                            <label for="minutes_retard">Minutes de retard <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="minutes_retard" wire:model="minutes_retard" min="1">
                            @error('minutes_retard') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="motif">Motif</label>
                            <input type="text" class="form-control" id="motif" wire:model="motif">
                            @error('motif') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group mb-0">
                            <label for="commentaire">Commentaire</label>
                            <textarea class="form-control" id="commentaire" wire:model="commentaire" rows="3"></textarea>
                            @error('commentaire') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('retardModal', false)">Annuler</button>
                        <button type="button" class="btn btn-warning" wire:click="saveRetard">Enregistrer</button>
                    </div>
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
        .border-left-warning {
            border-left: 4px solid #f6c23e !important;
        }

        .bg-warning-soft {
            background-color: rgba(246, 194, 62, 0.1) !important;
        }

        .bg-success-soft {
            background-color: rgba(40, 167, 69, 0.1) !important;
        }

        .bg-info-soft {
            background-color: rgba(23, 162, 184, 0.1) !important;
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

        .class-card.selected-warning {
            background-color: #f6c23e;
            border-color: #f6c23e;
            color: #fff !important;
        }

        .class-card.selected-warning .text-dark,
        .class-card.selected-warning .text-muted {
            color: #fff !important;
        }

        .class-card.selected-warning .bg-success-soft,
        .class-card.selected-warning .bg-warning-soft {
            background-color: rgba(255, 255, 255, 0.2) !important;
        }

        .class-card.selected-warning .text-success,
        .class-card.selected-warning .text-warning {
            color: #fff !important;
        }

        .class-card.selected-warning .badge-warning {
            background-color: #fff;
            color: #f6c23e;
        }
    </style>
</div>
