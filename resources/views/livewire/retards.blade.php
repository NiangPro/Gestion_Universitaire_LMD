<div class="container-fluid px-4">
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 font-weight-bold text-primary">Gestion des Retards</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRetardModal">
                <i class="fas fa-plus-circle me-2"></i>Nouveau Retard
            </button>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Campus</label>
                        <select wire:model.live="selectedCampus" class="form-select">
                            <option value="">Tous les campus</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Année Académique</label>
                        <select wire:model.live="selectedYear" class="form-select">
                            <option value="">Toutes les années</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Justifié</label>
                        <select wire:model.live="selectedJustification" class="form-select">
                            <option value="">Tous</option>
                            <option value="1">Justifié</option>
                            <option value="0">Non justifié</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Rechercher</label>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Rechercher...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Étudiant</th>
                            <th>Cours</th>
                            <th>Date</th>
                            <th>Durée</th>
                            <th>Justifié</th>
                            <th>Motif</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                            JD
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">John Doe</h6>
                                        <small class="text-muted">ID: 12345</small>
                                    </div>
                                </div>
                            </td>
                            <td>Mathématiques</td>
                            <td>{{ now()->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1" style="height: 6px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 30%"></div>
                                    </div>
                                    <span class="ms-2">15 min</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-warning-subtle text-warning">Non</span>
                            </td>
                            <td>Transport</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-info" title="Éditer">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Ajout Retard -->
    <div class="modal fade" id="addRetardModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouveau Retard</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Étudiant</label>
                                <select class="form-select" wire:model="etudiant_id">
                                    <option value="">Sélectionner un étudiant</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Cours</label>
                                <select class="form-select" wire:model="cours_id">
                                    <option value="">Sélectionner un cours</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date</label>
                                <input type="datetime-local" class="form-control" wire:model="date">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Minutes de retard</label>
                                <input type="number" class="form-control" wire:model="minutes_retard" min="1">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Motif</label>
                                <input type="text" class="form-control" wire:model="motif">
                            </div>
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="justifie">
                                    <label class="form-check-label">Retard justifié</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Commentaire</label>
                                <textarea class="form-control" wire:model="commentaire" rows="3"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" wire:click="save">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .avatar-sm {
        width: 36px;
        height: 36px;
    }
    .avatar-title {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
    }
</style>
@endpush
