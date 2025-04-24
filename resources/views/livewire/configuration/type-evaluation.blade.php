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
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Nouveau type d'évaluation" wire:model="newTypeName">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" wire:click="add">Ajouter</button>
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