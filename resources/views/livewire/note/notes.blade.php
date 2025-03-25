<div>
    {{-- The Master doesn't talk, he acts. --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Gestion des Notes</h3>
            <button type="button" class="btn btn-primary" wire:click="$set('showModal', true)">
                <i class="fas fa-plus"></i> Ajouter une note
            </button>
        </div>
        
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Filtres -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Rechercher un étudiant..." 
                           wire:model.debounce.300ms="searchTerm">
                </div>
                <div class="col-md-3">
                    <select class="form-control" wire:model="filterType">
                        <option value="">Tous les types</option>
                        <option value="CC">Contrôle Continu</option>
                        <option value="TP">Travaux Pratiques</option>
                        <option value="Examen">Examen</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" wire:model="filterSemestre">
                        <option value="">Tous les semestres</option>
                        <option value="1">Semestre 1</option>
                        <option value="2">Semestre 2</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-secondary" wire:click="resetFilters">
                        Réinitialiser
                    </button>
                </div>
            </div>

            <!-- Modal d'ajout de note -->
            @if($showModal)
                @include('livewire.note.add-note')
            @endif

            <!-- Tableau des notes -->
            <table class="table mt-4">
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Cours</th>
                        <th>Note</th>
                        <th>Type</th>
                        <th>Coefficient</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notes as $note)
                        <tr>
                            <td>{{ $note->etudiant->nom }} {{ $note->etudiant->prenom }}</td>
                            <td>{{ $note->cours->nom }}</td>
                            <td>{{ $note->note }}/20</td>
                            <td>{{ $note->type_evaluation }}</td>
                            <td>{{ $note->coefficient }}</td>
                            <td>{{ $note->date_evaluation }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" wire:click="edit({{ $note->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $note->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $notes->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('swal:confirm', event => {
        Swal.fire({
            title: event.detail.title,
            text: event.detail.text,
            icon: event.detail.type,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteNote', { noteId: event.detail.id });
            }
        })
    });
</script>
@endpush
