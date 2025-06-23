<div class="col-xl-12 col-xxl-12 col-lg-12 col-md-12 col-sm-12">
    <div class="card">
        <div class="card-body">
            <div class="accordion accordion-with-icon">
                <div class="accordion__item">
                    <div class="accordion__header" data-bs-toggle="collapse" data-bs-target="#type-evaluations-collapse">
                        <span class="fa fa-cog"></span>
                        <span class="accordion__header--text list-title">Types d'évaluation</span>
                        <span class="accordion__header--indicator fa fa-minus"></span>
                    </div>
                    <div id="type-evaluations-collapse" class="collapse show">
                        <div class="accordion__body--text">
                            <ul class="mb-3 pb-3 ml-3 pl-3 mr-3 pr-3 list-config">
                                @foreach($typeEvaluations as $type)
                                <li class="row" id="type-evaluation-{{$type->id}}">
                                    <span class="col-md-8"><i class="fa fa-tag" aria-hidden="true"></i>
                                        {{ $type->nom }}</span>
                                    <div class="col-md-4 text-right item-actions">
                                        <button class="btn btn-sm btn-primary" wire:click="edit({{$type->id}})">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" wire:click="delete({{$type->id}})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            <hr>
                            <form wire:submit='storeEvaluation' action="" method="post" class="row">
                                @error('evaluation.nom') <span class="col-md-12 text-danger">{{ $message }}</span> @enderror
                                <input type="text" class="form-control col-md-8" placeholder="Nouveau type d'évaluation" wire:model="evaluation.nom">

                                @if($evaluation["idtype"])
                                <div class="col-md-4">
                                    <i title="Actualiser" wire:click='initialiser("evaluation")' style="cursor: pointer;" class="fa fa-refresh text-primary fa-x"></i>
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