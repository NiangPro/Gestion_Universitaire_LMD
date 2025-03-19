@php
    $statuses = ['absent' => 'danger', 'present' => 'success'];
@endphp

<div class="container-fluid px-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 font-weight-bold text-primary">Gestion des Absences</h5>
            <button type="button"  wire:click="showAddAbsenceModal" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAbsenceModal">
                <i class="fas fa-plus-circle me-2"></i>Nouvelle Absence
            </button>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Année Académique</label>
                        <select wire:model.live="selectedYear" class="form-control">
                            <option value="">Toutes les années</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Statut</label>
                        <select wire:model.live="selectedStatus" class="form-control">
                            <option value="">Tous les statuts</option>
                            <option value="absent">Absent</option>
                            <option value="present">Présent</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
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
                            <th>Statut</th>
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
                                <span class="badge bg-danger-subtle text-danger">Absent</span>
                            </td>
                            <td>
                                <span class="badge bg-warning-subtle text-warning">Non</span>
                            </td>
                            <td>Maladie</td>
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

    @if ($isOpen)
    <!-- Modal Ajout Absence -->
    <div class="modal fade" id="addAbsenceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouvelle Absence</h5>
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
                                <label class="form-label">Statut</label>
                                <select class="form-select" wire:model="status">
                                    <option value="absent">Absent</option>
                                    <option value="present">Présent</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Motif</label>
                                <input type="text" class="form-control" wire:model="motif">
                            </div>
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="justifie">
                                    <label class="form-check-label">Absence justifiée</label>
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
    @endif
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
