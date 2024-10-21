<div>
    @if ($status == "list")
    <section class="pricing-section">
        <div class="container">
            <button wire:click='changeStatus("add")' class="btn btn-success"><span class="btn-icon-left text-success"><i class="fa fa-plus-circle"></i></span>Ajouter</button>
            <div class="card mt-2">
                <div class="card-header">
                    <h4 class="card-title">Liste des professeurs</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="myTable" class="display table-bordered" style="width:100%" wire:ignore.self>
                            <thead>
                                <tr>
                                    <th>Prenom</th>
                                    <th>Nom</th>
                                    <th>Sexe</th>
                                    <th>Téléphone</th>
                                    <th>Adresse</th>
                                    <th>Rôle</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $p)
                                    <tr>
                                        <td>{{$p->prenom}}</td>
                                        <td>{{$p->nom}}</td>
                                        <td>{{$p->sexe}}</td>
                                        <td>{{$p->tel}}</td>
                                        <td>{{$p->adresse}}</td>
                                        <td>{{$p->role}}</td>
                                        <td>
                                            <button type="button" wire:click='getProf({{$p->id}})' class="btn btn-sm btn-primary" title="Information"><i class="fa fa-eye"></i></button>
                                            {{-- <button type="button" wire:click='delete({{$p->id}})' class="btn btn-sm btn-danger" title="Suppression"><i class="fa fa-trash"></i></button> --}}
                                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalProf" title="Supprimer"><i class="fa fa-trash"></i></button>
                                            <div
                                                class="modal fade"
                                                id="modalProf"
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
                                                <div class="modal-body text-center">
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
    
                                                    <button type="button" wire:click='delete({{$p->id}})'  class="btn btn-danger">Oui</button>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Prenom</th>
                                    <th>Nom</th>
                                    <th>Sexe</th>
                                    <th>Téléphone</th>
                                    <th>Adresse</th>
                                    <th>Rôle</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @else       
        @include('livewire.personnel.professeur.add') 
    @endif
</div>


@section('script')
    <script>
      window.addEventListener('addSuccessful', event =>{
        iziToast.success({
        title: 'Professeur',
        message: 'Ajout avec succes',
        position: 'topRight'
        });
    });
    window.addEventListener('updateSuccessful', event =>{
        iziToast.success({
        title: 'Professeur',
        message: 'Mis à jour avec succes',
        position: 'topRight'
        });
    });
    </script>
@endsection