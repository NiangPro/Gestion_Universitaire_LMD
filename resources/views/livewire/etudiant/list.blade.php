<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des étudiants</h3>
    </div>
    <div class="card-body">
        <form>
            <div class="row mb-3">
                <div class="col-md-4">
                    <select wire:model.live="annee_academique" class="form-control">
                        <option value="">Année académique</option>    
                        @foreach ($academic_years as $academicYear)
                            <option value="{{ $academicYear->id }}">{{ $academicYear->debut }} - {{ $academicYear->fin }}</option>
                        @endforeach
                    </select>
                </div>
                @if($annee_academique)
                    <div class="col-md-4">
                        <select wire:model.live="classe" class="form-control">
                            <option value="">Classe</option>
                            @foreach ($classes as $classe)
                                <option value="{{ $classe->id }}">{{ $classe->nom }} -> {{ $classe->filiere->nom }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Rechercher un étudiant...">
                    </div>
                @endif
            </div>
        </form>

        @if ($classe)
            @if($etudiants->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Matricule</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Sexe</th>
                                <th>Date de naissance</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($etudiants as $etudiant)
                                @php
                                    $inscription = $etudiant->inscriptions()->latest()->first();
                                @endphp
                                <tr>
                                    <td>{{ $etudiant->matricule }}</td>
                                    <td>{{ $etudiant->nom }}</td>
                                    <td>{{ $etudiant->prenom }}</td>
                                    <td>{{ $etudiant->sexe }}</td>
                                    <td>{{ date('d/m/Y', strtotime($etudiant->date_naissance)) }}</td>
                                    <td>
                                        <button wire:click="edit({{ $etudiant->id }})" class="btn btn-sm btn-warning">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        @if($inscription)
                                            <button wire:click="confirmerSuppression({{ $inscription->id }})" class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    Aucun étudiant trouvé dans cette classe.
                </div>
            @endif
        @else
            <div class="alert alert-info">
                Veuillez sélectionner une classe pour voir la liste des étudiants.
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('confirmerSuppression', (inscriptionId) => {
            Swal.fire({
                title: 'Êtes-vous sûr?',
                text: "Cette action est irréversible!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer!',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('supprimer', inscriptionId);
                }
            })
        });
    });
</script>
@endpush