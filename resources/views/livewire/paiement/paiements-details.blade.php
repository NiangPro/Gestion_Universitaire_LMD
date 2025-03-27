<div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle"></i> Détails du Paiement
                    </h5>
                    <button type="button" class="close text-white" wire:click="$set('showDetailModal', false)">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if($selectedPaiement)
                    <div class="card border-0">
                        <div class="card-body p-0">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="bg-light">Référence</th>
                                    <td>{{ $selectedPaiement->reference }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Date</th>
                                    <td>{{ date('d/m/Y H:i', strtotime($selectedPaiement->date_paiement)) }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Étudiant</th>
                                    <td>
                                        {{ $selectedPaiement->user->nom }} {{ $selectedPaiement->user->prenom }}<br>
                                        <small class="text-muted">{{ $selectedPaiement->user->matricule }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Type</th>
                                    <td>
                                        @switch($selectedPaiement->type_paiement)
                                            @case('inscription')
                                                <span class="badge badge-info">Inscription</span>
                                                @break
                                            @case('mensualite')
                                                <span class="badge badge-primary">Mensualité</span>
                                                @break
                                            @case('complement')
                                                <span class="badge badge-warning">Complément</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Montant</th>
                                    <td>{{ number_format($selectedPaiement->montant, 0, ',', ' ') }} FCFA</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Mode</th>
                                    <td>
                                        <span class="badge badge-secondary">
                                            {{ ucfirst($selectedPaiement->mode_paiement) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Status</th>
                                    <td>
                                        @switch($selectedPaiement->status)
                                            @case('valide')
                                                <span class="badge badge-success">Validé</span>
                                                @break
                                            @case('en_attente')
                                                <span class="badge badge-warning">En attente</span>
                                                @break
                                            @case('rejete')
                                                <span class="badge badge-danger">Rejeté</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Année Académique</th>
                                    <td>{{ date('Y', strtotime($selectedPaiement->academicYear->debut)) }} - {{ date('Y', strtotime($selectedPaiement->academicYear->fin)) }}</td>
                                </tr>
                                @if($selectedPaiement->observation)
                                <tr>
                                    <th class="bg-light">Observation</th>
                                    <td>{{ $selectedPaiement->observation }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showDetailModal', false)">
                        Fermer
                    </button>
                    @if($selectedPaiement && $selectedPaiement->isEditable())
                        <button type="button" class="btn btn-primary" 
                                wire:click="startEdit({{ $selectedPaiement->id }})">
                            <i class="fas fa-edit"></i> Modifier
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>