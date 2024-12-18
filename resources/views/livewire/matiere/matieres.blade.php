<div>
    <div class="card">
        <div class="card-header row">
            <h1 class="card-title col-md-10">{{$title}}</h1>
            <div class="col-md-2 text-right">
                @if($status == "list")
                <button wire:click='changeStatus("add")' class="btn btn-success"><span class="btn-icon-left text-primary"><i class="fa fa-plus"></i></span>Ajouter</button>
                @else
                <a href="{{route('matiere')}}" class="btn btn-primary"><span class="btn-icon-left text-primary"><i class="fa fa-hand-o-left"></i></span>Retour</a>
                @endif
            </div> 
        </div>
        <div class="card-body">
            @if($status != "list")
                @include('livewire.matiere.add')
            @else
                <div class="table-responsive">
                    <table id="myTable" class="table text-center table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Coefficient</th>
                                <th>Crédit</th>
                                <th>Filiere</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($matieres as $m)
                                <tr id="mat-{{$m->id}}">
                                    <td>{{ $m->nom }}</td>
                                    <td>{{ $m->coef }}</td>
                                    <td>{{ $m->credit }}</td>
                                    <td>{{ $m->filiere->nom}}</td>
                                    <td>
                                        <button type="button" wire:click='getInfo({{$m->id}})' class="btn btn-sm btn-primary" title="Information"><i class="fa fa-eye"></i></button>
                                        <button type="button" data-toggle="modal" data-target="#modalMatId{{$m->id}}" class="btn  btn-danger "><i class="fa fa-trash"></i></button>
                                        <!-- Button trigger modal -->
                                        
                                        <!-- Modal -->
                                        <div
                                            class="modal fade modalMatId"
                                            id="modalMatId{{$m->id}}"
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
                                                    <button type="button" wire:click='supprimer({{$m->id}})'  class="btn btn-danger">Oui</button>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    window.addEventListener('added', event =>{
        iziToast.success({
        title: 'Unite d\'nseignement',
        message: 'Ajout avec succes',
        position: 'topRight'
        });
    });


    window.addEventListener('update', event =>{
        iziToast.success({
        title: 'Unité d\'nseignement',
        message: 'mise à jour avec succes',
        position: 'topRight'
        });
    });

    window.addEventListener('delete', event =>{
        iziToast.success({
            title: 'Unité d\'enseignement',
            message: 'supprimée avec succes',
            position: 'topRight'
        });
    });
</script>