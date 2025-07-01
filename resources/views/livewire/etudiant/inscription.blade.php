<form wire:submit.prevent="save" class="needs-validation" novalidate>
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
                                <span class="input-group-text"> <i class="fa fa-venus-mars" style="font-size:15px;"></i> </span>
                            </div>
                            <select class="form-control @error('sexe') is-invalid @enderror" wire:model="sexe" id="sexe" name="sexe">
                                <option value="">Selectionné</option>
                                <option value="Homme">Homme</option>
                                <option value="Femme">Femme</option>
                            </select>
                            @error('sexe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Nom</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-user" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="text" id="nom" name="nom" placeholder="Nom" class="form-control @error('nom') is-invalid @enderror" wire:model="nom">
                            @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Prenom</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-user" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="text" id="prenom" name="prenom" placeholder="Prénom" class="form-control @error('prenom') is-invalid @enderror" wire:model="prenom">
                            @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Nom utilisateur</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-user" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="text" id="username" name="username" placeholder="Nom utilisateur" class="form-control @error('username') is-invalid @enderror" wire:model="username">
                            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Date de naissance</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-calendar" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="date" id="date_naissance" name="date_naissance" class="form-control @error('date_naissance') is-invalid @enderror" wire:model="date_naissance">
                            @error('date_naissance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Lieu de naissance</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-location" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="text" id="lieu_naissance" name="lieu_naissance" placeholder="Lieu de naissance" class="form-control @error('lieu_naissance') is-invalid @enderror" wire:model="lieu_naissance">
                            @error('lieu_naissance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Nationalité</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-location" style="font-size:15px;"></i> </span>
                            </div>
                            <select class="form-control @error('nationalite') is-invalid @enderror" wire:model="nationalite" id="nationalite" name="nationalite">
                                @foreach($pays as $pays)
                                    <option value="{{ $pays->nom_fr }}" {{ $pays->nom_fr == 'Sénégal' ? 'selected' : '' }}>{{ $pays->nom_fr }}</option>
                                @endforeach
                            </select>
                            @error('nationalite') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Etablissement précédant</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-location" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="text" id="etablissement_precedant" name="etablissement_precedant" placeholder="Etablissement précédant" class="form-control @error('etablissement_precedant') is-invalid @enderror" wire:model="etablissement_precedant">
                            @error('etablissement_precedant') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Téléphone</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-phone" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="tel" id="tel" name="tel" placeholder="Numéro de téléphone" class="form-control @error('tel') is-invalid @enderror" wire:model="tel">
                            @error('tel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Email</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-envelope" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="email" id="email" name="email" placeholder="Adresse email" class="form-control @error('email') is-invalid @enderror" wire:model="email">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Ville</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-house-chimney" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="text" id="ville" name="ville" placeholder="Ville" class="form-control @error('ville') is-invalid @enderror" wire:model="ville">
                            @error('ville') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Adresse</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-location" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="text" id="adresse" name="adresse" placeholder="Adresse" class="form-control @error('adresse') is-invalid @enderror" wire:model="adresse">
                            @error('adresse') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                            <select class="form-control @error('classe_id') is-invalid @enderror" id="classe_id" name="classe_id" wire:model.live="classe_id">
                                <option value="">Selectionné la classe</option>
                                @foreach ($classes as $classe)
                                    <option value="{{ $classe->id }}">{{ $classe->nom }} ({{ $classe->filiere->nom }}) </option>
                                @endforeach
                            </select>
                            @error('classe_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <hr>
                    <div style="border-top: 2px solid #343957; border-radius: 5px 5px 0px 0px;">
                        <h4><i class="fa fa-check pt-3"></i> Détails inscription</h4>
                    </div>
                    {{-- <div class="form-group">
                        <label class="text-label">Date d'inscription</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-calendar" style="font-size:15px;"></i> </span>
                            </div>
                            <input type="date" class="form-control" value="" name="val-username">
                        </div>
                    </div> --}}
                    <div class="form-group" wire:ignore>
                        <label class="text-label">Paiements</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-wallet" style="font-size:15px;"></i> </span>
                            </div>
                            <select class="form-control" id="etat" name="etat" wire:model.live="etat">
                                <option value="">Selectionné</option>
                                <option value="Payé">Payé</option>
                                <option value="Avance">Avance</option>
                            </select>
                        </div>
                    </div>
                    
                    @if($etat === "Payé")
                        <div class="form-group">
                            <label class="text-label">Montant</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa-solid fa-money-bill-wave" style="font-size:15px;"></i> </span>
                                </div>
                                <input type="number" id="montant" wire:model="montant" class="form-control" readonly>
                            </div>
                            <small class="text-muted">Montant fixé selon la classe sélectionnée</small>
                            @error('montant') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    @elseif($etat === "Avance")
                        <div class="row">
                            <div class="form-group col-6">
                                <label class="text-label">Montant</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa-solid fa-money-bill-wave" style="font-size:15px;"></i> </span>
                                    </div>
                                    <input type="number" id="montant" name="montant" wire:model.live="montant" class="form-control">
                                </div>
                                @error('montant') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-6">
                                <label class="text-label">Restant</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa-solid fa-money-bill-wave" style="font-size:15px;"></i> </span>
                                    </div>
                                    <input type="number" id="restant" name="restant" wire:model.live="restant" class="form-control" value="" readonly style="background:#f5f5f5;">
                                </div>
                                @error('restant') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="text-label">Mode de paiement</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa-solid fa-money-bill-wave" style="font-size:15px;"></i> </span>
                            </div>
                            <select class="form-control" id="mode_paiement" name="mode_paiement" wire:model.live="mode_paiement">
                                <option value="">Selectionner le mode de paiement</option>
                                <option value="Espèces">Espèces</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Virement">Virement</option>
                                <option value="Wave">Wave</option>
                                <option value="Orange Money">Orange Money</option>
                                <option value="Free Money">Free Money</option>
                            </select>
                            @error('mode_paiement') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-label">Tenue</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-shirt" style="font-size:15px;"></i> </span>
                            </div>
                            <select class="form-control" id="tenue" name="tenue" wire:model.live="tenue" name="val-username" >
                                <option value="">Selectionné</option>
                                <option value="Payé">Payé</option>
                                <option value="Avance">Avance</option>
                                <option value="Pas encore">Pas encore</option>
                            </select>
                            @error('tenue') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    @if($tenue === 'Payé' || $tenue === 'Avance')
                        <div class="form-group">
                            <label class="text-label">Montant tenue</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-shirt"></i></span>
                                </div>
                                <input type="number" min="0" step="0.01" id="montant_tenue" name="montant_tenue" class="form-control @error('montant_tenue') is-invalid @enderror" wire:model="montant_tenue" placeholder="Montant de la tenue">
                                @error('montant_tenue') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="text-label">Commentaire</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-info" style="font-size:15px;"></i> </span>
                            </div>
                            <textarea placeholder="detail" class="form-control" wire:model.live="commentaire" name="val-username" >
                               
                            </textarea>
                            @error('commentaire') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <hr>
                    <div class="mt-3" style="border-top: 2px solid #343957; border-radius: 5px 5px 0px 0px;">
                        <h4><i class="fa fa-truck-medical pt-3"></i> Tuteur</h4>
                    </div>

                    <div class="form-group">
                        <label class="text-label">Type de tuteur</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-user" style="font-size:15px;"></i> </span>
                            </div>
                            <select class="form-control" id="type_tuteur" name="type_tuteur" wire:model.live="type_tuteur">
                                <option value="Existant">Tuteur existant</option>
                                <option value="Nouveau">Nouveau tuteur</option>
                            </select>
                        </div>
                    </div>

                    @if($type_tuteur === 'Existant')
                        <div class="form-group">
                            <label class="text-label">Sélectionner un tuteur</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-user" style="font-size:15px;"></i> </span>
                                </div>
                                <select class="form-control" id="tuteur_id" name="tuteur_id" wire:model.live="tuteur_id">
                                    <option value="">Sélectionner un tuteur</option>
                                    @foreach($tuteurs as $tuteur)
                                        <option value="{{ $tuteur->id }}">{{ $tuteur->prenom }} {{ $tuteur->nom }}</option>
                                    @endforeach
                                </select>
                                @error('tuteur_id') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    @else
                        <div class="form-group">
                            <label class="text-label">Nom du tuteur</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-user" style="font-size:15px;"></i> </span>
                                </div>
                                <input type="text" id="nom_tuteur" name="nom_tuteur" placeholder="Nom" class="form-control" wire:model.live="nom_tuteur">
                                @error('nom_tuteur') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="text-label">Prénom du tuteur</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-user" style="font-size:15px;"></i> </span>
                                </div>
                                <input type="text" id="prenom_tuteur" name="prenom_tuteur" placeholder="Prénom" class="form-control" wire:model.live="prenom_tuteur">
                                @error('prenom_tuteur') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="text-label">Adresse du tuteur</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-location" style="font-size:15px;"></i> </span>
                                </div>
                                <input type="text" id="adresse_tuteur" name="adresse_tuteur" placeholder="Adresse" class="form-control" wire:model.live="adresse_tuteur">
                                @error('adresse_tuteur') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="text-label">Téléphone du tuteur</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-phone" style="font-size:15px;"></i> </span>
                                </div>
                                <input type="tel" id="tel_tuteur" name="tel_tuteur" placeholder="Numéro de téléphone du tuteur" class="form-control @error('tel_tuteur') is-invalid @enderror" wire:model="tel_tuteur">
                                @error('tel_tuteur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="text-label">Profession du tuteur</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-briefcase" style="font-size:15px;"></i> </span>
                                </div>
                                <input type="text" id="profession_tuteur" name="profession_tuteur" placeholder="Profession" class="form-control" wire:model.live="profession_tuteur">
                                @error('profession_tuteur') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="text-label">Relation avec l'étudiant</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-user" style="font-size:15px;"></i> </span>
                            </div>
                            <select class="form-control" id="relation" wire:model.live="relation">
                                <option value="">Sélectionner la relation</option>
                                <option value="Père">Père</option>
                                <option value="Mère">Mère</option>
                                <option value="Frère">Frère</option>
                                <option value="Sœur">Sœur</option>
                                <option value="Oncle">Oncle</option>
                                <option value="Tante">Tante</option>
                                <option value="Tuteur">Tuteur</option>
                            </select>
                            @error('relation') <div class="text-danger">{{ $message }}</div> @enderror
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
                            <select class="form-control @error('maladie') is-invalid @enderror" id="maladie" wire:model.live="maladie">
                                <option value="">Souffrez-vous d'une maladie ?</option>
                                <option value="Oui">Oui</option>
                                <option value="Non">Non</option>
                            </select>
                            @error('maladie') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    @if($maladie == 'Oui')
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa-solid fa-notes-medical"></i> </span>
                                </div>
                                <textarea class="form-control @error('description_maladie') is-invalid @enderror" id="description_maladie" name="description_maladie" wire:model="description_maladie" placeholder="Description de la maladie"></textarea>
                                @error('description_maladie') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    

                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa-solid fa-question" style="font-size:15px;"></i> </span>
                                </div>
                                <select class="form-control @error('traitement') is-invalid @enderror" id="traitement" name="traitement" wire:model.live="traitement">
                                    <option value="">Suivez-vous un traitement ?</option>
                                    <option value="Oui">Oui</option>
                                    <option value="Non">Non</option>
                                </select>
                                @error('traitement') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    @endif
                    @if($traitement == 'Oui')
                        <div class="form-group row">
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa-solid fa-user-doctor"></i> </span>
                                    </div>
                                    <input type="text" id="nom_medecin" name="nom_medecin" placeholder="Nom du médecin" class="form-control @error('nom_medecin') is-invalid @enderror" wire:model="nom_medecin">
                                    @error('nom_medecin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa-solid fa-phone"></i> </span>
                                    </div>
                                    <input type="tel" id="telephone_medecin" name="telephone_medecin" placeholder="Téléphone du médecin" class="form-control @error('telephone_medecin') is-invalid @enderror" wire:model="telephone_medecin">
                                    @error('telephone_medecin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="mt-3">
        <button type="button" class="btn btn-danger float-right ml-2" wire:click="cancel">
            <i class="fa fa-times"></i> Annuler
        </button>
        <button type="submit" class="btn btn-primary float-right" wire:loading.attr="disabled">
            <i class="fa fa-save"></i> 
            <span wire:loading.remove>Inscrire</span>
            <span wire:loading>Inscription en cours...</span>
        </button>
    </div>
</form>