<div>
    <div class="card">
        <div class="card-header row">
            <h1 class="card-title col-md-6">{{$title}}</h1>
            <div class="col-md-6 text-right">
                @if($status == "list")
                    <button wire:click='changeStatus("add")' class="btn btn-success ml-5"><span class="btn-icon-left text-primary"><i class="fa fa-plus"></i></span>Inscription</button>&nbsp;&nbsp;&nbsp;
                    <button wire:click='changeStatus("re-register")' class="btn btn-info"><span class="btn-icon-left text-primary"><i class="fa fa-plus"></i></span>Réinscription</button>
                @else
                    <a href="{{route('etudiant')}}" class="btn btn-primary"><span class="btn-icon-left text-primary"><i class="fa fa-hand-o-left"></i></span>Retour</a>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if($status == "list")
                @include("livewire.etudiant.list")
            @elseif($status == "add")
                @include("livewire.etudiant.inscription")
            @elseif($status == "re-register")   
                <div class="row">
                    <div class="col-md-6">
                        <select class="form-control" wire:model.live="matricule">
                            <option value="">Sélectionner un étudiant</option>
                            @foreach($etudiants as $etudiant)
                                <option value="{{ $etudiant->matricule }}">
                                    {{ $etudiant->matricule }} - {{ $etudiant->nom }} {{ $etudiant->prenom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if ($matricule)
                    @include("livewire.etudiant.reinscription")
                @endif
            @endif
            {{-- <div wire:ignore.self>
                <div class="custom-tab-1">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'list' ? 'active' : '' }}" wire:click="setActiveTab('list')" data-toggle="tab" href="#list">
                                <span>
                                    <i class="fa fa-users"></i>
                                </span>
                                Liste des étudiants 
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'add' ? 'active' : '' }}" wire:click="setActiveTab('add')" data-toggle="tab" href="#add">
                                <span>
                                    <i class="fa fa-plus-circle"></i>
                                </span>
                                Effectuez une inscription
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'reinscrire' ? 'active' : '' }}" wire:click="setActiveTab('reinscrire')" data-toggle="tab" href="#reinscrire">
                                <span>
                                    <i class="fa fa-file"></i>
                                </span>
                                Effectuez une réinscription
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content tabcontent-border">
                        <div class="tab-pane fade {{ $activeTab === 'list' ? 'show active' : '' }}" id="list" role="tabpanel" wire:ignore.self>
                            <div class="pt-4">
                                @include("livewire.etudiant.list")
                            </div>
                        </div>
                        <div class="tab-pane fade {{ $activeTab === 'add' ? 'show active' : '' }}" id="add" role="tabpanel" wire:ignore.self>
                            <div class="pt-4">
                                @include("livewire.etudiant.inscription")
                            </div>
                        </div>
                        <div class="tab-pane fade {{ $activeTab === 'reinscrire' ? 'show active' : '' }}" id="reinscrire" role="tabpanel" wire:ignore.self>
                            <div class="pt-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <select class="form-control" wire:model.live="matricule">
                                            <option value="">Sélectionner un étudiant</option>
                                            @foreach($etudiants as $etudiant)
                                                <option value="{{ $etudiant->matricule }}">
                                                    {{ $etudiant->matricule }} - {{ $etudiant->nom }} {{ $etudiant->prenom }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($matricule)
                                    @include("livewire.etudiant.reinscription")
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('saved', event => {
        iziToast.success({
            title: 'Etudiant/Inscription',
            message: 'Enregistré avec succès',
            position: 'topRight'
        });
    });

    window.addEventListener('deleted', event => {
        iziToast.success({
            title: 'Etudiant/Inscription',
            message: 'Supprimé avec succès',
            position: 'topRight'
        });
    });
</script>
@endpush