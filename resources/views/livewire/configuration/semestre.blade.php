<div class="col-xl-12 col-xxl-12 col-lg-12 col-md-12 col-sm-12">        
                    <div class="card">
                        <div class="card-body">
                            <div class="accordion accordion-with-icon">
                                <div class="accordion__item">
                                    <div class="accordion__header" data-bs-toggle="collapse" data-bs-target="#semestres-collapse">
                                        <span class="fa fa-cog"></span>
                                        <span class="accordion__header--text list-title">Semestres</span>
                                        <span class="accordion__header--indicator fa fa-minus"></span>
                                    </div>
                                    <div id="semestres-collapse" class="collapse show">
                                        <div class="accordion__body--text">
                                            <ul class="mb-3 pb-3 ml-3 pl-3 mr-3 pr-3 list-config">
                                                @foreach($semestres as $s)
                                                <li class="row" id="semestre-{{$s->id}}">
                                                    <span class="col-md-6"><i class="fa fa-tag" aria-hidden="true"></i>
                                                        {{ $s->nom }}</span>
                                                    <div class="col-md-3">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input" id="semestre-active-{{$s->id}}" wire:click="toggleSemestre({{$s->id}})" {{ $s->is_active ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="semestre-active-{{$s->id}}">{{ $s->is_active ? 'Actif' : 'Inactif' }}</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 text-right item-actions">
                                                        <a wire:click='getSemestre({{$s->id}})'><i class="fa fa-edit text-primary" style="font-size: 20px"></i></a>
                                                        <a data-toggle="modal" data-target="#semestreId{{$s->id}}"><i class="fa fa-trash text-danger" style="font-size: 20px"></i></a>
                                                    </div>
                                                </li>

                                                <!-- Modal -->
                                                <div
                                                    class="modal fade modalId"
                                                    id="semestreId{{$s->id}}"
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
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Êtes-vous sûr de vouloir supprimer?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                                                    Non
                                                                </button>
                                                                <button type="button" wire:click='supprimerSemestre({{$s->id}})' class="btn btn-danger">Oui</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </ul>
                                            <hr>
                                            <form wire:submit='storeSemestre' action="" method="post" class="row">
                                                @error('semestre.ordre') <span class="col-md-12 text-danger">{{ $message }}</span> @enderror
                                                <input type="number" min="1" max="10" wire:model='semestre.ordre' required placeholder="Ordre (Ex: 1)" class="form-control col-md-8">
                                                @if($semestre["idsemestre"])
                                                <div class="col-md-4">
                                                    <i title="Actualiser" wire:click='initialiser("semestre")' style="cursor: pointer;" class="fa fa-refresh text-primary fa-x"></i>
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