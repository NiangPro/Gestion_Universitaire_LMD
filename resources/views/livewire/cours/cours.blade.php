<div>
    <div class="card">
        <div class="card-header row">
            <div class="col-md-8">
                <h4 class="card-title">{{ $title }}</h4>
            </div>
            <div class="col-md-4 text-right">
                <button class="btn btn-primary" wire:click="openModal">Ajouter un cours</button>
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
                    <select class="form-control" wire:model.live="cours">
                        <option value="">Selectionner une classe ou professeur</option>
                        <option value="1">NGLP123456</option>
                        <option value="2">NGLP123457</option>
                        <option value="3">NGLP123458</option>
                    </select>
                </div>
                @endif
            </div>
            <!-- Modal -->
            @if($isOpen)
            <div class="modal show d-block" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Ajouter un cours</h5>
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
    window.addEventListener('updateSuccessful', event => {
        iziToast.success({
            title: 'Professeur',
            message: 'Mis à jour avec succes',
            position: 'topRight'
        });
    });
</script>
@endsection