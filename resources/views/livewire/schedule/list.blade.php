<div class="table-responsive mt-3">
    <table class="table table-bordered"> 
        <thead>
            <tr>
                <th>Heure</th>
                <th>Lundi</th>
                <th>Mardi</th>
                <th>Mercredi</th>
                <th>Jeudi</th>
                <th>Vendredi</th>
                <th>Samedi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $heures = [
                    '08:00', '09:00', '10:00', '11:00', '12:00',
                    '13:00', '14:00', '15:00', '16:00', '17:00',
                    '18:00', '19:00'
                ];
                $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
            @endphp

            @foreach($heures as $index => $heure)
                <tr>
                    <td>{{ $heure }} - {{ $heures[$index + 1] ?? '20:00' }}</td>
                    @foreach($jours as $jour)
                    @php 
                        $findc = false;
                    @endphp
                        @foreach($courses as $cours)
                            @if($cours->semaine->jour == $jour && (($cours->heure_debut >= $heure && $cours->heure_debut <= $heures[$index + 1]) || ($cours->heure_debut <= $heure && $cours->heure_fin >= $heure)))
                            @php 
                                $findc = true;
                            @endphp    
                            <td class="bg-primary text-white text-center p-1">
                                    @if($type == 'classe')
                                        {{ $cours->matiere->nom }}<br>
                                        {{ $cours->professeur->prenom }} {{ $cours->professeur->nom }}<br>
                                    @else
                                        {{ $cours->matiere->nom }}<br>
                                        {{ $cours->classe->nom }} <br>({{ strtolower($cours->classe->filiere->nom) }})<br>
                                    @endif
                                    Salle: {{ $cours->salle->nom }}
                                </td>
                            @endif
                        @endforeach
                        @if(!$findc)
                            <td></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>