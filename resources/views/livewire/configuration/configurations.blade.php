<div>
    <div class="row">
        <div class="col-xl-4 col-xxl-6 col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title list-title"><i class="fa fa-list-alt"></i> Classes</h4>
                    @if($classe["idclasse"])
                        <i title="Actualiser" wire:click='initialiser("classe")' style="cursor: pointer;" class="fa fa-refresh text-primary fa-2x"></i>
                    @endif
                </div>
                <div class="card-body">
                    <ul class="mb-3 pb-3 ml-3 pl-3 mr-3 pr-3 list-config">
                        @foreach($classes as $c)
                        <li class="row" id="classe-{{$c->id}}">
                            <span class="col-md-8">{{ $c->nom }}</span>
                            <div  class="col-md-4 text-right item-actions">
                                <a href="#" wire:click='getClasse({{$c->id}})'><i class="fa fa-edit text-primary" style="font-size: 20px"></i></a>
                                <a href="#" data-toggle="modal" data-target="#modalId{{$c->id}}"><i class="fa fa-trash text-danger" style="font-size: 20px"></i></a>
                            </div>
                        </li>

                        <!-- Modal -->
                        <div
                            class="modal fade modalId"
                            id="modalId{{$c->id}}"
                            tabindex="-1"
                            role="dialog"
                            aria-labelledby="modalTitleId"
                            aria-hidden="true"
                        >
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalTitleId">
                                        Suppression
                                        </h5>
                                        <button
                                        type="button"
                                        class="close"
                                        data-dismiss="modal"
                                        aria-label="Close"
                                        >&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir supprimer?
                                    </div>
                                    <div class="modal-footer">
                                        <button
                                        type="button"
                                        class="btn btn-success"
                                        data-dismiss="modal"
                                        >
                                        Non
                                        </button>
                                        <button type="button" wire:click='supprimerClasse({{$c->id}})'  class="btn btn-danger">Oui</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </ul>
                    <form wire:submit='storeClasse' action="" method="post" class="row m-2">
                        <input type="text" wire:model='classe.nom' required placeholder="Veuillez saisir le nom de la classe" class="form-control col-md-9">
                        @if($classe["idclasse"])
                            <button type="submit" class="btn btn-warning col-md-3"><i class="fa fa-edit"></i> Modifier</button>
                        @else 
                            <button type="submit" class="btn btn-primary col-md-3"><i class="fa fa-plus"></i> Ajouter</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-xxl-6 col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title list-title"><i class="fa fa-list-alt"></i> Filières</h4>
                    @if($filiere["idfiliere"])
                        <i title="Actualiser" wire:click='initialiser("filiere")' style="cursor: pointer;" class="fa fa-refresh text-primary fa-2x"></i>
                    @endif
                </div>
                <div class="card-body">
                    <ul class="mb-3 pb-3 ml-3 pl-3 mr-3 pr-3 list-config">
                        @foreach($filieres as $f)
                        <li class="row" id="classe-{{$f->id}}">
                            <span class="col-md-8">{{ $f->nom }}</span>
                            <div  class="col-md-4 text-right item-actions">
                                <a  wire:click='getFiliere({{$f->id}})'><i class="fa fa-edit text-primary" style="font-size: 20px"></i></a>
                                <a  data-toggle="modal" data-target="#modalId{{$f->id}}"><i class="fa fa-trash text-danger" style="font-size: 20px"></i></a>
                            </div>
                        </li>

                        <!-- Modal -->
                        <div
                            class="modal fade modalId"
                            id="modalId{{$f->id}}"
                            tabindex="-1"
                            role="dialog"
                            aria-labelledby="modalTitleId"
                            aria-hidden="true"
                        >
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalTitleId">
                                        Suppression
                                        </h5>
                                        <button
                                        type="button"
                                        class="close"
                                        data-dismiss="modal"
                                        aria-label="Close"
                                        >&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir supprimer?
                                    </div>
                                    <div class="modal-footer">
                                        <button
                                        type="button"
                                        class="btn btn-success"
                                        data-dismiss="modal"
                                        >
                                        Non
                                        </button>
                                        <button type="button" wire:click='supprimerFiliere({{$f->id}})'  class="btn btn-danger">Oui</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </ul>
                    <form wire:submit='storeFiliere' action="" method="post" class="row m-2">
                        <input type="text" wire:model='filiere.nom' required placeholder="Veuillez saisir le nom du filière" class="form-control col-md-9">
                        @if($filiere["idfiliere"])
                            <button type="submit" class="btn btn-warning col-md-3"><i class="fa fa-edit"></i> Modifier</button>
                        @else 
                            <button type="submit" class="btn btn-primary col-md-3"><i class="fa fa-plus"></i> Ajouter</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-xxl-6 col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title list-title"><i class="fa fa-list-alt"></i> Départements</h4>
                    @if($departement["iddepartement"])
                        <i title="Actualiser" wire:click='initialiser("departement")' style="cursor: pointer;" class="fa fa-refresh text-primary fa-2x"></i>
                    @endif
                </div>
                <div class="card-body">
                    <ul class="mb-3 pb-3 ml-3 pl-3 mr-3 pr-3 list-config">
                        @foreach($departements as $d)
                        <li class="row" id="classe-{{$d->id}}">
                            <span class="col-md-8">{{ $d->nom }}</span>
                            <div  class="col-md-4 text-right item-actions">
                                <a  wire:click='getDepartement({{$d->id}})'><i class="fa fa-edit text-primary" style="font-size: 20px"></i></a>
                                <a  data-toggle="modal" data-target="#modalId{{$d->id}}"><i class="fa fa-trash text-danger" style="font-size: 20px"></i></a>
                            </div>
                        </li>

                        <!-- Modal -->
                        <div
                            class="modal fade modalId"
                            id="modalId{{$d->id}}"
                            tabindex="-1"
                            role="dialog"
                            aria-labelledby="modalTitleId"
                            aria-hidden="true"
                        >
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalTitleId">
                                        Suppression
                                        </h5>
                                        <button
                                        type="button"
                                        class="close"
                                        data-dismiss="modal"
                                        aria-label="Close"
                                        >&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir supprimer?
                                    </div>
                                    <div class="modal-footer">
                                        <button
                                        type="button"
                                        class="btn btn-success"
                                        data-dismiss="modal"
                                        >
                                        Non
                                        </button>
                                        <button type="button" wire:click='supprimerDepartement({{$d->id}})'  class="btn btn-danger">Oui</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </ul>
                    <form wire:submit='storeDepartement' action="" method="post" class="row m-2">
                        <input type="text" wire:model='departement.nom' required placeholder="Veuillez saisir le nom du département" class="form-control col-md-9">
                        @if($departement["iddepartement"])
                            <button type="submit" class="btn btn-warning col-md-3"><i class="fa fa-edit"></i> Modifier</button>
                        @else 
                            <button type="submit" class="btn btn-primary col-md-3"><i class="fa fa-plus"></i> Ajouter</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-xxl-6 col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title list-title"><i class="fa fa-list-alt"></i> Unités d'enseignements (u.e)</h4>
                    @if($departement["iddepartement"])
                        <i title="Actualiser" wire:click='initialiser("departement")' style="cursor: pointer;" class="fa fa-refresh text-primary fa-2x"></i>
                    @endif
                </div>
                <div class="card-body">
                    <ul class="mb-3 pb-3 ml-3 pl-3 mr-3 pr-3 list-config">
                        @foreach($departements as $d)
                        <li class="row" id="classe-{{$d->id}}">
                            <span class="col-md-8">{{ $d->nom }}</span>
                            <div  class="col-md-4 text-right item-actions">
                                <a  wire:click='getDepartement({{$d->id}})'><i class="fa fa-edit text-primary" style="font-size: 20px"></i></a>
                                <a  data-toggle="modal" data-target="#modalId{{$d->id}}"><i class="fa fa-trash text-danger" style="font-size: 20px"></i></a>
                            </div>
                        </li>

                        <!-- Modal -->
                        <div
                            class="modal fade modalId"
                            id="modalId{{$d->id}}"
                            tabindex="-1"
                            role="dialog"
                            aria-labelledby="modalTitleId"
                            aria-hidden="true"
                        >
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalTitleId">
                                        Suppression
                                        </h5>
                                        <button
                                        type="button"
                                        class="close"
                                        data-dismiss="modal"
                                        aria-label="Close"
                                        >&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir supprimer?
                                    </div>
                                    <div class="modal-footer">
                                        <button
                                        type="button"
                                        class="btn btn-success"
                                        data-dismiss="modal"
                                        >
                                        Non
                                        </button>
                                        <button type="button" wire:click='supprimerDepartement({{$d->id}})'  class="btn btn-danger">Oui</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </ul>
                    <form wire:submit='storeDepartement' action="" method="post" class="row m-2">
                        <input type="text" wire:model='departement.nom' required placeholder="Veuillez saisir le nom" class="form-control col-md-8">
                        <input type="number" wire:model='' required placeholder="Crédit" class="form-control col-md-4">
                        @if($departement["iddepartement"])
                            <button type="submit" class="btn btn-warning col-md-3"><i class="fa fa-edit"></i> Modifier</button>
                        @else 
                            <button type="submit" class="btn btn-primary col-md-3"><i class="fa fa-plus"></i> Ajouter</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('css')
    <style>
        .list-config{
            max-height: 250px;overflow-y:scroll;
        }
        .list-title{
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            font-weight: bold;
            text-transform: uppercase;
        }
        .list-config li{
            cursor: pointer;
            color:#343957;
            line-height: 40px;
            font-size: 14px;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }

        .list-config li:hover{
            background: #d8d8d8;
        }
        .list-config li:hover > span + .item-actions{
            visibility: visible;
        }

        .item-actions{
            visibility: hidden;
        }
    </style>
@endsection

<script>
    window.addEventListener('added', event =>{
        iziToast.success({
        title: 'Configurations',
        message: 'Ajout avec succes',
        position: 'topRight'
        });
    });

    window.addEventListener('updated', event =>{
        iziToast.success({
        title: 'Configurations',
        message: 'Mis à jour avec succes',
        position: 'topRight'
        });
    });

    window.addEventListener('deleted', event =>{
        iziToast.success({
        title: 'Configurations',
        message: 'Suppression avec succes',
        position: 'topRight'
        });

        $('.modalId').modal('hide');
    });
</script>
