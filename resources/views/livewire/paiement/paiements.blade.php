<div class="container-fluid">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-1"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-money-bill text-primary"></i> Gestion des Paiements
                        </h4>
                        @if(Auth::user()->hasPermission('paiements', 'create'))
                        <button class="btn btn-primary" wire:click="$set('showModal', true)">
                            <i class="fas fa-plus-circle"></i> Nouveau Paiement
                        </button>
                        @endif
                    </div>

                    <!-- Filtres -->
                    @if(Auth::user()->hasPermission('paiements', 'view'))
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Année Académique</label>
                                <select wire:model.live="academic_year_id" class="form-control">
                                    <option value="">Toutes les années</option>
                                    @foreach($academic_years as $year)
                                        <option value="{{ $year->id }}">{{ date('Y', strtotime($year->debut)) }} - {{ date('Y', strtotime($year->fin)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Matricule Étudiant</label>
                                <input type="text" wire:model.live="matricule" class="form-control" placeholder="Rechercher par matricule...">
                            </div>
                        </div>
                    </div>

                    <!-- Liste des paiements -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="">
                                <tr>
                                    <th>Date</th>
                                    <th>Référence</th>
                                    <th>Étudiant</th>
                                    <th>Type</th>
                                    <th>Montant</th>
                                    <th>Mode</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paiements as $paiement)
                                    <tr>
                                        <td>{{ date('d/m/Y H:i', strtotime($paiement->date_paiement)) }}</td>
                                        <td>
                                            <span class="badge badge-light">{{ $paiement->reference }}</span>
                                        </td>
                                        <td>
                                            {{ $paiement->user->nom }} {{ $paiement->user->prenom }}
                                            <br>
                                            <small class="text-muted">{{ $paiement->user->matricule }}</small>
                                        </td>
                                        <td>
                                            @switch($paiement->type_paiement)
                                                @case('inscription')
                                                    <span class="badge badge-info">Inscription</span>
                                                    @break
                                                @case('mensualite')
                                                    <span class="badge badge-primary">Mensualité</span>
                                                    @break
                                                @case('complement')
                                                    <span class="badge badge-warning">Complément</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                                        <td>
                                            <span class="badge badge-secondary">
                                                {{ ucfirst($paiement->mode_paiement) }}
                                            </span>
                                        </td>
                                        <td>
                                            @switch($paiement->status)
                                                @case('valide')
                                                    <span class="badge badge-success">Validé</span>
                                                    @break
                                                @case('en_attente')
                                                    <span class="badge badge-warning">En attente</span>
                                                    @break
                                                @case('rejete')
                                                    <span class="badge badge-danger">Rejeté</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            @if(Auth::user()->hasPermission('paiements', 'view'))
                                            <button class="btn btn-sm btn-info" 
                                                    wire:click="showDetails({{ $paiement->id }})" 
                                                    title="Détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @endif

                                            @if(Auth::user()->hasPermission('paiements', 'edit') && $paiement->isEditable())
                                            <button class="btn btn-sm btn-primary" 
                                                    wire:click="startEdit({{ $paiement->id }})" 
                                                    title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <img src="{{ asset('images/empty.png') }}" alt="Aucun paiement" 
                                                 style="width: 200px; opacity: 0.5;">
                                            <p class="text-muted mt-3">Aucun paiement trouvé</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Vous n'avez pas la permission de voir les paiements
                    </div>
                    @endif

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $paiements->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'ajout de paiement -->
    @if($showModal && Auth::user()->hasPermission('paiements', 'create'))
    @include('livewire.paiement.paiements-add')
    @endif

    <!-- Modal de détails -->
    @if($showDetailModal && Auth::user()->hasPermission('paiements', 'view'))
    @include('livewire.paiement.paiements-details')
    @endif
</div>

@push('styles')
<style>
    .badge {
        padding: 0.5em 1em;
    }
    .table td {
        vertical-align: middle;
    }
    .list-group-item:hover {
        cursor: pointer;
        background-color: #f8f9fa;
    }
    .list-group {
        max-height: 300px;
        overflow-y: auto;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .list-group-item:first-child {
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
    }
    .list-group-item:last-child {
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
    }
    .alert {
        border-left: 4px solid;
        margin-bottom: 20px;
    }

    .alert-success {
        border-left-color: #28a745;
    }

    .alert-danger {
        border-left-color: #dc3545;
    }

    .alert-warning {
        border-left-color: #ffc107;
    }

    .alert-info {
        border-left-color: #17a2b8;
    }

    .alert i {
        margin-right: 8px;
    }

    .alert .btn-close {
        font-size: 0.8rem;
    }

    .modal-body .table {
        margin-bottom: 0;
    }

    .modal-body .table th {
        width: 35%;
        vertical-align: middle;
    }

    .modal-body .table td {
        vertical-align: middle;
    }

    /* Styles pour le modal */
    .modal-dialog {
        max-width: 700px;
        margin: 1.75rem auto;
    }

    .modal-content {
        border: none;
        border-radius: 8px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .modal-body {
        padding: 1.5rem;
        max-height: calc(100vh - 210px);
        overflow-y: auto;
    }

    .table {
        margin-bottom: 0;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        width: 30%;
    }

    .table td {
        vertical-align: middle;
    }

    .badge {
        padding: 0.5em 1em;
        font-size: 0.875rem;
    }

    /* Personnalisation de la barre de défilement */
    .modal-body::-webkit-scrollbar {
        width: 6px;
    }

    .modal-body::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .modal-body::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }

    .modal-body::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endpush

@push('scripts')
<script>
    // Faire disparaître automatiquement les alertes après 5 secondes
    window.addEventListener('livewire:load', function () {
        Livewire.on('showAlert', () => {
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    });
</script>
@endpush
