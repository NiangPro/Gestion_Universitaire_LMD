<div>
    <div class="card">
        <div class="card-header row">
            <h1 class="card-title col-md-10">{{$title}}</h1>
             <div class="col-md-2 text-right">
                @if($status == "list")
                <button wire:click='changeStatus("add")' class="btn btn-success"><span class="btn-icon-left text-primary"><i class="fa fa-plus"></i></span>Ajouter</button>
                @else
                <a href="{{route('uniteenseignement')}}" class="btn btn-primary"><span class="btn-icon-left text-primary"><i class="fa fa-hand-o-left"></i></span>Retour</a>
                @endif
            </div> 
        </div>
        <div class="card-body">
            @if($status != "list")
                @include('livewire.ue.add')
            @else
                <div class="table-responsive">
                    <table id="myTable" class="table text-center table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Filiere</th>
                                <th>Niveau d'étude</th>
                                <th>Credit</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($uniteEnseignement as $u)
                                <tr id="dept-{{$u->id}}">
                                    <td>{{ $u->nom }}</td>
                                    <td>{{ $u->filiere->nom}}</td>
                                    <td>{{ $u->niveauEtude->nom}}</td>
                                    <td>{{ $u->credit}}</td>
                                    <td>
                                        <button type="button" wire:click='getInfo({{$u->id}})' class="btn btn-sm btn-primary" title="Information"><i class="fa fa-eye"></i></button>
                                        <button type="button" data-toggle="modal" data-target="#modalUeId{{$u->id}}" class="btn  btn-danger "><i class="fa fa-trash"></i></button>
                                        <!-- Button trigger modal -->
                                        
                                        <!-- Modal -->
                                        <div
                                            class="modal fade modalUeId"
                                            id="modalUeId{{$u->id}}"
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
                                                    <button type="button" wire:click='supprimer({{$u->id}})'  class="btn btn-danger">Oui</button>
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