<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-money-bill text-primary"></i> Gestion des Paiements
                        </h4>
                        <button class="btn btn-primary" wire:click="$set('showModal', true)">
                            <i class="fas fa-plus-circle"></i> Nouveau Paiement
                        </button>
                    </div>

                    <!-- Filtres -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Année Académique</label>
                                <select wire:model.live="academic_year_id" class="form-control">
                                    <option value="">Toutes les années</option>
                                    @foreach($academic_years as $year)
                                        <option value="{{ $year->id }}">{{ $year->nom }}</option>
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
                            <thead class="bg-light">
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
                                        <td>{{ $paiement->date_paiement->format('d/m/Y H:i') }}</td>
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
                                            <button class="btn btn-sm btn-info" title="Détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <img src="{{ asset('images/empty.svg') }}" alt="Aucun paiement" 
                                                 style="width: 200px; opacity: 0.5;">
                                            <p class="text-muted mt-3">Aucun paiement trouvé</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $paiements->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'ajout de paiement -->
    @if($showModal)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle"></i> Nouveau Paiement
                    </h5>
                    <button type="button" class="close text-white" wire:click="$set('showModal', false)">
                        <span>&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="savePaiement">
                    <div class="modal-body">
                        <!-- Recherche étudiant -->
                        <div class="form-group">
                            <label>Matricule Étudiant</label>
                            <div class="input-group">
                                <input type="text" class="form-control" wire:model="searchMatricule" 
                                       placeholder="Entrez le matricule...">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="button" wire:click="searchEtudiant">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Type de paiement -->
                        <div class="form-group">
                            <label>Type de Paiement</label>
                            <select class="form-control" wire:model="type_paiement">
                                <option value="">Sélectionner le type</option>
                                <option value="inscription">Inscription</option>
                                <option value="mensualite">Mensualité</option>
                                <option value="complement">Complément</option>
                            </select>
                            @error('type_paiement') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Montant -->
                        <div class="form-group">
                            <label>Montant</label>
                            <div class="input-group">
                                <input type="number" class="form-control" wire:model="montant">
                                <div class="input-group-append">
                                    <span class="input-group-text">FCFA</span>
                                </div>
                            </div>
                            @error('montant') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Mode de paiement -->
                        <div class="form-group">
                            <label>Mode de Paiement</label>
                            <select class="form-control" wire:model="mode_paiement">
                                <option value="">Sélectionner le mode</option>
                                <option value="espece">Espèces</option>
                                <option value="cheque">Chèque</option>
                                <option value="virement">Virement</option>
                                <option value="mobile_money">Mobile Money</option>
                            </select>
                            @error('mode_paiement') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Observation -->
                        <div class="form-group">
                            <label>Observation</label>
                            <textarea class="form-control" wire:model="observation" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">
                            Annuler
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
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
</style>
@endpush
