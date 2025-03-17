<div>
    <div class="row">
        {{-- partie gauche  --}}
        <div class="col-xl-6 col-xxl-6 col-lg-6 col-md-6 col-sm-12">
            <div class="row">
                <div class="col-xl-12 col-xxl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="accordion-one" class="accordion accordion-with-icon">
                                <div class="accordion__item">
                                    <div class="accordion__header collapsed" data-toggle="collapse" data-target="#with-icon_collapseOne" aria-expanded="false">
                                        <span class="fa fa-cog"></span>
                                        <span class="accordion__header--text  list-title">Départements</span>
                                        <span class="accordion__header--indicator indicator_bordered"></span>
                                    </div>
                                    <div id="with-icon_collapseOne" class="accordion__body collapse show" data-parent="#accordion-one" style="">
                                        <div class="accordion__body--text">
                                            <ul class="mb-3 pb-3 ml-3 pl-3 mr-3 pr-3 list-config">
                                                @foreach($departements as $d)
                                                <li class="row" id="classe-{{$d->id}}">
                                                    <span class="col-md-8"><i class="fa fa-tag" aria-hidden="true"></i>
                                                        {{ $d->nom }}</span>
                                                    <div class="col-md-4 text-right item-actions">
                                                        <a wire:click='getDepartement({{$d->id}})'><i class="fa fa-edit text-primary" style="font-size: 20px"></i></a>
                                                        <a data-toggle="modal" data-target="#modalId{{$d->id}}"><i class="fa fa-trash text-danger" style="font-size: 20px"></i></a>
                                                    </div>
                                                </li>

                                                <!-- Modal -->
                                                <div
                                                    class="modal fade modalId"
                                                    id="modalId{{$d->id}}"
                                                    tabindex="-1"
                                                    role="dialog"
                                                    aria-labelledby="modalTitleId"
                                                    aria-hidden="true">
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
                                                                    aria-label="Close">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Êtes-vous sûr de vouloir supprimer?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-success"
                                                                    data-dismiss="modal">
                                                                    Non
                                                                </button>
                                                                <button type="button" wire:click='supprimerDepartement({{$d->id}})' class="btn btn-danger">Oui</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </ul>
                                            <hr>
                                            <form wire:submit='storeDepartement' action="" method="post" class="row">
                                                @error('departement.nom') <span class="col-md-12 text-danger">{{ $message }}</span> @enderror
                                                <input type="text" wire:model='departement.nom' placeholder="Veuillez saisir le nom du département" class="form-control col-md-8">
                                                @if($departement["iddepartement"])
                                                <div class="col-md-4">

                                                    <i title="Actualiser" wire:click='initialiser("departement")' style="cursor: pointer;" class="fa fa-refresh text-primary fa-x"></i>
                                                    <button type="submit" class="btn btn-warning">Modifier</button>
                                                </div>
                                                @else
                                                <button type="submit" class="btn btn-primary col-md-3"><i class="fa fa-plus"></i> Ajouter</button>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-xxl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="accordion-three" class="accordion accordion-with-icon">
                                <div class="accordion__item">
                                    <div class="accordion__header collapsed" data-toggle="collapse" data-target="#with-icon_collapseThree" aria-expanded="false">
                                        <span class="fa fa-cog"></span>
                                        <span class="accordion__header--text  list-title">Classes</span>
                                        <span class="accordion__header--indicator indicator_bordered"></span>
                                    </div>
                                    <div id="with-icon_collapseThree" class="accordion__body collapse show" data-parent="#accordion-three" style="">
                                        <div class="accordion__body--text">
                                            <ul class="mb-3 pb-3 ml-3 pl-3 mr-3 pr-3 list-config">
                                                @foreach($classes as $c)
                                                <li class="row" id="classe-{{$c->id}}">
                                                    <span class="col-md-8"><i class="fa fa-tag" aria-hidden="true"></i>
                                                        {{ $c->nom }}</span>
                                                    <div class="col-md-4 text-right item-actions">
                                                        <a wire:click='getClasse({{$c->id}})'><i class="fa fa-edit text-primary" style="font-size: 20px"></i></a>
                                                        <a data-toggle="modal" data-target="#modalId{{$c->id}}"><i class="fa fa-trash text-danger" style="font-size: 20px"></i></a>
                                                    </div>
                                                    <span class="col-md-12 subtitle">Filière<i class="fa fa-long-arrow-right" aria-hidden="true"></i> {{ $c->filiere->nom }}</span>
                                                </li>

                                                <!-- Modal -->
                                                <div
                                                    class="modal fade modalId"
                                                    id="modalId{{$c->id}}"
                                                    tabindex="-1"
                                                    role="dialog"
                                                    aria-labelledby="modalTitleId"
                                                    aria-hidden="true">
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
                                                                    aria-label="Close">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Êtes-vous sûr de vouloir supprimer?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-success"
                                                                    data-dismiss="modal">
                                                                    Non
                                                                </button>
                                                                <button type="button" wire:click='supprimerClasse({{$c->id}})' class="btn btn-danger">Oui</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </ul>
                                            <hr>
                                            <form wire:submit='storeClasse' action="" method="post" class="row">
                                                @error('classe.filiere_id') <span class="col-md-12 text-danger">{{ $message }}</span> @enderror
                                                <select name="" wire:model='classe.filiere_id' class="col-md-12 form-control mb-2" id="">
                                                    <option value="">Selectionner un filière </option>
                                                    @foreach($filieres as $f)
                                                    <option value="{{ $f->id }}">{{ $f->nom }}</option>
                                                    @endforeach
                                                </select>
                                                @error('classe.nom') <span class="col-md-12 text-danger">{{ $message }}</span> @enderror
                                                <input type="text" wire:model='classe.nom' required placeholder="Veuillez saisir le nom de la classe" class="form-control col-md-8">
                                                @if($classe["idclasse"])
                                                <div class="col-md-4">

                                                    <i title="Actualiser" wire:click='initialiser("classe")' style="cursor: pointer;" class="fa fa-refresh text-primary fa-x"></i>
                                                    <button type="submit" class="btn btn-warning">Modifier</button>
                                                </div>
                                                @else
                                                <button type="submit" class="btn btn-primary col-md-3"><i class="fa fa-plus"></i> Ajouter</button>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-xxl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="accordion-one" class="accordion accordion-with-icon">
                                <div class="accordion__item">
                                    <div class="accordion__header collapsed" data-toggle="collapse" data-target="#with-icon_collapseOne" aria-expanded="false">
                                        <span class="fa fa-cog"></span>
                                        <span class="accordion__header--text  list-title">Coefficients</span>
                                        <span class="accordion__header--indicator indicator_bordered"></span>
                                    </div>
                                    <div id="with-icon_collapseOne" class="accordion__body collapse show" data-parent="#accordion-one" style="">
                                        <div class="accordion__body--text">
                                            <ul class="mb-3 pb-3 ml-3 pl-3 mr-3 pr-3 list-config">
                                                @foreach($coefs as $c)
                                                <li class="row" id="classe-{{$c->id}}">
                                                    <span class="col-md-8"><i class="fa fa-tag" aria-hidden="true"></i>
                                                        {{ $c->valeur }}</span>
                                                    <div class="col-md-4 text-right item-actions">
                                                        <a wire:click='getCoefficient({{$c->id}})'><i class="fa fa-edit text-primary" style="font-size: 20px"></i></a>
                                                        <a data-toggle="modal" data-target="#coefId{{$c->id}}"><i class="fa fa-trash text-danger" style="font-size: 20px"></i></a>
                                                    </div>
                                                </li>

                                                <!-- Modal -->
                                                <div
                                                    class="modal fade modalId"
                                                    id="coefId{{$d->id}}"
                                                    tabindex="-1"
                                                    role="dialog"
                                                    aria-labelledby="modalTitleId"
                                                    aria-hidden="true">
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
                                                                    aria-label="Close">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Êtes-vous sûr de vouloir supprimer?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-success"
                                                                    data-dismiss="modal">
                                                                    Non
                                                                </button>
                                                                <button type="button" wire:click='supprimerDepartement({{$d->id}})' class="btn btn-danger">Oui</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </ul>
                                            <hr>
                                            <form wire:submit='storeCoefficient' action="" method="post" class="row">
                                                @error('coef.valeur') <span class="col-md-12 text-danger">{{ $message }}</span> @enderror
                                                <input type="number" min="1" wire:model='coef.valeur' placeholder="Veuillez saisir la valeur" class="form-control col-md-8">
                                                @if($coef["idcoef"])
                                                <div class="col-md-4">

                                                    <i title="Actualiser" wire:click='initialiser("coef")' style="cursor: pointer;" class="fa fa-refresh text-primary fa-x"></i>
                                                    <button type="submit" class="btn btn-warning">Modifier</button>
                                                </div>
                                                @else
                                                <button type="submit" class="btn btn-primary col-md-3"><i class="fa fa-plus"></i> Ajouter</button>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-xxl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="accordion-one" class="accordion accordion-with-icon">
                                <div class="accordion__item">
                                    <div class="accordion__header collapsed" data-toggle="collapse" data-target="#with-icon_collapseOne" aria-expanded="false">
                                        <span class="fa fa-cog"></span>
                                        <span class="accordion__header--text  list-title">Salles</span>
                                        <span class="accordion__header--indicator indicator_bordered"></span>
                                    </div>
                                    <div id="with-icon_collapseOne" class="accordion__body collapse show" data-parent="#accordion-one" style="">
                                        <div class="accordion__body--text">
                                            <ul class="mb-3 pb-3 ml-3 pl-3 mr-3 pr-3 list-config">
                                                @foreach($salles as $s)
                                                <li class="row" id="salle-{{$s->id}}">
                                                    <span class="col-md-8"><i class="fa fa-tag" aria-hidden="true"></i>
                                                        {{ $s->nom }}</span>
                                                    <div class="col-md-4 text-right item-actions">
                                                        <a wire:click='getSalle({{$s->id}})'><i class="fa fa-edit text-primary" style="font-size: 20px"></i></a>
                                                        <a data-toggle="modal" data-target="#salleId{{$s->id}}"><i class="fa fa-trash text-danger" style="font-size: 20px"></i></a>
                                                    </div>
                                                </li>

                                                <!-- Modal -->
                                                <div
                                                    class="modal fade modalId"
                                                    id="salleId{{$d->id}}"
                                                    tabindex="-1"
                                                    role="dialog"
                                                    aria-labelledby="modalTitleId"
                                                    aria-hidden="true">
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
                                                                    aria-label="Close">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Êtes-vous sûr de vouloir supprimer?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-success"
                                                                    data-dismiss="modal">
                                                                    Non
                                                                </button>
                                                                <button type="button" wire:click='supprimerSalle({{$s->id}})' class="btn btn-danger">Oui</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </ul>
                                            <hr>
                                            <form wire:submit='storeSalle' action="" method="post" class="row">
                                                @error('salle.nom') <span class="col-md-12 text-danger">{{ $message }}</span> @enderror
                                                <input type="text" wire:model='salle.nom' placeholder="Veuillez saisir le nom" class="form-control col-md-8">
                                                @if($salle["idsalle"])
                                                <div class="col-md-4">

                                                    <i title="Actualiser" wire:click='initialiser("salle")' style="cursor: pointer;" class="fa fa-refresh text-primary fa-x"></i>
                                                    <button type="submit" class="btn btn-warning">Modifier</button>
                                                </div>
                                                @else
                                                <button type="submit" class="btn btn-primary col-md-3"><i class="fa fa-plus"></i> Ajouter</button>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- partie droite  --}}
        <div class="col-xl-6 col-xxl-6 col-lg-6 col-md-6 col-sm-12">
            <div class="row">
                <div class="col-xl-12 col-xxl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="accordion-two" class="accordion accordion-with-icon">
                                <div class="accordion__item">
                                    <div class="accordion__header collapsed" data-toggle="collapse" data-target="#with-icon_collapseTwo" aria-expanded="false">
                                        <span class="fa fa-cog"></span>
                                        <span class="accordion__header--text  list-title">Filières</span>
                                        <span class="accordion__header--indicator indicator_bordered"></span>
                                    </div>
                                    <div id="with-icon_collapseTwo" class="accordion__body collapse show" data-parent="#accordion-two" style="">
                                        <div class="accordion__body--text">
                                            <ul class="mb-3 pb-3 ml-3 pl-3 mr-3 pr-3 list-config">
                                                @foreach($filieres as $f)
                                                <li class="row" id="classe-{{$f->id}}">
                                                    <span class="col-md-8"><i class="fa fa-tag" aria-hidden="true"></i>
                                                        {{ $f->nom }}</span>
                                                    <div class="col-md-4 text-right item-actions">
                                                        <a wire:click='getFiliere({{$f->id}})'><i class="fa fa-edit text-primary" style="font-size: 20px"></i></a>
                                                        <a data-toggle="modal" data-target="#modalId{{$f->id}}"><i class="fa fa-trash text-danger" style="font-size: 20px"></i></a>
                                                    </div>
                                                    <span class="col-md-12 subtitle">Département<i class="fa fa-long-arrow-right" aria-hidden="true"></i> {{ $f->departement->nom }}</span>
                                                </li>

                                                <!-- Modal -->
                                                <div
                                                    class="modal fade modalId"
                                                    id="modalId{{$f->id}}"
                                                    tabindex="-1"
                                                    role="dialog"
                                                    aria-labelledby="modalTitleId"
                                                    aria-hidden="true">
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
                                                                    aria-label="Close">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Êtes-vous sûr de vouloir supprimer?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-success"
                                                                    data-dismiss="modal">
                                                                    Non
                                                                </button>
                                                                <button type="button" wire:click='supprimerFiliere({{$f->id}})' class="btn btn-danger">Oui</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </ul>
                                            <hr>
                                            <form wire:submit='storeFiliere' action="" method="post" class="row">
                                                @error('filiere.departement_id') <span class="col-md-12 text-danger">{{ $message }}</span> @enderror
                                                <select name="" wire:model='filiere.departement_id' class="col-md-12 form-control mb-2" id="">
                                                    <option value="">Selectionner un département </option>
                                                    @foreach($departements as $d)
                                                    <option value="{{ $d->id }}">{{ $d->nom }}</option>
                                                    @endforeach
                                                </select>
                                                @error('filiere.nom') <span class="col-md-12 text-danger">{{ $message }}</span> @enderror
                                                <input type="text" wire:model='filiere.nom' required placeholder="Veuillez saisir le nom du filière" class="form-control col-md-8">
                                                @if($filiere["idfiliere"])
                                                <div class="col-md-4">

                                                    <i title="Actualiser" wire:click='initialiser("filiere")' style="cursor: pointer;" class="fa fa-refresh text-primary fa-x"></i>
                                                    <button type="submit" class="btn btn-warning">Modifier</button>
                                                </div>
                                                @else
                                                <button type="submit" class="btn btn-primary col-md-3"><i class="fa fa-plus"></i> Ajouter</button>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-xxl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="accordion-four" class="accordion accordion-with-icon">
                                <div class="accordion__item">
                                    <div class="accordion__header collapsed" data-toggle="collapse" data-target="#with-icon_collapseFour" aria-expanded="false">
                                        <span class="fa fa-cog"></span>
                                        <span class="accordion__header--text  list-title">Unités d'enseignements (u.e)</span>
                                        <span class="accordion__header--indicator indicator_bordered"></span>
                                    </div>
                                    <div id="with-icon_collapseFour" class="accordion__body collapse show" data-parent="#accordion-four" style="">
                                        <div class="accordion__body--text">
                                            <ul class="mb-3 pb-3 ml-3 pl-3 mr-3 pr-3 list-config">
                                                @foreach($ues as $u)
                                                <li class="row" id="ue-{{$u->id}}">
                                                    <span class="col-md-8"><i class="fa fa-tag" aria-hidden="true"></i>
                                                        {{ $u->nom }}</span>
                                                    <div class="col-md-4 text-right item-actions">
                                                        <a wire:click='getUe({{$u->id}})'><i class="fa fa-edit text-primary" style="font-size: 20px"></i></a>
                                                        <a data-toggle="modal" data-target="#ueId{{$u->id}}"><i class="fa fa-trash text-danger" style="font-size: 20px"></i></a>
                                                    </div>
                                                    <span class="col-md-12 subtitle">Crédit<i class="fa fa-long-arrow-right" aria-hidden="true"></i> {{ $u->credit }}</span>
                                                    <span class="col-md-12 subtitle">Filière<i class="fa fa-long-arrow-right" aria-hidden="true"></i> {{ $u->filiere->nom }}</span>
                                                    <span class="col-md-12 subtitle">Disciplines<i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                                        @foreach($u->matieres as $m)
                                                        {{ $m->nom }},
                                                        @endforeach
                                                    </span>
                                                </li>

                                                <!-- Modal -->
                                                <div
                                                    class="modal fade modalId"
                                                    id="ueId{{$u->id}}"
                                                    tabindex="-1"
                                                    role="dialog"
                                                    aria-labelledby="modalTitleId"
                                                    aria-hidden="true">
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
                                                                    aria-label="Close">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Êtes-vous sûr de vouloir supprimer?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-success"
                                                                    data-dismiss="modal">
                                                                    Non
                                                                </button>
                                                                <button type="button" wire:click='supprimerUe({{$u->id}})' class="btn btn-danger">Oui</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </ul>
                                            <hr>
                                            <form wire:submit='storeUe' action="" method="post" class="row">
                                                @error('ue.nom') <span class="col-md-12 text-danger">{{ $message }}</span> @enderror
                                                @error('ue.credit') <span class="col-md-12 text-danger">{{ $message }}</span> @enderror
                                                <input type="text" wire:model='ue.nom' required placeholder="Veuillez saisir le nom" class="form-control col-md-8">
                                                <input type="number" wire:model='ue.credit' required placeholder="Crédit" class="form-control col-md-4">
                                                @error('ue.filiere_id') <span class="col-md-12 mt-2 text-danger">{{ $message }}</span> @enderror
                                                <select name="" wire:model='ue.filiere_id' class="col-md-12 mt-2 form-control mb-2" id="">
                                                    <option value="">Selectionner un filière </option>
                                                    @foreach($filieres as $f)
                                                    <option value="{{ $f->id }}">{{ $f->nom }}</option>
                                                    @endforeach
                                                </select>
                                                @error('ue.disciplines') <span class="col-md-12 text-danger">{{ $message }}</span> @enderror
                                                <div class="form-group col-md-12">
                                                    <label for="">Disciplines</label>
                                                    <input type="text" wire:model.live="ue.valeur" placeholder="Saisissez des disciplines et séparez-les par des virgules" class="form-control" />
                                                </div>
                                                <div class="col-md-12 m-1 flex flex-wrap space-x-2">
                                                    @foreach ($matieres as $index => $tag)
                                                    @if($tag["delete"] == false)
                                                    <button type="button" class="btn btn-outline-secondary btn-rounded mb-2 btn-sm">{{ $tag["nom"] }} <span class="btn-icon-right" wire:click="supprimerMatiere({{ $index }})"
                                                            class="text-danger"><i class="fa fa-close"></i></span>
                                                    </button>
                                                    @endif
                                                    @endforeach
                                                    @foreach ($ue["disciplines"] as $index => $tag)
                                                    <button type="button" class="btn btn-outline-secondary btn-rounded mb-2 btn-sm">{{ $tag }} <span class="btn-icon-right" wire:click="removeTag({{ $index }})"
                                                            class="text-danger"><i class="fa fa-close"></i></span>
                                                    </button>
                                                    @endforeach
                                                </div>
                                                @if($ue["idue"])
                                                <div class="col-md-4">

                                                    <i title="Actualiser" wire:click='initialiser("ue")' style="cursor: pointer;" class="fa fa-refresh text-primary fa-x"></i>
                                                    <button type="submit" class="btn btn-warning">Modifier</button>
                                                </div>
                                                @else
                                                <button type="submit" class="btn btn-primary col-md-3"><i class="fa fa-plus"></i> Ajouter</button>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@section('css')
<style>
    .list-config {
        max-height: 250px;
        overflow-y: scroll;
    }

    .list-title {
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        font-weight: bold;
        text-transform: uppercase;
    }

    .list-config li {
        cursor: pointer;
        color: #343957;
        line-height: 30px;
        font-size: 14px;
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
    }

    .list-config li .subtitle {
        color: #9a9eb4;
        font-size: 12px;
        text-indent: 20px;

    }

    .list-config li:hover {
        background: #d8d8d8;
        border-left: 5px solid #343957;
    }

    .list-config li:hover>span+.item-actions {
        visibility: visible;
    }

    .item-actions {
        visibility: hidden;
    }
</style>
@endsection

<script>
    window.addEventListener('added', event => {
        iziToast.success({
            title: 'Configurations',
            message: 'Ajout avec succes',
            position: 'topRight'
        });
    });

    window.addEventListener('updated', event => {
        iziToast.success({
            title: 'Configurations',
            message: 'Mis à jour avec succes',
            position: 'topRight'
        });
    });

    window.addEventListener('deleted', event => {
        iziToast.success({
            title: 'Configurations',
            message: 'Suppression avec succes',
            position: 'topRight'
        });

        $('.modalId').modal('hide');
    });
</script>