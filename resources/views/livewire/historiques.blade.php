<div>
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Dernières activités des utilisateurs</h1>
             
        </div>
        <div class="card-body">
           
                <div class="table-responsive">
                    <table id="myTable" class="table text-center table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Navigateur</th>
                                <th>Adresse Ip</th>
                                @if(Auth()->user()->estSuperAdmin())
                                <th>établissement</th>
                                @endif
                                <th>Utilisateur</th>
                                <th>Telephone</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($histos as $a)
                                <tr id="dept-{{$a->id}}">
                                    <td>{{ $a->description}}</td>
                                    <td>{{ $a->navigateur }}</td>
                                    <td>{{ $a->ip }}</td>
                                    @if(Auth()->user()->estSuperAdmin())
                                    <td>{{ $a->campus->nom }}</td>
                                    @endif
                                    <td>{{ $a->user->prenom }} {{ $a->user->nom }}</td>
                                    <td>{{ ucfirst($a->user->tel) }}</td>
                                    <td>{{ date("d/m/Y à h:i:s", strtotime($a->created_at)) }}</td>
                                    <td>
                                        @if(strtolower($a->type) == "delete")
                                            <span class="badge bg-danger text-white">Suppression</span>
                                        @elseif(strtolower($a->type) == "edit")
                                            <span class="badge bg-warning text-white">Modification</span>
                                        @else 
                                            <span class="badge bg-success text-white">Ajout</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
</div>
