<div>
    <!-- Afficher le message de succès -->
    @if (session()->has('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif
    <div class="card">
        <div class="card-header row">
            <h1 class="card-title col-md-10">{{$title}}</h1>
             <div class="col-md-2 text-right">
                @if($status == "info")
                    <button wire:click='change("list")' class="btn btn-primary"><span class="btn-icon-left text-primary"><i class="fa fa-hand-o-left"></i></span>Retour</button>
                @endif
              {{--  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#basicModal"><i class="fa fa-plus"></i> Ajouter</button>
                @include('livewire.campus.modalAdd')--}}
            </div> 
        </div>
        <div class="card-body">
            @if($status == "info")
                @include('livewire.campus.info')
            @else
                <div class="table-responsive">
                    <table wire:ignore.self id="example2" class="display text-center table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Nom</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th>Etat</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($camps as $c)
                                <tr id="campus-{{$c->id}}">
                                    <td>
                                        <img width="100" height="70" src="{{asset('images/'.$c->image)}}" alt="">
                                    </td>
                                    <td>{{$c->nom}}</td>
                                    <td>{{$c->tel}}</td>
                                    <td>{{$c->email}}</td>
                                    <td>
                                        @if($c->statut == 1)
                                            <span class="badge badge-success">Ouvert</span>
                                        @else 
                                            <span class="badge badge-danger">Fermé</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" wire:click='getCampus({{$c->id}})' class="btn btn-sm btn-primary" title="Information"><i class="fa fa-eye"></i></button>
                                        @if($c->statut == 1)
                                            <button type="button" class="btn btn-sm btn-warning" title="Fermer" wire:click='changeStatus({{$c->id}}, "ferme")'><i class="fa fa-lock"></i></button>
                                        @else 
                                            <button type="button" class="btn btn-sm btn-warning" title="Ouvrir" wire:click='changeStatus({{$c->id}}, "actif")'><i class="fa fa-unlock"></i></button>
                                        @endif
                                        {{-- <button class="btn btn-sm btn-danger"  title="Supprimer" wire:confirm='Etes vous sur'><i class="fa fa-trash"></i></button> --}}
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
    window.addEventListener('campusAdded', event =>{
        $('#basicModal').modal('hide');
        iziToast.success({
        title: 'Inscription',
        message: 'Ajout avec succes',
        position: 'topRight'
        });
    });

    window.addEventListener('actif', event =>{
        $('#basicModal').modal('hide');
        iziToast.success({
        title: 'Campus',
        message: 'ouvert avec succes',
        position: 'topRight'
        });
    });

    window.addEventListener('ferme', event =>{
        $('#basicModal').modal('hide');
        iziToast.warning({
        title: 'Campus',
        message: 'fermé avec succes',
        position: 'topRight'
        });
    });
</script>