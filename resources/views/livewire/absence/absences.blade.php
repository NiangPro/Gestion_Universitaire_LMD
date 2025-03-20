@php
    $statuses = ['absent' => 'danger', 'present' => 'success'];
@endphp

<div class="container-fluid px-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 font-weight-bold text-primary">Gestion des Absences</h5>
            <button type="button"  wire:click="showAddAbsenceModal" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Nouvelle Absence
            </button>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Année Académique</label>
                        <select wire:model.live="selectedYear" class="form-control">
                            <option value="">Toutes les années</option>
                            @foreach ($years as $year)
                                <option value="{{ $year->id }}">
                                    @if ($year->encours == 1)
                                    En cours
                                    @else
                                    {{ date("d/m/Y", strtotime($year->debut)) }} - {{ date("d/m/Y", strtotime($year->fin))   }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Statut</label>
                        <select wire:model.live="selectedStatus" class="form-control">
                            <option value="">Tous les statuts</option>
                            <option value="absent">Absent</option>
                            <option value="present">Présent</option>
                        </select>
                    </div>
                </div> -->
                @if($selectedYear)
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Rechercher</label>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Rechercher...">
                    </div>
                </div>
                @endif
            </div>

            @if(count($absences) > 0)
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
                        @foreach ($absences as $absence)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="mb-0">{{ $absence->etudiant->prenom }} {{ $absence->etudiant->nom }}</h6>
                                        <small class="text-muted">ID: {{ $absence->etudiant->matricule }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $absence->cours->matiere->nom }}</td>
                            <td>{{ $absence->date->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $statuses[$absence->status] }}-subtle text-{{ $statuses[$absence->status] }}">
                                    {{ $absence->status }}
                                </span>
                            </td>
                            <td>{{ $absence->justifie ? 'Oui' : 'Non' }}</td>
                            <td>{{ $absence->motif }}</td>
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
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Aucune absence trouvée
            </div>
            @endif
        </div>
    </div>

    @if ($isOpen)
    <!-- Modal Ajout Absence -->
    @include('livewire.absence.add')
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

@section('scripts')
<script>
   window.addEventListener('added', event =>{
        iziToast.success({
        title: 'Absence',
        message: 'enregistrée avec succes',
        position: 'topRight'
        });
    });
</script>
@endsection