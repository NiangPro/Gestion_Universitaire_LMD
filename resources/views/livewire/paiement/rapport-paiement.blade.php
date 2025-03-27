<div class="container-fluid">
    <!-- En-tête avec les filtres -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fas fa-chart-line text-primary"></i> Rapport des Paiements
                    </h4>
                    
                    <div class="row g-3">
                        <!-- Sélection de la période -->
                        <div class="col-md-3">
                            <label class="form-group">Type de rapport</label>
                            <select wire:model.live="periode" class="form-control">
                                <option value="mensuel">Mensuel</option>
                                <option value="annuel">Annuel</option>
                                <option value="personnalise">Personnalisé</option>
                            </select>
                        </div>

                        @if($periode === 'mensuel')
                            <div class="col-md-3">
                                <label class="form-group">Mois</label>
                                <input type="month" wire:model.live="selectedMonth" class="form-control">
                            </div>
                        @elseif($periode === 'annuel')
                            <div class="col-md-3">
                                <label class="form-group">Année</label>
                                <select wire:model.live="selectedYear" class="form-control">
                                    @for($i = now()->year; $i >= 2020; $i--)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        @else
                            <div class="col-md-3">
                                <label class="form-group">Date début</label>
                                <input type="date" wire:model.live="dateDebut" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-group">Date fin</label>
                                <input type="date" wire:model.live="dateFin" class="form-control">
                            </div>
                        @endif

                        <!-- Recherche étudiant -->
                        <div class="col-md-3">
                            <label class="form-group">Étudiant (optionnel)</label>
                            <div class="position-relative">
                                <input type="text" 
                                       class="form-control" 
                                       wire:model.live="searchMatricule" 
                                       placeholder="Rechercher un étudiant..."
                                       autocomplete="off">
                                
                                @if(!empty($suggestions) && $suggestions->count() > 0)
                                    <div class="position-absolute w-100 mt-1 shadow-sm bg-white rounded border" style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                                        @foreach($suggestions as $etudiant)
                                            <div class="p-2 border-bottom hover-bg-light cursor-pointer" 
                                                 wire:click="selectEtudiant({{ $etudiant->id }})">
                                                <strong>{{ $etudiant->matricule }}</strong><br>
                                                <small>{{ $etudiant->nom }} {{ $etudiant->prenom }}</small>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($selectedEtudiant)
                        <div class="mt-3">
                            <div class="d-flex align-items-center">
                                <div class="border rounded p-2 bg-light">
                                    <strong>{{ $selectedEtudiant->nom }} {{ $selectedEtudiant->prenom }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $selectedEtudiant->matricule }}</small>
                                </div>
                                <button class="btn btn-sm btn-outline-danger ms-2" wire:click="resetEtudiant">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total des paiements</h6>
                    <h3 class="mb-0">{{ number_format($stats['total'], 0, ',', ' ') }} FCFA</h3>
                    <small>{{ $stats['count'] }} paiement(s)</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Moyenne par paiement</h6>
                    <h3 class="mb-0">{{ number_format($stats['moyenne'], 0, ',', ' ') }} FCFA</h3>
                    <small>Par transaction</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Répartition par type de paiement</h5>
                    <canvas id="typeChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Évolution des paiements</h5>
                    <canvas id="evolutionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Après vos graphiques existants, ajoutez -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Comparaison mensuelle des paiements</h5>
                    <div class="text-muted mb-3">
                        <small>
                            <i class="fas fa-square text-primary"></i> {{ $stats['annee_actuelle'] }} 
                            @if($stats['annee_precedente'])
                                <i class="fas fa-square text-secondary ms-3"></i> {{ $stats['annee_precedente'] }}
                            @endif
                        </small>
                    </div>
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des paiements -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-4">Liste détaillée des paiements</h5>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Référence</th>
                            <th>Étudiant</th>
                            <th>Type</th>
                            <th>Mode</th>
                            <th>Montant</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paiements as $paiement)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($paiement->date_paiement)) }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $paiement->reference }}
                                    </span>
                                </td>
                                <td>
                                    {{ $paiement->user->nom }} {{ $paiement->user->prenom }}
                                    <br>
                                    <small class="text-muted">{{ $paiement->user->matricule }}</small>
                                </td>
                                <td>
                                    @switch($paiement->type_paiement)
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
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($paiement->mode_paiement) }}
                                    </span>
                                </td>
                                <td>
                                    <strong>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</strong>
                                </td>
                                <td>
                                    @switch($paiement->status)
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
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <img src="{{ asset('images/empty.png') }}" alt="Aucun paiement" 
                                         style="width: 200px; opacity: 0.5;">
                                    <p class="text-muted mt-3">Aucun paiement trouvé pour cette période</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $paiements->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Fonction pour créer les graphiques
    function createCharts(stats) {
        // Graphique par type de paiement
        const typeCtx = document.getElementById('typeChart').getContext('2d');
        const typeChart = new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(stats.par_type),
                datasets: [{
                    data: Object.values(stats.par_type),
                    backgroundColor: ['#0d6efd', '#198754', '#ffc107']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Graphique d'évolution
        const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
        const evolutionChart = new Chart(evolutionCtx, {
            type: 'line',
            data: {
                labels: Object.keys(stats.par_jour),
                datasets: [{
                    label: 'Montant des paiements',
                    data: Object.values(stats.par_jour),
                    borderColor: '#0d6efd',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Histogramme mensuel comparatif
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(stats.comparaison_mensuelle),
                datasets: [
                    {
                        label: 'Année ' + stats.annee_actuelle,
                        data: Object.values(stats.comparaison_mensuelle).map(m => m.actuel),
                        backgroundColor: '#0d6efd',
                        borderColor: '#0d6efd',
                        borderWidth: 1
                    },
                    {
                        label: stats.annee_precedente ? 'Année ' + stats.annee_precedente : '',
                        data: Object.values(stats.comparaison_mensuelle).map(m => m.precedent),
                        backgroundColor: '#6c757d',
                        borderColor: '#6c757d',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' FCFA';
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        return { 
            typeChart, 
            evolutionChart,
            monthlyChart
        };
    }

    document.addEventListener('livewire:initialized', function () {
        // Récupération des données initiales
        const initialStats = JSON.parse('{!! json_encode($stats) !!}');
        const charts = createCharts(initialStats);

        // Mise à jour des graphiques lors des changements
        Livewire.on('refreshCharts', (stats) => {
            charts.typeChart.data.labels = Object.keys(stats.par_type);
            charts.typeChart.data.datasets[0].data = Object.values(stats.par_type);
            charts.typeChart.update();

            charts.evolutionChart.data.labels = Object.keys(stats.par_jour);
            charts.evolutionChart.data.datasets[0].data = Object.values(stats.par_jour);
            charts.evolutionChart.update();

            // Mise à jour de l'histogramme comparatif
            charts.monthlyChart.data.datasets[0].data = Object.values(stats.comparaison_mensuelle).map(m => m.actuel);
            charts.monthlyChart.data.datasets[1].data = Object.values(stats.comparaison_mensuelle).map(m => m.precedent);
            charts.monthlyChart.update();
        });
    });
</script>
@endpush

@push('styles')
<style>
    .hover-bg-light:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .badge {
        padding: 0.5em 1em;
    }

    .card {
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .table td {
        vertical-align: middle;
    }

    /* Ajoutez des styles pour l'histogramme */
    #monthlyChart {
        min-height: 300px;
    }
</style>
@endpush
