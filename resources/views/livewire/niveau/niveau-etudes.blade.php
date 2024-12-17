<div>
    <div class="row">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header row">
                    <h4 class="card-title col-md-8">Formulaire d'@if($status == 'edit') édition @else ajout @endif niveau d'etude</h4>
                    @if($status == 'edit')
                        <div class="col-md-4 text-right">
                            <button wire:click='changeStatus("add")' class="btn btn-warning"><span class="btn-icon-left text-warning"><i class="fa fa-hand-o-left"></i></span>Retour</button>
                        </div>
                    @endif
                </div>    
                <div class="card-body">
                    <form wire:submit='store'>
                        <div class="form-group">
                            <label><strong>Nom</strong></label>
                            <input type="text" wire:model='nom' class="form-control @error('nom') error @enderror" placeholder="Veuillez entrer le niveau d'étude">
                            @error('nom') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        @if($status == 'edit')
                            <button type="submit" class="btn btn-warning">Modifier</button>
                        @else 
                            <button type="submit" class="btn btn-success">Ajouter</button>
                        @endif
                    </form>
                </div>
            </div>
            <!-- /# card -->
        </div>
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Liste des niveaux d'étude</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="myTable" class="table student-data-table m-t-20">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($level as $n)
                                <tr>
                                    <td>
                                        {{$n->nom}}
                                    </td>
                                    <td>
                                        <button type="button" wire:click='getLevel({{$n->id}})' class="btn btn-sm btn-primary" title="Information"><i class="fa fa-eye"></i></button>
                                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalLevel" title="Supprimer"><i class="fa fa-trash"></i></button>
                                        <div
                                            class="modal fade"
                                            id="modalLevel"
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
                                                    data-dismiss="modal">
                                                    Non
                                                </button>

                                                <button type="button" wire:click='delete({{$n->id}})'  class="btn btn-danger">Oui</button>
                                            </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('script')
    <script>
      window.addEventListener('addSuccessful', event =>{
        iziToast.success({
        title: 'Niveau d\'étude',
        message: 'Ajout avec succes',
        position: 'topRight'
        });
    });
    window.addEventListener('updateSuccessful', event =>{
        iziToast.success({
        title: 'Niveau d\'étude',
        message: 'Mis à jour avec succes',
        position: 'topRight'
        });
    });
    window.addEventListener('deleteLevel', event =>{
        iziToast.warning({
        title: 'Niveau d\'étude',
        message: 'Supprimé avec succes',
        position: 'topRight'
        });
    });
    </script>
@endsection