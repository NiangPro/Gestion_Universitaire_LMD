<div class="col-xl-12 col-xxl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="accordion accordion-with-icon">
                                <div class="accordion__item">
                                    <div class="accordion__header" data-bs-toggle="collapse" data-bs-target="#classes-collapse">
                                        <span class="fa fa-cog"></span>
                                        <span class="accordion__header--text list-title">Classes</span>
                                        <span class="accordion__header--indicator fa fa-minus"></span>
                                    </div>
                                    <div id="classes-collapse" class="collapse show">
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