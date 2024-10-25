<div>
    <div class="card transparent-card">
        <div class="card-header d-block">
            <h4 class="card-title">Les élèments supprimés du site</h4>
        </div>
        <div class="card-body">
            <div id="accordion-ten" class="accordion accordion-header-shadow accordion-rounded">
               
                    @foreach($tables as $t)
                    {{-- @if($t->model != "Departement") --}}
                    <div class="accordion__item">
                        <div class="accordion__header collapsed accordion__header--primary" data-toggle="collapse" data-target="#header-shadow_collapse{{$t->id}}" aria-expanded="false">
                            <span class="accordion__header--icon"></span>
                            <span class="accordion__header--text">@if($t->nom == "academic_years") Année académique @else {{ucfirst($t->model)}} @endif @if(count($this->getDeletedItems($t->model)) > 0) <span class="badge text-white bg-danger">{{count($this->getDeletedItems($t->model))}}</span> @endif</span>
                            <span class="accordion__header--indicator"></span>
                        </div>
                        <div id="header-shadow_collapse{{$t->id}}" class="accordion__body collapse" data-parent="#accordion-ten" style="">
                            <div class="accordion__body--text">
                                @if(count($this->getDeletedItems($t->model)) > 0)
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Element</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            @foreach($this->getDeletedItems($t->model) as $e)
                                            <tr>
                                                <td>
                                                    @if($t->model == "AcademicYear")
                                                        Nom université :{{$e->campus->nom}}, Année académique:{{date("d/m/Y", strtotime($e->debut))}} - {{date("d/m/Y", strtotime($e->fin))}}
                                                    @elseif($t->model == "Campus")
                                                        {{$e->nom}} <i class="fa fa-arrows-h"></i> {{$e->email}}
                                                    @elseif($t->model == "Pack")
                                                        Nom: {{$e->nom}} & montant annuel: {{$e->annuel}} FCFA
                                                    @elseif($t->model == "Departement")
                                                        Nom université :{{$e->campus->nom}}, Nom département: {{$e->nom}}
                                                    @endif
                                                </td>
                                                <td>
                                                    <button wire:click='restaurer("{{$t->model}}", {{$e->id}})' class="btn btn-rounded btn-outline-success">Restaurer</button>
                                                    <button class="btn btn-rounded btn-outline-danger"  data-toggle="modal" data-target="#modalId{{$e->id}}">Supprimer définitivement</button>
                                                     <!-- Modal -->
                                                    <div
                                                        class="modal fade modalId"
                                                        id="modalId{{$e->id}}"
                                                        tabindex="-1"
                                                        role="dialog"
                                                        aria-labelledby="modalTitleId"
                                                        aria-hidden="true"
                                                    >
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalTitleId" >
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
                                                                Voulez-vous vraiment supprimer cet élément de façon permanente?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button
                                                                type="button"
                                                                class="btn btn-success"
                                                                data-dismiss="modal"
                                                                >
                                                                Non
                                                                </button>
                                                                <button type="button" wire:click='supprimer("{{$t->model}}",{{$e->id}})'  class="btn btn-danger">Oui</button>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @else 
                                    Aucun élément supprimé
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- @endif --}}
                    @endforeach
               
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('actif', event =>{
        iziToast.success({
        title: 'Suppression',
        message: 'activé avec succes',
        position: 'topRight'
        });
    });

    window.addEventListener('restore', event =>{
        iziToast.success({
        title: 'Restauration',
        message: 'avec succes',
        position: 'topRight'
        });
    });

    window.addEventListener('delete', event =>{
        $('.modalId').modal('hide');
        iziToast.warning({
        title: 'Elément',
        message: 'Supprimé avec succes',
        position: 'topRight'
        });
    });
</script>
