<div>
    <div class="container">
        @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <!-- Abonnement actuel -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h4 class="mb-0 text-white">Abonnement Actuel</h4>
            </div>
            <div class="card-body">
                @if($currentSubscription)
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="card-title">Pack : {{ $currentSubscription->pack->nom }}</h5>
                        <p class="card-text">
                            <span class="badge {{ $currentSubscription->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                                {{ $currentSubscription->status === 'active' ? 'Actif' : 'Inactif' }}
                            </span>
                        </p>
                        <p class="card-text">
                            Date de début : {{ $currentSubscription->start_date->locale('fr')->isoFormat('LL') }}
                        </p>
                        <p class="card-text">
                            Date de fin : {{ $currentSubscription->end_date->locale('fr')->isoFormat('LL') }}
                        </p>

                        <!-- Barre de progression des utilisateurs -->
                        <div class="mt-4">
                            <h6>Utilisation du pack</h6>
                            <div class="d-flex justify-content-between mb-1">
                                <span>{{ $usersCount }} utilisateurs sur {{ $currentSubscription->pack->limite }}</span>
                                <span class="text-dark">
                                    {{ round(($usersCount / $currentSubscription->pack->limite) * 100) }}%
                                </span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar {{ $this->getUserProgressBarColor() }}"
                                    role="progressbar"
                                    style="width: {{ $this->getProgressWidth() }}%;"
                                    aria-valuenow="{{ $usersCount }}"
                                    aria-valuemin="0"
                                    aria-valuemax="{{ $currentSubscription->pack->limite }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-right">
                            <h3 class="text-primary">{{ (int)$remainingDays }} jours restants</h3>
                            <div class="btn-group mt-3">
                                <button type="button" wire:click="showRenewModal" class="btn btn-success mr-2">
                                    <i class="fas fa-sync-alt mr-2"></i>Renouveler
                                </button>
                                <button type="button" wire:click="toggleCompareMode" class="btn btn-primary mr-2">
                                    <i class="fas fa-exchange-alt mr-2"></i>Changer de formule
                                </button>
                                <button type="button" wire:click="showCancelModal" class="btn btn-danger">
                                    <i class="fas fa-times mr-2"></i>Résilier
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <p class="text-center">Aucun abonnement actif</p>
                @endif
            </div>
        </div>

        <!-- Liste des packs -->
        @if($compareMode || !$currentSubscription)
        <div class="mb-4">
            <h4 class="mb-4">{{ $currentSubscription ? 'Changer de Pack' : 'Choisir un Pack' }}</h4>
            <div class="row">
                @foreach($packs as $pack)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 {{ $currentSubscription && $currentSubscription->pack_id === $pack->id ? 'border-primary' : '' }}">
                        <div class="card-header text-center" style="background-color: {{ $pack->couleur }}20">
                            <h5 class="card-title mb-0" style="color: {{ $pack->couleur }}">{{ $pack->nom }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <h3 class="mb-0">{{ \App\Helpers\MoneyHelper::formatMontant($pack->mensuel) }}</h3>
                                <small class="text-muted">/mois</small>
                                <p class="text-muted mt-2">ou {{ \App\Helpers\MoneyHelper::formatMontant($pack->annuel) }}/an</p>
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success mr-2"></i>
                                    Limite de {{ $pack->limite }} utilisateurs
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer bg-transparent text-center border-0">
                            @if($currentSubscription && $currentSubscription->pack_id === $pack->id)
                            <button class="btn btn-outline-secondary" disabled>
                                <i class="fas fa-check mr-2"></i>Pack actuel
                            </button>
                            @else
                            <button wire:click="changePack({{ $pack->id }})"
                                class="btn btn-outline-primary">
                                <i class="fas fa-exchange-alt mr-2"></i>Changer pour ce pack
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Modal de renouvellement -->
        <div wire:ignore.self class="modal fade" id="renewModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Renouveler votre abonnement</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <div class="rounded-circle bg-success p-3 d-inline-block mb-3">
                                <i class="fas fa-sync-alt text-white"></i>
                            </div>
                            <h4>Renouvellement du pack {{ $currentSubscription?->pack->nom }}</h4>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-radio mb-3">
                                <input type="radio" id="monthly" name="renewalDuration"
                                    class="custom-control-input"
                                    wire:model.live="renewalDuration"
                                    value="monthly">
                                <label class="custom-control-label" for="monthly">
                                    Mensuel - {{ \App\Helpers\MoneyHelper::formatMontant($currentSubscription?->pack->mensuel ?? 0) }}
                                </label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="annual" name="renewalDuration"
                                    class="custom-control-input"
                                    wire:model.live="renewalDuration"
                                    value="annual">
                                <label class="custom-control-label" for="annual">
                                    Annuel - {{ \App\Helpers\MoneyHelper::formatMontant($currentSubscription?->pack->annuel ?? 0) }}
                                    <span class="text-success d-block small">
                                        Économisez {{ \App\Helpers\MoneyHelper::formatMontant(($currentSubscription?->pack->mensuel * 12 - $currentSubscription?->pack->annuel) ?? 0) }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-success" wire:click="renewSubscription">
                            <i class="fas fa-check mr-2"></i>Confirmer le renouvellement
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de résiliation -->
        <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title text-danger">Confirmation de résiliation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="mb-4">
                            <i class="fas fa-exclamation-triangle text-warning fa-3x"></i>
                        </div>
                        <h4>Êtes-vous sûr de vouloir résilier votre abonnement ?</h4>
                        <p class="text-muted">
                            Cette action mettra fin à votre abonnement actuel.
                            Vous n'aurez plus accès aux services après la résiliation.
                        </p>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-danger" wire:click="confirmCancellation">
                            <i class="fas fa-times mr-2"></i>Confirmer la résiliation
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de changement de pack -->
        <div class="modal fade" id="changePackModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Confirmer le changement</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if($selectedPack)
                        <div class="text-center mb-4">
                            <div class="rounded-circle bg-primary p-3 d-inline-block mb-3">
                                <i class="fas fa-exchange-alt text-white"></i>
                            </div>
                            <h4>Passage au pack "{{ $selectedPack->nom }}"</h4>
                        </div>
                        <div class="alert alert-info">
                            <h6 class="mb-2">Détails du nouveau pack :</h6>
                            <p class="mb-1">Prix mensuel : {{ \App\Helpers\MoneyHelper::formatMontant($selectedPack->mensuel) }}</p>
                            <p class="mb-0">Limite utilisateurs : {{ $selectedPack->limite }}</p>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-primary" wire:click="confirmPackChange">
                            <i class="fas fa-check mr-2"></i>Confirmer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.addEventListener('openRenewModal', event => {
                $('#renewModal').modal('show');
            });

            window.addEventListener('closeRenewModal', event => {
                $('#renewModal').modal('hide');
            });

            window.addEventListener('openCancelModal', event => {
                $('#cancelModal').modal('show');
            });

            window.addEventListener('closeCancelModal', event => {
                $('#cancelModal').modal('hide');
            });

            window.addEventListener('openChangePackModal', event => {
                $('#changePackModal').modal('show');
            });

            window.addEventListener('closeChangePackModal', event => {
                $('#changePackModal').modal('hide');
            });
        });
    </script>
</div>