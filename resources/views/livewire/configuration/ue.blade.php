
<div class="col-xl-12 col-xxl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="accordion accordion-with-icon">
                                <div class="accordion__item">
                                    <div class="accordion__header" data-bs-toggle="collapse" data-bs-target="#ue-collapse">
                                        <span class="fa fa-cog"></span>
                                        <span class="accordion__header--text list-title">Unités d'enseignements (u.e)</span>
                                        <span class="accordion__header--indicator fa fa-minus"></span>
                                    </div>
                                    <div id="ue-collapse" class="collapse show">
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