<div>
    <div class="row">
        <div class="col-xl-4 col-xxl-6 col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="fa fa-list-alt"></i> Classes</h4>
                </div>
                <div class="card-body">
                    <ul class="m-3 p-3" style="max-height: 300px;overflow-y:scroll;">
                        <li class="row" style="cursor: pointer;">
                            <span class="col-md-8">Terminal</span>
                            <div  class="col-md-4 text-right item-actions">
                                <a href=""><i class="fa fa-edit text-primary" style="font-size: 20px"></i></a>
                                <a href=""><i class="fa fa-trash text-danger" style="font-size: 20px"></i></a>
                            </div>
                        </li>
                        
                    </ul>
                    <form action="" method="post" class="row m-2">
                        <input type="text" placeholder="Veuillez saisir une classe" class="form-control col-md-9">
                        <button type="submit" class="btn btn-success col-md-3">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-xxl-6 col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="fa fa-list-alt"></i> Filières</h4>
                </div>
                <div class="card-body">
                    <ul class="m-3 p-3" style="max-height: 300px;overflow-y:scroll;">
                        <li class="row" style="cursor: pointer;">
                            <span class="col-md-8">Terminal</span>
                            <div  class="col-md-4 text-right item-actions">
                                <a href=""><i class="fa fa-edit text-primary" style="font-size: 20px"></i></a>
                                <a href=""><i class="fa fa-trash text-danger" style="font-size: 20px"></i></a>
                            </div>
                        </li>
                    </ul>
                    <form action="" method="post" class="row m-2">
                        <input type="text" placeholder="Veuillez saisir une classe" class="form-control col-md-9">
                        <button type="submit" class="btn btn-success col-md-3"><i class="fa fa-plus"></i>Ajouter</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-xxl-6 col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="fa fa-list-alt"></i> Départements</h4>
                </div>
                <div class="card-body">
                    <ul class="m-3 p-3" style="max-height: 300px;overflow-y:scroll;">
                        <li class="row" style="cursor: pointer;">
                            <span class="col-md-8">Terminal</span>
                            <div  class="col-md-4 text-right item-actions">
                                <a href=""><i class="fa fa-edit text-primary" style="font-size: 20px"></i></a>
                                <a href=""><i class="fa fa-trash text-danger" style="font-size: 20px"></i></a>
                            </div>
                        </li>
                    </ul>
                    <form action="" method="post" class="row m-2">
                        <input type="text" placeholder="Veuillez saisir une classe" class="form-control col-md-9">
                        <button type="submit" class="btn btn-success col-md-3"><i class="fa fa-plus"></i>Ajouter</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-xxl-6 col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="fa fa-list-alt"></i> Unité d'enseignement</h4>
                </div>
                <div class="card-body">
                    <ul class="m-3 p-3" style="max-height: 300px;overflow-y:scroll;">
                        <li class="row" style="cursor: pointer;">
                            <span class="col-md-8">Terminal</span>
                            <div  class="col-md-4 text-right item-actions">
                                <a href=""><i class="fa fa-edit text-primary" style="font-size: 20px"></i></a>
                                <a href=""><i class="fa fa-trash text-danger" style="font-size: 20px"></i></a>
                            </div>
                        </li>
                    </ul>
                    <form action="" method="post" class="row m-2">
                        <input type="text" placeholder="Veuillez saisir une classe" class="form-control col-md-9">
                        <button type="submit" class="btn btn-success col-md-3"><i class="fa fa-plus"></i>Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('css')
    <style>
        ul li{
            line-height: 45px;
        }

        ul li:hover{
            background: #d8d8d8;
        }
        ul li:hover > span + .item-actions{
            visibility: visible;
        }
    </style>
@endsection
