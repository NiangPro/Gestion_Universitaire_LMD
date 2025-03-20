<div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Gestion des étudiants</h4>
        </div>
        <div class="card-body">
            <div wire:ignore.self>
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
            </div>
        </div>
    </div>
</div>
