<div>
    <div class="card">
        <div class="card-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#home8">
                        <span>
                            <i class="fa fa-users"></i>
                        </span>
                        Liste des étudiants
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#profile8">
                        <span>
                            <i class="fa fa-plus-circle"></i>
                        </span>
                        Effectuez une inscription
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#reinscrire">
                        <span>
                            <i class="fa fa-file"></i>
                        </span>
                        Effectuez une réinscription
                    </a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content tabcontent-border">
                <div class="tab-pane fade active show" id="home8" role="tabpanel">
                    <div class="pt-4">
                         @include("livewire.etudiant.list")
                    </div>
                </div>
                <div class="tab-pane fade" id="profile8" role="tabpanel">
                    <div class="pt-4">
                        @include("livewire.etudiant.inscription")
                    </div>
                </div>
                <div class="tab-pane fade" id="reinscrire" role="tabpanel">
                    <div class="pt-4">
                        <div class="row">
                            <div class="col-md-12">
                                <select class="form-control" wire:model.live="matricule" name="matricule" id="matricule">
                                    <option value="">Selectionner un étudiant</option>
                                    <option value="1">NGLP123456</option>
                                    <option value="2">NGLP123457</option>
                                    <option value="3">NGLP123458</option>
                                </select>
                            </div>
                        </div>
                        @if ($matricule)
                            @include("livewire.etudiant.inscription")
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
