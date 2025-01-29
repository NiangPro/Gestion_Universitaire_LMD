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
                    <a class="nav-link" data-toggle="tab" href="#messages8">
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
                <div class="tab-pane fade" id="messages8" role="tabpanel">
                    <div class="pt-4">
                        <h4>This is icon title</h4>
                        <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor.
                        </p>
                        <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
