<div class="container-fluid py-4">
    <!-- En-tête avec sélecteur de semestre et statistiques globales -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Sélectionner un semestre</h5>
                    <select wire:model.live="selectedSemestre" class="form-control">
                        @foreach($semestres as $semestre)
                            <option value="{{ $semestre->id }}">{{ $semestre->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-primary text-white">
                        <div class="card-body">
                            <h6 class="card-title">Moyenne Générale</h6>
                            <h2 class="mb-0">{{ number_format($moyenneGenerale, 2) }}/20</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">Crédits Validés</h6>
                            <h2 class="mb-0">{{ $creditsValides }}/{{ $creditsTotaux }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-info text-white">
                        <div class="card-body">
                            <h6 class="card-title">Année Académique</h6>
                            <h5 class="mb-0">{{ $currentAcademicYear->annee }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des notes par matière -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h4 class="card-title mb-4">Relevé de Notes Détaillé</h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Matière</th>
                            <th>Unité d'Enseignement</th>
                            <th class="text-center">Coefficient</th>
                            <th class="text-center">Crédits</th>
                            <th class="text-center">Type Évaluation</th>
                            <th class="text-center">Moyenne</th>
                            <th class="text-center">Validation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notes as $matiereId => $notesMatiere)
                            @php
                                $matiere = $notesMatiere->first()->matiere;
                                $moyenneMatiere = $this->calculerMoyenneMatiere($notesMatiere);
                            @endphp
                            <tr>
                                <td>{{ $matiere->nom }}</td>
                                <td>{{ $matiere->uniteEnseignement ? $matiere->uniteEnseignement->nom : 'Non assigné' }}</td>
                                <td class="text-center">{{ $matiere->coefficient }}</td>
                                <td class="text-center">{{ $matiere->credit }}</td>
                                <td class="text-center">
                                    @foreach($notesMatiere as $note)
                                        <div class="mb-2">
                                            <span class="badge {{ $note->note >= 10 ? 'badge-success' : 'badge-danger' }}">
                                                {{ $note->typeEvaluation ? $note->typeEvaluation->nom : 'Non défini' }} : {{ number_format($note->note, 2) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="text-center font-weight-bold">
                                    <span class="badge {{ $moyenneMatiere >= 10 ? 'badge-success' : 'badge-danger' }} badge-lg">
                                        {{ number_format($moyenneMatiere, 2) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($moyenneMatiere >= 10)
                                        <i class="fas fa-check-circle text-success fa-lg"></i>
                                    @else
                                        <i class="fas fa-times-circle text-danger fa-lg"></i>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Aucune note disponible pour ce semestre
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
