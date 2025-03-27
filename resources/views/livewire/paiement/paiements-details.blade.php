<div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog  modal-dialog-scrollable">
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
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <th class="bg-light" style="width: 30%">Référence</th>
                                        <td>{{ $selectedPaiement->reference }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Date</th>
                                        <td>{{ $selectedPaiement->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Étudiant</th>
                                        <td>
                                            <strong>{{ $selectedPaiement->user->nom }} {{ $selectedPaiement->user->prenom }}</strong><br>
                                            <small class="text-muted">Matricule: {{ $selectedPaiement->user->matricule }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Type</th>
                                        <td>
                                            @switch($selectedPaiement->type_paiement)
                                                @case('inscription')
                                                    <span class="badge bg-info">Inscription</span>
                                                    @break
                                                @case('mensualite')
                                                    <span class="badge bg-primary">Mensualité</span>
                                                    @break
                                                @case('complement')
                                                    <span class="badge bg-warning">Complément</span>
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Montant</th>
                                        <td><strong>{{ number_format($selectedPaiement->montant, 0, ',', ' ') }} FCFA</strong></td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Mode</th>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ ucfirst($selectedPaiement->mode_paiement) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Statut</th>
                                        <td>
                                            @switch($selectedPaiement->status)
                                                @case('valide')
                                                    <span class="badge bg-success">Validé</span>
                                                    @break
                                                @case('en_attente')
                                                    <span class="badge bg-warning">En attente</span>
                                                    @break
                                                @case('rejete')
                                                    <span class="badge bg-danger">Rejeté</span>
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
                                </tbody>
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