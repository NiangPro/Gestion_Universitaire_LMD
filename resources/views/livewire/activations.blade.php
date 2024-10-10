<div>
    <div class="card transparent-card">
        <div class="card-header row">
            <h4 class="card-title col-md-8">Validation de la Suppression des Éléments dans les Tables</h4>
            <div class="col-md-2 text-right">
                <button wire:click='' class="btn btn-success"><span class="btn-icon-left text-success"><i class="fa fa-plus-circle"></i></span>Ajouter</button>
            </div>
        </div>
        <div class="card-body">
            <div id="accordion-ten" class="accordion accordion-header-shadow accordion-rounded">
                @foreach($tables as $t)
                    <div class="accordion__item" id="item_{{$t->id}}">
                        <div class="accordion__header accordion__header--success row">
                            <span class="col-md-9">{{ucfirst($t->nom)}}</span>
                            <span class="col-md-3 text-right">
                                @if($t->status == 0) 
                                <button wire:click='changeStatus({{$t->id}}, "actif")' class="btn btn-outline-danger btn-rounded">Activer</button>
                                @else
                                <button wire:click='changeStatus({{$t->id}}, "ferme")' class="btn btn-outline-success btn-rounded">Désactiver</button>
                                @endif
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('actif', event =>{
        iziToast.success({
        title: 'Suppression',
        message: 'activé avec succes',
        position: 'topRight'
        });
    });

    window.addEventListener('ferme', event =>{
        iziToast.warning({
        title: 'Suppression',
        message: 'désactivé avec succes',
        position: 'topRight'
        });
    });
</script>