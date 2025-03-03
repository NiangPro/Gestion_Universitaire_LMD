<div class="table-responsive mt-5">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>@if($type == 'classe') Professeur @else Classe @endif</th>
                        <th>Jour</th>
                        <th>Salle</th>
                        <th>Horaire</th>
                        <th>Matiere</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courses as $c)
                    <tr>
                        @if($type == 'classe')
                        <td>{{ $c->professeur->prenom }} - {{ $c->professeur->nom }}</td>
                        @else
                        <td>{{ $c->classe->nom }} - {{ $c->classe->filiere->nom }}</td>
                        @endif
                        <td>{{ $c->semaine->jour }}</td>
                        <td>{{ $c->salle->nom }}</td>
                        <td>{{ $c->heure_debut }} - {{ $c->heure_fin }}</td>
                        <td>{{ $c->matiere->nom }}</td>
                        <td>
                            <a href="" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                            <a href="" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                            
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
</div>