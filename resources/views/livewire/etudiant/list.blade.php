<form action="">
    <div class="row">
        <div class="col-md-6">
            <select name="" class="col-md-12 form-control mb-2">
                <option value="">Année accadémique </option>
                <option value="2024-2025">2024 - 2025</option>
            </select>
        </div>
        <div class="col-md-6">
            <select name="" wire:model.live="classe" class="col-md-12 form-control mb-2">
                <option value="">Classe</option>
                <option value="2024-2025">Cohorte 11</option>
            </select>
        </div>
    </div>
</form>
@if ($classe)
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Sexe</th>
                <th>Date de naissance</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Fall</td>
                <td>Alioune</td>
                <td>Homme</td>
                <td>20/01/2000</td>
                <td>
                    <a href="" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                    <a href="" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                    <a href="" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
        </tbody>
    </table>
@else
    <div class="alert alert-danger">
        Veuillez selectionner une classe
    </div>
@endif