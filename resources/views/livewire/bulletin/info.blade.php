<div class="container border p-4" style="background: white; box-shadow: 1px 1px 3px black;">
  <!-- Header -->
  <div class="row mb-3">
    <div class="col-6">
      <h4 class="heading">{{ auth()->user()->campus->nom }}</h4>
    </div>
    <div class="col-6 text-right">
      <h5 class="heading">Bulletin de Notes</h5>
      <p><strong>Année scolaire :</strong> {{ date('Y', strtotime($academicYear->debut)) }} - {{ date('Y', strtotime($academicYear->fin)) }}<br>
         <strong>Semestre :</strong> {{ $semestre->numero }}</p>
    </div>
  </div>

  <!-- Infos personnelles -->
  <div class="row mb-3">
    <div class="col-md-4">
      <p><strong>Matricule :</strong> {{ $etudiant->matricule }}<br>
         <strong>Nom et Prénom :</strong> {{ $etudiant->nom }} {{ $etudiant->prenom }} <br>
         <strong>Sexe :</strong> {{ $etudiant->sexe }}</p>
    </div>
    <div class="col-md-4">
      <p><strong>Date de naissance :</strong> {{ $etudiant->date_naissance ? date('d/m/Y', strtotime($etudiant->date_naissance)) : 'Non renseigné' }}<br>
         <strong>Lieu :</strong> {{ $etudiant->lieu_naissance ?? 'Non renseigné' }}</p>
    </div>
    <div class="col-md-4">
      <p><strong>Mention :</strong> {{ $classe->filiere->departement->nom }}<br>
         <strong>Spécialité :</strong> {{ $classe->filiere->nom }}<br>
         <strong>Grade :</strong> {{ $classe->niveau }}
    </div>
  </div>

  <h6 class="heading text-center mb-3">Domaine : Sciences et Technologies</h6>

  <!-- Tableau de notes -->
  <table class="table table-bordered table-sm">
    <thead class="thead-light">
      <tr>
        <th rowspan="2">UE</th>
        <th rowspan="2">Éléments constitutifs</th>
        <th colspan="3">Notes</th>
        <th>MCC</th>
        <th>CUE</th>
        <th>MUE</th>
        <th>MEC</th>
        <th>Crédits</th>
      </tr>
      <tr>
        <th>MCC1</th>
        <th>EXAM</th>
        <th>CEC</th>
        <th colspan="6"></th>
      </tr>
    </thead>
    <tbody>
      @foreach($ues as $ueId => $ue)
        @foreach($ue['matieres'] as $index => $matiere)
          <tr class="{{ $loop->first ? 'table-secondary' : '' }}">
            @if($loop->first)
              <td rowspan="{{ count($ue['matieres']) }}">{{ $ue['nom'] }}</td>
            @endif
            <td>{{ $matiere['nom'] }}</td>
            @php
              $notes = collect($matiere['notes'])->sortBy('typeEvaluation.ordre');
              $cc = $notes->firstWhere('typeEvaluation.code', 'CC');
              $exam = $notes->firstWhere('typeEvaluation.code', 'EXAM');
            @endphp
            <td>{{ $cc ? number_format($cc->note, 2) : '-' }}</td>
            <td>{{ $exam ? number_format($exam->note, 2) : '-' }}</td>
            <td>{{ $matiere['coefficient'] }}</td>
            <td>{{ number_format($matiere['moyenne'], 2) }}</td>
            <td>{{ $matiere['credit'] }}</td>
            <td>{{ number_format($ue['moyenne'], 2) }}</td>
            <td>{{ number_format($matiere['moyenne'], 2) }}</td>
            <td>{{ $matiere['credit'] }}</td>
          </tr>
        @endforeach
      @endforeach
    </tbody>
  </table>

  <!-- Résumé -->
  <div class="row mt-3">
    <div class="col-md-6">
      @php
        $totalCreditsObtenus = collect($ues)->sum(function($ue) {
          return collect($ue['matieres'])->sum(function($matiere) {
            return $matiere['moyenne'] >= 10 ? $matiere['credit'] : 0;
          });
        });
        $totalCredits = collect($ues)->sum(function($ue) {
          return collect($ue['matieres'])->sum('credit');
        });
        $moyenneGenerale = collect($ues)->sum(function($ue) {
          return $ue['moyenne'] * collect($ue['matieres'])->sum('credit');
        }) / $totalCredits;
      @endphp
      <p><strong>Total crédits obtenus :</strong> {{ $totalCreditsObtenus }} / {{ $totalCredits }}</p>
    </div>
    <div class="col-md-6 text-right">
      <p><strong>Moyenne Générale :</strong> {{ number_format($moyenneGenerale, 2) }}</p>
    </div>
  </div>

  <!-- Appréciation -->
  <div class="row">
    <div class="col-md-6">
      <p><strong>Appréciation du conseil de classe :</strong><br>
      @if($moyenneGenerale >= 16)
        Excellent travail. Félicitations !
      @elseif($moyenneGenerale >= 14)
        Très bon travail. Continuez ainsi !
      @elseif($moyenneGenerale >= 12)
        Bon travail. Semestre validé.
      @elseif($moyenneGenerale >= 10)
        Travail satisfaisant. Semestre validé.
      @else
        Des efforts supplémentaires sont nécessaires.
      @endif
      </p>
    </div>
    <div class="col-md-6">
      <p><strong>Décision du jury :</strong><br>
      @if($moyenneGenerale >= 10 && ($totalCreditsObtenus / $totalCredits) >= 0.8)
        Admis
      @else
        Ajourné
      @endif
      </p>
    </div>
  </div>

  <!-- Résumé UE -->
  <table class="table table-bordered table-sm mt-2">
    <thead class="thead-light">
      <tr>
        <th>UE</th>
        @foreach($ues as $ue)
          <th>{{ $ue['nom'] }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Moyenne</td>
        @foreach($ues as $ue)
          <td>{{ number_format($ue['moyenne'], 2) }}</td>
        @endforeach
      </tr>
      <tr>
        <td>Crédits</td>
        @foreach($ues as $ue)
          <td>{{ $ue['credit_total'] }}</td>
        @endforeach
      </tr>
    </tbody>
  </table>

  <!-- Légende -->
  <p><strong>Légende :</strong><br>
  <strong>MCC</strong> : Moyenne Contrôle Continu - EXAM : Examen - <strong>CEC</strong> : Crédit EC - <strong>MUE</strong> : Moyenne Unité d’Enseignement<br>
  <strong>CUE</strong> : Coefficient Unité d’Enseignement - <strong>MUE</strong> : Moyenne Unité d’Enseignement - <strong>MEC</strong> : Moyenne des EC - Crédit : Crédits acquis
  </p>

  <div id="pied" class="text-right m-2 pb-5" style="position: relative;">
    <!-- Signature -->
    <div class="signature">
        <h5 >Le Directeur des études</h5>
        <h5 class="mt-3" style="z-index: 10;">Ababacar SYLLA</h5>
        <img src="{{ asset('storage/images/signet.png') }}" width="100" height="50" alt="Cachet (Exemple)">
    </div>

    <div class="stamp" style="position: absolute;top:30px; right:-10px;z-index:0;">
        <img src="{{ asset('storage/images/tempon.png') }}" width="150" height="150" alt="Cachet (Exemple)">
    </div>
  </div>
 
</div>

@push("css")
    <style>
        .table th, .table td {
      vertical-align: middle;
      text-align: center;
    }
    .heading {
      text-transform: uppercase;
      font-weight: bold;
    }
    .signature {
      text-align: right;
      margin-top: 30px;
    }

    #pied{
        position: relative;
    }
    .stamp {
      position: absolute!important;
      top: 0px;
    }
    .table-sm td, .table-sm th {
      padding: .3rem;
    }
    </style>
@endpush