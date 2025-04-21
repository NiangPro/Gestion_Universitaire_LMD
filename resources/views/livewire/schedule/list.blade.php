<div class="table-responsive mt-3">
    <style>
        .schedule-table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .schedule-table th {
            background-color: #1a237e;
            color: white;
            font-weight: 600;
            text-align: center;
            padding: 15px;
            border: none;
        }
        .schedule-table td {
            padding: 10px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        .schedule-table tr:nth-child(even) td:first-child {
            background-color: #f5f5f5;
        }
        .course-cell {
            background-color: #3949ab !important;
            color: white;
            border-radius: 6px;
            padding: 8px !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }
        .course-cell:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .course-title {
            font-weight: 600;
            font-size: 0.95em;
            margin-bottom: 4px;
        }
        .course-info {
            font-size: 0.85em;
            opacity: 0.9;
        }
        .room-info {
            margin-top: 4px;
            font-size: 0.8em;
            background: rgba(255, 255, 255, 0.1);
            padding: 2px 6px;
            border-radius: 4px;
        }
    </style>
    <table class="table schedule-table"> 
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
                $heures = [];
                foreach ($courses as $cours) {
                    $debut = substr($cours->heure_debut, 0, 5);
                    $fin = substr($cours->heure_fin, 0, 5);
                    if (!in_array($debut, $heures)) {
                        $heures[] = $debut;
                    }
                    if (!in_array($fin, $heures)) {
                        $heures[] = $fin;
                    }
                }
                sort($heures);
                if (empty($heures)) {
                    $heures = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00'];
                }
                $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
            @endphp

            @foreach($heures as $index => $heure)
                <tr>
                    <td>{{ $heure }} - {{ $index < count($heures) - 1 ? $heures[$index + 1] : '20:00' }}</td>
                    @foreach($jours as $jour)
                    @php 
                        $findc = false;
                        $heure_suivante = $index < count($heures) - 1 ? $heures[$index + 1] : '20:00';
                    @endphp
                        @foreach($courses as $cours)
                            @if($cours->semaine->jour == $jour && $cours->heure_debut <= $heure && $cours->heure_fin > $heure)
                            @php 
                                $findc = true;
                            @endphp    
                            <td class="course-cell text-center">
                                    @if($type == 'classe')
                                        <div class="course-title">{{ $cours->matiere->nom }}</div>
                                        <div class="course-info">{{ $cours->professeur->prenom }} {{ $cours->professeur->nom }}</div>
                                    @else
                                        <div class="course-title">{{ $cours->matiere->nom }}</div>
                                        <div class="course-info">{{ $cours->classe->nom }}<br>({{ strtolower($cours->classe->filiere->nom) }})</div>
                                    @endif
                                    <div class="room-info">Salle: {{ $cours->salle->nom }}</div>
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