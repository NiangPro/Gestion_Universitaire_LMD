<div class="col-xl-12 col-xxl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="accordion accordion-with-icon">
                                <div class="accordion__item">
                                    <div class="accordion__header" data-bs-toggle="collapse" data-bs-target="#salles-collapse">
                                        <span class="fa fa-cog"></span>
                                        <span class="accordion__header--text list-title">Salles</span>
                                        <span class="accordion__header--indicator fa fa-minus"></span>
                                    </div>
                                    <div id="salles-collapse" class="collapse show">
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
                                                    id="salleId{{$s->id}}"
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