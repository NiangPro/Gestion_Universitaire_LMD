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
                                <span class="input-group-text"> <i class="fa fa-calendar" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="text" class="form-control" disabled value="{{ date('d/m/Y', strtotime(Auth::user()->campus->currentAcademicYear()->debut))}} - {{ date('d/m/Y', strtotime(Auth::user()->campus->currentAcademicYear()->fin))}}" id="val-username1" name="val-username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Sexe</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-user" style="font-size:15px;"></i> </span>
                            </div>
                            <select class="form-control" name="val-username" id="val-username2">
                                <option value="">Selectionné</option>
                                <option value="Homme">Homme</option>
                                <option value="Femme">Femme</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Nom</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-user" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="text" placeholder="Nom" class="form-control" value="" id="val-username1" name="val-username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Prenom</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-user" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="text" placeholder="Prénom" class="form-control" value="" id="val-username1" name="val-username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Date de naissance</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-calendar" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="date" class="form-control" value="" id="val-username1" name="val-username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Lieu de naissance</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-location" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="text" placeholder="Lieu de naissance" class="form-control" value="" id="val-username1" name="val-username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Nationalité</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-location" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="text" placeholder="Nationalité" class="form-control" value="" id="val-username1" name="val-username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Etablissement précédant</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-location" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="text" placeholder="Nationalité" class="form-control" value="" id="val-username1" name="val-username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Téléphone</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-phone" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="tel" placeholder="Numéro de téléphone" class="form-control" value="" id="val-username1" name="val-username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Email</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-envelope" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="email" placeholder="Adresse email" class="form-control" value="" id="val-username1" name="val-username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Ville</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-house-chimney" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="text" placeholder="Ville" class="form-control" value="" id="val-username1" name="val-username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Adresse</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-location" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="email" placeholder="Adresse" class="form-control" value="" id="val-username1" name="val-username">
                        </div>
                    </div>
                    <hr>
                    <div class="mt-3" style="border-top: 2px solid #343957; border-radius: 5px 5px 0px 0px;">
                        <h4><i class="fa-solid fa-stethoscope pt-3"></i> Renseignement médicaux</h4>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa-solid fa-question" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="text" placeholder="Soufrez-vouz d'une maladie" class="form-control" value="" id="val-username1" name="val-username">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa-solid fa-question" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="text" placeholder="Suivez-vous un traitement Oui/Non" class="form-control" value="" id="val-username1" name="val-username">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-6">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa-solid fa-user-doctor" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="text" placeholder="Nom du medecin" class="form-control" value="" id="val-username1" name="val-username">
                        </div>
                        <div class="input-group col-6">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa-solid fa-mobile-screen-button" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="text" placeholder="Téléphone du medecin" class="form-control" value="" id="val-username1" name="val-username">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row p-3">
                <div class="col-md-12" style="border-top: 2px solid #343957; border-radius: 5px 5px 0px 0px;">
                    <h4><i class="fa fa-list pt-3"></i> Détails de la formation</h4>
                    <div class="form-group">
                        <label class="text-label">Classe</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-house" style="font-size:15px;"></i> </span>
                            </div>
                            <select class="form-control" name="val-username" >
                                <option value="">Selectionné</option>
                                <option value="Homme">Homme</option>
                                <option value="Femme">Femme</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Filière</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-book-open" style="font-size:15px;"></i> </span>
                            </div>
                            <select class="form-control" name="val-username">
                                <option value="">Selectionné</option>
                                <option value="Homme">Filiere 1</option>
                                <option value="Femme">Filière 2</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Département</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-school" style="font-size:15px;"></i> </span>
                            </div>
                            <select class="form-control" name="val-username">
                                <option value="">Selectionné</option>
                                <option value="Homme">Dep 1</option>
                                <option value="Femme">Dep 2</option>
                            </select>
                        </div>
                    </div>
                    <div style="border-top: 2px solid #343957; border-radius: 5px 5px 0px 0px;">
                        <h4><i class="fa fa-check pt-3"></i> Détails inscription</h4>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Date d'inscription</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-calendar" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="date" class="form-control" value="" name="val-username">
                        </div>
                    </div>
                    <div class="form-group" wire:ignore>
                        <label class="text-label">Paiements</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-wallet" style="font-size:15px;"></i> </span>
                            </div>
                            <select class="form-control" wire:model.live="etat">
                                <option value="">Selectionné</option>
                                <option value="paye">Payé</option>
                                <option value="avance">Avance</option>
                            </select>
                        </div>
                    </div>
                    
                    @if($etat === "paye")
                        <div class="form-group">
                            <label class="text-label">Montant</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa-solid fa-money-bill-wave" style="font-size:15px;"></i> </span>
                                </div>
                                <input type="number" class="form-control" value="" id="" name="val-username">
                            </div>
                        </div>
                    @elseif($etat === "avance")
                        <div class="row">
                            <div class="form-group col-6">
                                <label class="text-label">Montant</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa-solid fa-money-bill-wave" style="font-size:15px;"></i> </span>
                                    </div>
                                    <input type="number" class="form-control" value="" id="" name="val-username">
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label class="text-label">Restant</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa-solid fa-money-bill-wave" style="font-size:15px;"></i> </span>
                                    </div>
                                    <input type="number" class="form-control" value="" id="" name="val-username">
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="text-label">Tenue</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-shirt" style="font-size:15px;"></i> </span>
                            </div>
                            <select class="form-control" name="val-username" >
                                <option value="">Selectionné</option>
                                <option value="Homme">Payé</option>
                                <option value="Femme">Avance</option>
                                <option value="Femme">Pas encore</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Commentaire</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-info" style="font-size:15px;"></i> </span>
                            </div>
                            <textarea placeholder="detail" class="form-control" name="val-username" >
                               
                            </textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="mt-3" style="border-top: 2px solid #343957; border-radius: 5px 5px 0px 0px;">
                        <h4><i class="fa fa-truck-medical pt-3"></i> En cas d'urgence</h4>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Nom complet</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-house-chimney" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="text" placeholder="Prénom & Nom" class="form-control" value="" id="val-username1" name="val-username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Téléphone</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-house-chimney" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="tel" placeholder="Téléphone" class="form-control" value="" id="val-username1" name="val-username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Relation</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-user" style="font-size:15px;"></i> </span>
                            </div>
                            <select class="form-control" name="val-username" id="val-username2">
                                <option value="">Selectionné</option>
                                <option value="Père">Père</option>
                                <option value="Mère">Mere</option>
                                <option value="Frere ou Soeur">Frère/Soeur</option>
                                <option value="Conjoint">Conjoint</option>
                                <option value="Tuteur">Tuteur</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <button type="button" class="btn btn-danger float-right ml-2">Annuler</button>
        <button type="submit" class="btn btn-primary float-right">Inscrire</button>
    </div>
</form>