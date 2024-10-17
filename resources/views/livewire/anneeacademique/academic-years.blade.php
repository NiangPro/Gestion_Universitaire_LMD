<div>
    <div class="card">
        <div class="card-header row">
            <h1 class="card-title col-md-10">{{$title}}</h1>
             <div class="col-md-2 text-right">
                @if($status == "list")
                <button wire:click='changeStatus("add")' class="btn btn-success"><span class="btn-icon-left text-primary"><i class="fa fa-plus"></i></span>Ajouter</button>
                @else
                <a href="{{route('academicyear')}}" class="btn btn-primary"><span class="btn-icon-left text-primary"><i class="fa fa-hand-o-left"></i></span>Retour</a>
                @endif
              {{--  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#basicModal"><i class="fa fa-plus"></i> Ajouter</button>
                @include('livewire.campus.modalAdd')--}}
            </div> 
        </div>
        <div class="card-body">
            @if($status != "list")
                @include('livewire.anneeacademique.add')
            @else
                <div class="table-responsive">
                    <table id="myTable" class="table text-center table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Date d'ouverture</th>
                                <th>Date de fermeture</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($acs as $a)
                                <tr id="anac-{{$a->id}}">
                                    <td>{{date("d/m/Y", strtotime($a->debut))}}</td>
                                    <td>{{date("d/m/Y", strtotime($a->fin))}}</td>
                                    <td>
                                        @if($a->encours)
                                            <span class="badge badge-success">Actif</span>
                                        @else 
                                            <span class="badge badge-danger">Désactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" wire:click='getInfo({{$a->id}})' class="btn btn-sm btn-primary" title="Information"><i class="fa fa-eye"></i></button>
                                        @if($a->encours)
                                            <button type="button" class="btn btn-sm btn-warning" title="Desactiver" wire:click='desactiver({{$a->id}}, "ferme")'><i class="fa fa-lock"></i></button>
                                        @else 
                                            <button type="button" class="btn btn-sm btn-warning" title="Activer" wire:click='activer({{$a->id}})'><i class="fa fa-unlock"></i></button>
                                        @endif
                                        <button type="button" data-toggle="modal"
                                        data-target="#modalId{{$a->id}}" class="btn  btn-danger "><i class="fa fa-trash"></i></button>
                                        <!-- Button trigger modal -->
                                        
                                        <!-- Modal -->
                                        <div
                                            class="modal fade modalId"
                                            id="modalId{{$a->id}}"
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
                                                    <button type="button" wire:click='supprimer({{$a->id}})'  class="btn btn-danger">Oui</button>
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
        title: 'Année académique',
        message: 'Ajout avec succes',
        position: 'topRight'
        });
    });

    window.addEventListener('actif', event =>{
        iziToast.success({
        title: 'Année académique',
        message: 'activée avec succes',
        position: 'topRight'
        });
    });

    window.addEventListener('update', event =>{
        iziToast.success({
        title: 'Année académique',
        message: 'mise à jour avec succes',
        position: 'topRight'
        });
    });

    window.addEventListener('desactif', event =>{
        iziToast.warning({
            title: 'Année académique',
        message: 'deséactivée avec succes',
        position: 'topRight'
        });
    });
    window.addEventListener('delete', event =>{
        $('.modalId').modal('hide');
        iziToast.warning({
            title: 'Année académique',
            message: 'supprimée avec succes',
            position: 'topRight'
        });
    });
</script>