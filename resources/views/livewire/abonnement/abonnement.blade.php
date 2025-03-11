<div>
    <div class="container py-5">
        <!-- Messages de notification -->
        @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Abonnement actuel -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Abonnement Actuel</h4>
            </div>
            <div class="card-body">
                @if($currentSubscription)
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="card-title">Pack : {{ $currentSubscription->pack->nom }}</h5>
                        <p class="card-text">
                            <span class="badge {{ $currentSubscription->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $currentSubscription->status }}
                            </span>
                        </p>
                        <p class="card-text">
                            Date de début : {{ $currentSubscription->start_date ? $currentSubscription->start_date->format('d/m/Y') : 'Non définie' }}
                        </p>
                        <p class="card-text">
                            Date de fin : {{ $currentSubscription->end_date ? $currentSubscription->end_date->format('d/m/Y') : 'Non définie' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="text-end">
                            <h3 class="text-primary">{{ $remainingDays }} jours restants</h3>
                            <button wire:click="cancelSubscription" class="btn btn-danger mt-3">
                                Résilier l'abonnement
                            </button>
                        </div>
                    </div>
                </div>
                @else
                <p class="text-center">Aucun abonnement actif</p>
                @endif
            </div>
        </div>

        <!-- Liste des packs disponibles -->
        <h4 class="mb-4">Changer de Pack</h4>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($packs as $pack)
            <div class="col">
                <div class="card h-100 shadow-sm" style="border-top: 4px solid {{ $pack->couleur }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $pack->nom }}</h5>
                        <p class="card-text">{{ $pack->text }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0">{{ number_format($pack->mensuel, 2) }} €/mois</h4>
                                <small class="text-muted">ou {{ number_format($pack->annuel, 2) }} €/an</small>
                            </div>
                            <button wire:click="changePack({{ $pack->id }})"
                                class="btn btn-outline-primary">
                                Choisir ce pack
                            </button>
                        </div>
                        <p class="mt-2 mb-0"><small>Limite : {{ $pack->limite }} utilisateurs</small></p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Modal de confirmation -->
        <div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmer le changement</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if($selectedPack)
                        <p>Êtes-vous sûr de vouloir passer au pack "{{ $selectedPack->nom }}" ?</p>
                        <p>Prix mensuel : {{ number_format($selectedPack->mensuel, 2) }} €</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button wire:click="confirmPackChange" class="btn btn-primary">Confirmer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));

            Livewire.on('show-confirmation-modal', () => {
                modal.show();
            });

            Livewire.on('hide-confirmation-modal', () => {
                modal.hide();
            });
        });
    </script>
</div>