<div class="card" id="bulletin-print">
        <div class="card-body">
            <!-- En-tête du bulletin -->
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h4 class="mb-3">BULLETIN DE NOTES</h4>
                    <h5>Année Académique {{ date('Y', strtotime($academicYears->find($selectedYear)->debut)) }} - {{ date('Y', strtotime($academicYears->find($selectedYear)->fin)) }}</h5>
                </div>
            </div>

            <!-- Informations de l'étudiant -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Nom et Prénom:</strong> {{ $students->find($selectedStudent)->prenom }} {{ $students->find($selectedStudent)->nom }}</p>
                    <p><strong>Classe:</strong> {{ $classes->find($selectedClasse)->nom }}</p>
                </div>
                <div class="col-md-6 text-md-right">
                    <p><strong>Filière:</strong> {{ $classes->find($selectedClasse)->filiere->nom }}</p>
                </div>
            </div>

            <!-- Notes par semestre -->
            @foreach($bulletin as $semestre_id => $notes)
            <div class="semestre-section mb-4">
                <h5 class="bg-light p-2">Semestre {{ $semestre_id }}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Matière</th>
                                <th>Contrôle Continu</th>
                                <th>Examen</th>
                                <th>Moyenne</th>
                                <th>Observation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalCoef = 0;
                                $totalPoints = 0;
                            @endphp
                            @foreach($notes->groupBy('matiere_id') as $matiere_notes)
                                @php
                                    $cc = $matiere_notes->where('type_evaluation_id', 1)->first()?->note ?? 0;
                                    $exam = $matiere_notes->where('type_evaluation_id', 2)->first()?->note ?? 0;
                                    $moyenne = ($cc + $exam) / 2;
                                    $coef = $matiere_notes->first()->matiere->coefficient ?? 1;
                                    $totalCoef += $coef;
                                    $totalPoints += ($moyenne * $coef);
                                @endphp
                                <tr>
                                    <td>{{ $matiere_notes->first()->matiere->nom }}</td>
                                    <td class="text-center">{{ number_format($cc, 2) }}</td>
                                    <td class="text-center">{{ number_format($exam, 2) }}</td>
                                    <td class="text-center font-weight-bold">{{ number_format($moyenne, 2) }}</td>
                                    <td>{{ $matiere_notes->first()->observation }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <td colspan="3" class="text-right font-weight-bold">Moyenne Générale:</td>
                                <td class="text-center font-weight-bold">
                                    {{ number_format($totalCoef > 0 ? $totalPoints / $totalCoef : 0, 2) }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endforeach

            <!-- Bouton d'impression -->
            <div class="row mt-4">
                <div class="col-12 text-right">
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fas fa-print mr-2"></i>Imprimer
                    </button>
                </div>
            </div>
        </div>
    </div>