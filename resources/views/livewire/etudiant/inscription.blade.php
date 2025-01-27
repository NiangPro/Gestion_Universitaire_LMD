<form action="">
    <div class="row">
        <div class="col-md-6">
            <div class="row p-3">
                <div class="col-md-12" style="border-top: 2px solid #343957; border-radius: 5px 5px 0px 0px;">
                    <h4><i class="fa fa-list pt-3"></i> Détails étudiant</h4>
                    <div class="form-group">
                        <label class="text-label">Année académique</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-calendar"></i> </span>
                            </div>
                            <input type="text" class="form-control" disabled value="{{ date('d/m/Y', strtotime(Auth::user()->campus->currentAcademicYear()->debut))}} - {{ date('d/m/Y', strtotime(Auth::user()->campus->currentAcademicYear()->fin))}}" id="val-username1" name="val-username" placeholder="Enter a username..">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row p-3">
                <div class="col-md-12" style="border-top: 2px solid #343957; border-radius: 5px 5px 0px 0px;">
                    <h4><i class="fa fa-list pt-3"></i> Détails de la formation</h4>
                </div>
            </div>
        </div>
    </div>
</form>