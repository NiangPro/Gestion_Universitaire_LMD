<div>
    <div class="card">
        <div class="card-header row">
            <div class="col-md-8">
                <h4 class="card-title">{{ $title }}</h4>
            </div>
            <div class="col-md-4 text-right">
                @if(Auth::user()->hasPermission('cours', 'create'))
                    <button class="btn btn-primary" wire:click="openModal">Ajouter un cours</button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <select class="form-control" wire:model.live="academicYear">
                        <option value="">Année académique</option>
                        @foreach ($academicYears as $ay)
                        <option value="{{ $ay->id }}">
                            @if ($ay->encours == 1)
                            En cours
                            @else
                            {{ date("d/m/Y", strtotime($ay->debut)) }} - {{ date("d/m/Y", strtotime($ay->fin))   }}
                            @endif
                        </option>
                        @endforeach
                    </select>
                </div>
                @if ($academicYear)
                <div class="col-md-4">
                    <select class="form-control" wire:model.live="type">
                        <option value="">Type de cours</option>
                        <option value="classe">Classe</option>
                        <option value="professeur">Professeur</option>
                    </select>
                </div>
                @endif
                @if ($type)
                <div class="col-md-4">
                    @if($classrooms)
                        <select class="form-control" wire:model.live="cours">
                            <option value="">Selectionner une classe</option>
                            @foreach ($classrooms as $cl)
                                <option value="{{ $cl->id }}">{{ $cl->nom }} - {{ $cl->filiere->nom }}</option>
                            @endforeach
                        </select>
                    @endif
                    @if($teachers)
                        <select class="form-control" wire:model.live="cours">
                            <option value="">Selectionner un professeur</option>
                            @foreach ($teachers as $cl)
                                <option value="{{ $cl->id }}">{{ $cl->prenom }} {{ $cl->nom }}</option>
                            @endforeach
                        </select>
                    @endif
                    
                </div>
                @endif
            </div>
            @if(count($courses) > 0)
            @include('livewire.cours.list')
            @endif
            <!-- Modal -->
            @if($isOpen)
            <div class="modal show d-block" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{$modalTitle}}</h5>
                            <button type="button" class="close" wire:click="closeModal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @include('livewire.cours.add')
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
            @endif
        </div>
    </div>
</div>
</div>


@section('script')
<script>
    window.addEventListener('added', event => {
        iziToast.success({
            title: 'Cours',
            message: 'Ajout avec succes',
            position: 'topRight'
        });
    });
    window.addEventListener('updated', event => {
        iziToast.success({
            title: 'Cours',
            message: 'Mis à jour avec succes',
            position: 'topRight'
        });
    });
    window.addEventListener('alertSalle', event => {
        iziToast.warning({
            title: 'Cours',
            message: 'La salle est déjà occupée pendant ce créneau horaire',
            position: 'topCenter'
        });
    })

    window.addEventListener('alertClasse', event => {
        iziToast.warning({
            title: 'Cours',
            message: 'La classe est déjà occupée pendant ce créneau horaire',
            position: 'topCenter'
        });
    })

    window.addEventListener('alertProfesseur', event => {
        iziToast.warning({
            title: 'Cours',
            message: 'Le professeur est déjà occupé pendant ce créneau horaire',
            position: 'topCenter'
        });
    })

    window.addEventListener('coursDeleted', event => {
        iziToast.success({
            title: 'Cours',
            message: 'Suppression avec succes',
            position: 'topRight'
        });
        $('.modalId').modal('hide');
    })
</script>
@endsection