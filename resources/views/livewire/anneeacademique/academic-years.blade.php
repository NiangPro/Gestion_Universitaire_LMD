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
                    <table id="myTable" class="table table-bordered" style="width:100%">
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
                                        <button type="button" wire:click='' class="btn btn-sm btn-primary" title="Information"><i class="fa fa-eye"></i></button>
                                        @if($a->encours)
                                            <button type="button" class="btn btn-sm btn-warning" title="Desactiver" wire:click='desactiver({{$a->id}}, "ferme")'><i class="fa fa-lock"></i></button>
                                        @else 
                                            <button type="button" class="btn btn-sm btn-warning" title="Activer" wire:click='activer({{$a->id}})'><i class="fa fa-unlock"></i></button>
                                        @endif
                                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalId" title="Supprimer"><i class="fa fa-trash"></i></button>

                                        
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

    window.addEventListener('desactif', event =>{
        iziToast.warning({
            title: 'Année académique',
        message: 'deséactivée avec succes',
        position: 'topRight'
        });
    });
    window.addEventListener('deleteCampus', event =>{
        $('.modalId').modal('hide');
        iziToast.warning({
        title: 'Campus',
        message: 'Supprimé avec succes',
        position: 'topRight'
        });
    });
</script>