<div>
    <div class="card transparent-card">
        <div class="card-header row">
            <h4 class="card-title col-md-8">Accepter les suppressions des tables</h4>
            <div class="col-md-2 text-right">
                <button wire:click='' class="btn btn-success"><span class="btn-icon-left text-success"><i class="fa fa-plus-circle"></i></span>Ajouter</button>
            </div>
        </div>
        <div class="card-body">
            <div id="accordion-ten" class="accordion accordion-header-shadow accordion-rounded">
                @foreach($tables as $t)
                    <div class="accordion__item">
                        <div class="accordion__header accordion__header--success collapsed" data-toggle="collapse" data-target="#{{$t->Tables_in_gestion_universitaire_lmd}}" aria-expanded="false">
                            <span class="accordion__header--icon"></span>
                            <span class="accordion__header--text">{{$t->Tables_in_gestion_universitaire_lmd}}</span>
                            <span class="accordion__header--indicator"></span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
