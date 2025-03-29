<div class="table-responsive mt-5">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>@if($type == 'classe') Professeur @else Classe @endif</th>
                        <th>Jour</th>
                        <th>Salle</th>
                        <th>Horaire</th>
                        <th>Matiere</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courses as $c)
                    <tr>
                        @if($type == 'classe')
                        <td>{{ $c->professeur->prenom }} - {{ $c->professeur->nom }}</td>
                        @else
                        <td>{{ $c->classe->nom }} - {{ $c->classe->filiere->nom }}</td>
                        @endif
                        <td>{{ $c->semaine->jour }}</td>
                        <td>{{ $c->salle->nom }}</td>
                        <td>{{ $c->heure_debut }} - {{ $c->heure_fin }}</td>
                        <td>{{ $c->matiere->nom }}</td>
                        <td class="text-center">
                            @if(Auth::user()->hasPermission('cours', 'edit'))
                                <button class="btn btn-warning btn-sm rounded-pill" wire:click="edit({{ $c->id }})">
                                    <i class="fa fa-edit"></i>
                                </button>
                            @endif

                            @if(Auth::user()->hasPermission('cours', 'delete'))
                                <button type="button" class="btn btn-danger btn-sm rounded-pill" data-toggle="modal" data-target="#deleteModal{{ $c->id }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <div class="modal fade modalId" id="deleteModal{{ $c->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $c->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $c->id }}">Suppression</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Etes-vous sur de vouloir supprimer ce cours ?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
                                                <button type="button" class="btn btn-danger" wire:click="delete({{ $c->id }})">Oui</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
</div>