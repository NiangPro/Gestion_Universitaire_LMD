<div>
    <div class="card" wire:key="historique-card">
        <div class="row card-header">
            <h3 class="card-title col-md-6">
                <i class="fas fa-history mr-2"></i>
                Historique des abonnements et campus
            </h3>
            <div class="col-md-6 text-right">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print mr-2"></i>Imprimer
                </button>
            </div>
        </div>
        
        <div class="card-body">
            <!-- Filtres -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <input type="text" 
                            wire:model.live="search" 
                            class="form-control" 
                            placeholder="Rechercher...">
                    </div>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="typeFilter" class="form-control">
                        <option value="">Tous les types</option>
                        <option value="add">Ajout</option>
                        <option value="edit">Modification</option>
                        <option value="delete">Suppression</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" 
                        wire:model.live="dateDebut" 
                        class="form-control" 
                        placeholder="Date début">
                </div>
                <div class="col-md-2">
                    <input type="date" 
                        wire:model.live="dateFin" 
                        class="form-control" 
                        placeholder="Date fin">
                </div>
                <div class="col-md-2">
                    <select wire:model.live="perPage" class="form-control">
                        <option value="10">10 par page</option>
                        <option value="25">25 par page</option>
                        <option value="50">50 par page</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button wire:click="resetFilters" class="btn btn-warning w-100">
                        <i class="fas fa-redo"></i>
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Campus</th>
                            <th>Date</th>
                            <th>Détails</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($historiques as $historique)
                        <tr>
                            <td>
                                @if($historique->table === 'subscriptions')
                                    @if($historique->type === 'add')
                                        <span class="badge badge-success">Nouvel abonnement</span>
                                    @elseif($historique->type === 'edit')
                                        <span class="badge badge-info">Renouvellement</span>
                                    @elseif($historique->type === 'delete')
                                        <span class="badge badge-danger">Résiliation</span>
                                    @endif
                                @elseif($historique->table === 'campuses')
                                    @if($historique->type === 'add')
                                        <span class="badge badge-primary">Nouveau campus</span>
                                    @elseif($historique->type === 'edit')
                                        <span class="badge badge-warning">Modification campus</span>
                                    @endif
                                @elseif($historique->table === 'packs')
                                    <span class="badge badge-secondary">Changement de formule</span>
                                @endif
                            </td>
                            <td>{{ $historique->description }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-{{ $historique->user->role === 'superadmin' ? 'dark' : 'info' }} mr-2">
                                        {{ $historique->user->role === 'superadmin' ? 'SuperAdmin' : $historique->campus->nom }}
                                    </span>
                                </div>
                                <small class="text-muted">{{ $historique->user->prenom }} {{ $historique->user->nom }}</small>
                            </td>
                            <td>
                                <div>{{ $historique->created_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $historique->created_at->format('H:i:s') }}</small>
                            </td>
                            <td>
                                <button wire:click="showDetails({{ $historique->id }})" 
                                        class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <img src="{{ asset('images/empty.png') }}" alt="Aucune donnée" 
                                     style="width: 200px; height: 200px;">
                                <p class="text-muted">Aucun historique trouvé</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    Affichage de {{ $historiques->firstItem() ?? 0 }} à {{ $historiques->lastItem() ?? 0 }} 
                    sur {{ $historiques->total() }} entrées
                </div>
                <div>
                    {{ $historiques->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Détails -->
    @if($showModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Détails de l'activité</h5>
                        <button type="button" class="close" wire:click="$set('showModal', false)">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="font-weight-bold">Informations de l'action</h6>
                                <dl class="row">
                                    <dt class="col-sm-4">Type</dt>
                                    <dd class="col-sm-8">
                                        <span class="badge badge-{{ $selectedHistorique->type === 'delete' ? 'danger' : ($selectedHistorique->type === 'edit' ? 'warning' : 'success') }}">
                                            {{ $selectedHistorique->type === 'delete' ? 'Suppression' : ($selectedHistorique->type === 'edit' ? 'Modification' : 'Ajout') }}
                                        </span>
                                    </dd>

                                    <dt class="col-sm-4">Description</dt>
                                    <dd class="col-sm-8">{{ $selectedHistorique->description }}</dd>

                                    <dt class="col-sm-4">Date</dt>
                                    <dd class="col-sm-8">{{ $selectedHistorique->created_at->format('d/m/Y H:i:s') }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <h6 class="font-weight-bold">Informations techniques</h6>
                                <dl class="row">
                                    <dt class="col-sm-4">Appareil</dt>
                                    <dd class="col-sm-8">{{ $selectedHistorique->device }}</dd>

                                    <dt class="col-sm-4">Navigateur</dt>
                                    <dd class="col-sm-8">{{ $selectedHistorique->navigateur }}</dd>

                                    <dt class="col-sm-4">Adresse IP</dt>
                                    <dd class="col-sm-8">{{ $selectedHistorique->ip }}</dd>
                                </dl>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="font-weight-bold">Informations de l'utilisateur</h6>
                                <dl class="row">
                                    <dt class="col-sm-4">Nom complet</dt>
                                    <dd class="col-sm-8">{{ $selectedHistorique->user->prenom }} {{ $selectedHistorique->user->nom }}</dd>

                                    <dt class="col-sm-4">Téléphone</dt>
                                    <dd class="col-sm-8">{{ $selectedHistorique->user->tel }}</dd>

                                    <dt class="col-sm-4">Email</dt>
                                    <dd class="col-sm-8">{{ $selectedHistorique->user->email }}</dd>

                                    <dt class="col-sm-4">Rôle</dt>
                                    <dd class="col-sm-8">
                                        <span class="badge badge-{{ $selectedHistorique->user->role === 'superadmin' ? 'dark' : 'primary' }}">
                                            {{ ucfirst($selectedHistorique->user->role) }}
                                        </span>
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                @if($selectedHistorique->campus_id)
                                    <h6 class="font-weight-bold">Informations de l'établissement</h6>
                                    <dl class="row">
                                        <dt class="col-sm-4">Nom</dt>
                                        <dd class="col-sm-8">{{ $selectedHistorique->campus->nom }}</dd>

                                        <dt class="col-sm-4">Email</dt>
                                        <dd class="col-sm-8">{{ $selectedHistorique->campus->email }}</dd>

                                        <dt class="col-sm-4">Téléphone</dt>
                                        <dd class="col-sm-8">{{ $selectedHistorique->campus->tel }}</dd>
                                    </dl>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Action système effectuée par un super administrateur
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

@push('styles')
<style>
    @media print {
        .card-header button,
        .row.mb-4,
        .modal,
        .modal-backdrop,
        .pagination,
        .btn {
            display: none !important;
        }
        .table {
            border-collapse: collapse !important;
        }
        .table td,
        .table th {
            background-color: #fff !important;
            border: 1px solid #000 !important;
        }
    }
</style>
@endpush
