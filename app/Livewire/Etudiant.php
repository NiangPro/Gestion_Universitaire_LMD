<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Inscription;
use App\Models\AcademicYear;
use App\Models\Medical;
use App\Models\Paiement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;
use App\Services\MatriculeService;
use App\Models\Campus;
use App\Models\Outils;
use Illuminate\Support\Facades\Auth;

#[Title("Etudiants")]
class Etudiant extends Component
{
    // Propriétés existantes
    public $status = "list";
    public $etat = "";
    public $classe = "";
    public $matricule = "";
    public $title = "";

    // Nouvelles propriétés pour l'inscription
    #[Rule('required|string|max:255')]
    public $nom = '';

    #[Rule('required|string|max:255')]
    public $prenom = '';

    #[Rule('required|string|max:255')]
    public $username = '';

    #[Rule('required|string|max:255')]
    public $sexe = '';

    #[Rule('required|date')]
    public $date_naissance = '';

    #[Rule('required|string|max:255')]
    public $lieu_naissance = '';

    #[Rule('required|string|max:255')]
    public $nationalite = '';

    #[Rule('required|string|max:255')]
    public $adresse = '';

    #[Rule('required|string|max:255')]
    public $ville = '';

    #[Rule('required|email|unique:users,email')]
    public $email = '';

    #[Rule('required|string|regex:/^[0-9]{9}$/')]
    public $tel = '';

    #[Rule('nullable|string|max:255')]
    public $etablissement_precedant = '';

    #[Rule('required|exists:classes,id')]
    public $classe_id = '';

    #[Rule('required|numeric|min:0')]
    public $montant;

    #[Rule('nullable|numeric|min:0')]
    public $restant;

    #[Rule('required|in:Payé,Avance,Pas encore')]
    public $tenue;

    #[Rule('nullable|numeric|min:0')]
    public $montant_tenue;

    #[Rule('required|in:Espèces,Cheque,Virement,Wave,Orange Money,Free Money')]
    public $mode_paiement;

    #[Rule('nullable|string')]
    public $commentaire;

    // Informations médicales
    #[Rule('required|in:Oui,Non')]
    public $maladie = 'Non';

    #[Rule('nullable|required_if:maladie,Oui|string')]
    public $description_maladie = '';

    #[Rule('required|in:Oui,Non')]
    public $traitement = 'Non';

    #[Rule('nullable|required_if:traitement,Oui|string')]
    public $nom_medecin = '';

    #[Rule('nullable|required_if:traitement,Oui|string')]
    public $telephone_medecin = '';

    // Propriétés pour le tuteur
    #[Rule('required|in:Existant,Nouveau')]
    public $type_tuteur = 'Existant';

    #[Rule('required_if:type_tuteur,Existant|exists:users,id')]
    public $tuteur_id;

    #[Rule('required_if:type_tuteur,Nouveau|string|max:255')]
    public $nom_tuteur = '';

    #[Rule('required_if:type_tuteur,Nouveau|string|max:255')]
    public $prenom_tuteur = '';

    #[Rule('required_if:type_tuteur,Nouveau|string|max:255')]
    public $adresse_tuteur = '';

    #[Rule('required_if:type_tuteur,Nouveau|string|regex:/^[0-9]{9}$/')]
    public $tel_tuteur = '';

    #[Rule('nullable|string|max:255')]
    public $profession_tuteur = '';

    #[Rule('required|string|max:255')]
    public $relation;

    // Variables pour les listes déroulantes
    public $classes = [];
    public $tuteurs = [];
    public $academic_years = [];

    // Propriétés pour la liste et le filtrage
    public $filter_annee_academique = '';
    public $filter_classe = '';
    public $search = '';
    public $etudiants;
    public $perPage = 10;

    // Propriété pour l'onglet actif
    public $activeTab = 'list';

    // Propriétés pour la réinscription
    public $etudiant;
    public $derniere_inscription;
    public $campus_id;
    public $academic_year_id;
    
    // Propriétés pour les listes déroulantes
    public $campuses = [];
    public $inscription_id = null;
    public $pays = [];
    
    public function changeStatus($status){
        $this->status = $status;

        if ($status == "add") {
            $this->title = "Formulaire d'ajout d'un étudiant";
            
            // Réinitialiser complètement le formulaire et ses validations
            $this->reset([
                'nom', 'prenom', 'username', 'sexe', 'date_naissance', 'lieu_naissance', 'nationalite', 'adresse', 'ville', 'email', 'tel',
                'etablissement_precedant', 'classe_id', 'montant', 'restant', 'tenue', 'montant_tenue', 'mode_paiement', 'commentaire',
                'maladie', 'description_maladie', 'traitement', 'nom_medecin', 'telephone_medecin',
                'type_tuteur', 'tuteur_id', 'nom_tuteur', 'prenom_tuteur', 'adresse_tuteur', 'tel_tuteur', 'profession_tuteur', 'relation',
                'inscription_id', 'matricule', 'etat'
            ]);
            $this->resetValidation();

            // Charger les dépendances pour le formulaire avec l'année en cours
            $currentAcademicYearId = Auth::user()->campus?->currentAcademicYear()?->id;
            if ($currentAcademicYearId) {
                $this->classes = \App\Models\Classe::where('academic_year_id', $currentAcademicYearId)->get();
            } else {
                $this->classes = [];
            }
            $this->loadTuteurs();

        }elseif($status == "edit"){
            $this->title = "Formulaire d'édition d'un étudiant";
        }else{
            $this->title = "Liste des étudiants";
        }
    }
    
    // Méthodes existantes
    public function updatedClasse($value)
    {
        $this->filter_classe = $value;
    }

    public function updatedFilterAnneeAcademique($value)
    {
        $this->filter_annee_academique = $value;
        if ($this->filter_annee_academique) {
            $this->classes = \App\Models\Classe::where('academic_year_id', $this->filter_annee_academique)->get();
        } else {
            $this->classes = [];
        }
        $this->filter_classe = ''; // Réinitialiser le filtre de classe
        $this->loadEtudiants();
    }

    public function updatedClasseId($value)
    {
        if ($value) {
            $classe = \App\Models\Classe::find($value);
            if ($classe) {
                $this->montant = $classe->cout_inscription;
            }
        }
    }

    public function updatedEtat($value)
    {
        if ($value === "Payé" && $this->classe_id) {
            $classe = \App\Models\Classe::find($this->classe_id);
            if ($classe) {
                $this->montant = $classe->cout_inscription;
                $this->restant = 0; 
            }
        } elseif ($value === "Avance") {
            $this->montant = null;
            $this->restant = null;
        }
    }

    public function updatedMatricule($value)
    {
        if ($value) {
            $this->etudiant = User::where('matricule', $value)
                ->where('role', 'etudiant')
                ->where('campus_id', Auth::user()->campus_id)
                ->first();

            if ($this->etudiant) {
                $this->derniere_inscription = Inscription::where('user_id', $this->etudiant->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                // Pré-remplir certains champs avec les données existantes
                $this->nom = $this->etudiant->nom;
                $this->prenom = $this->etudiant->prenom;
                $this->campus_id = $this->derniere_inscription->campus_id;
            }
        }
    }

    public function mount()
    {
        $this->outils = new Outils;
        $this->outils->initCountries();
        $this->loadCampuses();
        $this->loadAcademicYears();
        $this->loadPays();

        // Définir les filtres par défaut pour la liste
        if (Auth::user()->campus && Auth::user()->campus->currentAcademicYear()) {
            $this->filter_annee_academique = Auth::user()->campus->currentAcademicYear()->id;
        }

        // Charger les classes pour le filtre de la liste
        if ($this->filter_annee_academique) {
            $this->classes = \App\Models\Classe::where('academic_year_id', $this->filter_annee_academique)->get();
        }
        
        $this->loadEtudiants();
    }

    protected function loadPays()
    {
        $this->pays = \App\Models\Country::orderBy('nom_fr')->get();
        $this->nationalite = 'Sénégal'; // Sélectionner le Sénégal par défaut
    }

    protected function loadCampuses()
    {
        // Si l'utilisateur est un admin, il voit tous les campus
        // Sinon, il ne voit que son campus
        if (Auth::user()->role === 'admin') {
            $this->campuses = Campus::all();
        } else {
            $this->campuses = collect([Auth::user()->campus]);
        }
    }

    protected function loadClasses()
    {
        // Cette méthode est maintenant vide car la logique est gérée directement
        // dans mount, changeStatus, edit et updatedFilterAnneeAcademique
        // pour éviter les conflits.
    }

    protected function loadAcademicYears()
    {
        $this->academic_years = AcademicYear::orderBy('debut', 'desc')->get();
    }

    protected function loadTuteurs()
    {
        $this->tuteurs = User::where('role', 'tuteur')
            ->where('campus_id', Auth::user()->campus_id)
            ->get();
    }

    public function updatedTypeTuteur()
    {
        // Réinitialiser les champs selon le type de tuteur
        if ($this->type_tuteur === 'Existant') {
            $this->nom_tuteur = '';
            $this->prenom_tuteur = '';
            $this->adresse_tuteur = '';
            $this->tel_tuteur = '';
            $this->profession_tuteur = '';
        } else {
            $this->tuteur_id = null;
        }
    }

    public function save()
    {
        Log::info('Début de la méthode save');
        
        try {
            Log::info('Données soumises:', [
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'username' => $this->username,
                'email' => $this->email,
                'tel' => $this->tel,
                'sexe' => $this->sexe,
                'date_naissance' => $this->date_naissance,
                'lieu_naissance' => $this->lieu_naissance,
                'nationalite' => $this->nationalite,
                'adresse' => $this->adresse,
                'ville' => $this->ville,
                'etablissement_precedant' => $this->etablissement_precedant,
                'classe_id' => $this->classe_id,
                'montant' => $this->montant,
                'tenue' => $this->tenue,
                'type_tuteur' => $this->type_tuteur,
                'nom_tuteur' => $this->nom_tuteur,
                'prenom_tuteur' => $this->prenom_tuteur,
                'adresse_tuteur' => $this->adresse_tuteur,
                'tel_tuteur' => $this->tel_tuteur,
                'profession_tuteur' => $this->profession_tuteur,
                'relation' => $this->relation,
                'maladie' => $this->maladie,
                'description_maladie' => $this->description_maladie,
                'traitement' => $this->traitement,
                'nom_medecin' => $this->nom_medecin,
                'telephone_medecin' => $this->telephone_medecin
            ]);

            $rules = [
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'tel' => 'required|string',
                'sexe' => 'required|in:Homme,Femme',
                'date_naissance' => 'required|date',
                'lieu_naissance' => 'required|string|max:255',
                'nationalite' => 'required|string|max:255',
                'adresse' => 'required|string|max:255',
                'ville' => 'required|string|max:255',
                'etablissement_precedant' => 'nullable|string|max:255',
                'classe_id' => 'required|exists:classes,id',
                
                // Validation du paiement
                'montant' => 'required|numeric|min:0',
                'tenue' => 'required|in:Payé,Avance,Pas encore',
                'mode_paiement' => 'required_unless:tenue,Pas encore',
                'commentaire' => 'nullable|string',
                
                // Validation du tuteur
                'type_tuteur' => 'required|in:Existant,Nouveau',
                'tuteur_id' => 'required_if:type_tuteur,Existant|nullable|exists:users,id',
                'nom_tuteur' => 'required_if:type_tuteur,Nouveau|nullable|string|max:255',
                'prenom_tuteur' => 'required_if:type_tuteur,Nouveau|nullable|string|max:255',
                'adresse_tuteur' => 'required_if:type_tuteur,Nouveau|nullable|string|max:255',
                'tel_tuteur' => 'required_if:type_tuteur,Nouveau|nullable|string',
                'profession_tuteur' => 'nullable|string|max:255',
                'relation' => 'required|string|max:255',
                
                // Validation des informations médicales
                'maladie' => 'required|in:Oui,Non',
                'description_maladie' => 'nullable|required_if:maladie,Oui|string',
                'traitement' => 'required_if:maladie,Oui|nullable|in:Oui,Non',
                'nom_medecin' => 'nullable|required_if:traitement,Oui|string',
                'telephone_medecin' => 'nullable|required_if:traitement,Oui|string'
            ];

            Log::info('Début de la validation');
            try {
                $validatedData = $this->validate($rules);
                Log::info('Données validées avec succès');
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::error('Erreur de validation:', [
                    'errors' => $e->errors()
                ]);
                throw $e;
            }

            DB::beginTransaction();
            Log::info('Transaction démarrée');

            // le matricule de l'etudiant exemple ETU-2025-1-0001
            $matricule = MatriculeService::generateMatricule(Auth::user()->campus_id);

            // Créer l'utilisateur (étudiant)
            $user = User::create([
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'username' => $this->username,
                'email' => $this->email,
                'tel' => $this->tel,
                'image' => "profil.jpg",
                'sexe' => $this->sexe,
                'date_naissance' => $this->date_naissance,
                'lieu_naissance' => $this->lieu_naissance,
                'nationalite' => $this->nationalite,
                'adresse' => $this->adresse,
                'ville' => $this->ville,
                'etablissement_precedant' => $this->etablissement_precedant,
                'role' => 'etudiant',
                'password' => Hash::make(str_replace(' ', '', strtolower($this->prenom)) . '@' . date('Y')),
                'campus_id' => Auth::user()->campus_id,
                'matricule' => $matricule
            ]);
            Log::info('Utilisateur créé avec succès', ['user_id' => $user->id]);

            // Créer ou récupérer le tuteur
            if ($this->type_tuteur === 'Nouveau') {
                $tuteur = User::create([
                    'nom' => $this->nom_tuteur,
                    'prenom' => $this->prenom_tuteur,
                    'username' => strtolower($this->prenom_tuteur) . '.' . strtolower($this->nom_tuteur),
                    'email' => strtolower($this->prenom_tuteur) . '.' . strtolower($this->nom_tuteur) . '@example.com',
                    'tel' => $this->tel_tuteur,
                    'adresse' => $this->adresse_tuteur,
                    'profession' => $this->profession_tuteur,
                    'role' => 'tuteur',
                    'image' => 'profil.jpg',
                    'password' => Hash::make('password'),
                    'campus_id' => Auth::user()->campus_id,
                    'status' => 'active'
                ]);
                $tuteur_id = $tuteur->id;
                Log::info('Nouveau tuteur créé', ['tuteur_id' => $tuteur_id]);
            } else {
                $tuteur_id = $this->tuteur_id;
                Log::info('Tuteur existant utilisé', ['tuteur_id' => $tuteur_id]);
            }

            // Créer le dossier médical si nécessaire
            $medical_id = null;
            if ($this->maladie === 'Oui') {
                $medical = Medical::create([
                    'user_id' => $user->id,
                    'maladie' => $this->maladie,
                    'description' => $this->description_maladie,
                    'traitement' => $this->traitement,
                    'nom_medecin' => $this->nom_medecin,
                    'telephone_medecin' => $this->telephone_medecin
                ]);
                $medical_id = $medical->id;
                Log::info('Dossier médical créé', ['medical_id' => $medical_id]);
            }

            // Vérifiez si une inscription est en cours de modification
            if ($this->inscription_id) {
                $inscription = Inscription::findOrFail($this->inscription_id);
                $inscription->update([
                    'classe_id' => $this->classe_id,
                    'tuteur_id' => $tuteur_id,
                    'medical_id' => $medical_id,
                    'relation' => $this->relation,
                    'montant' => $this->montant,
                    'restant' => $this->restant ?? 0,
                    'tenue' => $this->tenue,
                    'montant_tenue' => $this->montant_tenue,
                    'commentaire' => $this->commentaire,
                    'status' => 'en_cours',
                    'date_inscription' => now()
                ]);
                Log::info('Inscription mise à jour', ['inscription_id' => $inscription->id]);

                // Mettre à jour le paiement associé à l'inscription
                $paiement = Paiement::where('user_id', $inscription->user_id)
                    ->where('type_paiement', 'inscription')
                    ->where('academic_year_id', $inscription->academic_year_id)
                    ->first();

                if ($paiement) {
                    $paiement->update([
                        'montant' => $this->montant,
                        'mode_paiement' => $this->mode_paiement,
                        'observation' => $this->commentaire,
                        'status' => 'validé',
                        'date_paiement' => now(),
                    ]);
                    Log::info('Paiement d\'inscription mis à jour', ['paiement_id' => $paiement->id]);
                }
            } else {
                // Créer une nouvelle inscription
                $inscription = Inscription::create([
                    'user_id' => $user->id,
                    'campus_id' => Auth::user()->campus_id,
                    'academic_year_id' => Auth::user()->campus->currentAcademicYear()->id,
                    'classe_id' => $this->classe_id,
                    'tuteur_id' => $tuteur_id,
                    'medical_id' => $medical_id,
                    'relation' => $this->relation,
                    'montant' => $this->montant,
                    'montant_tenue' => $this->montant_tenue,
                    'restant' => $this->restant ?? 0,
                    'tenue' => $this->tenue,
                    'commentaire' => $this->commentaire,
                    'status' => 'en_cours',
                    'date_inscription' => now()
                ]);
                Log::info('Inscription créée', ['inscription_id' => $inscription->id]);

                Paiement::create([
                    'user_id' => $user->id,
                    'montant' => $this->montant,
                    'type_paiement' => 'inscription',
                    'mode_paiement' => $this->mode_paiement,
                    'observation' => $this->commentaire,
                    'status' => 'validé',
                    'campus_id' => Auth::user()->campus_id,
                    'academic_year_id' => Auth::user()->campus->currentAcademicYear()->id,
                    'date_paiement' => now(),
                    'reference' => Paiement::genererReference(),
                ]);
                Log::info('Paiement d\'inscription créé pour l\'utilisateur', ['user_id' => $user->id]);
            
            }

            DB::commit();
            Log::info('Transaction validée avec succès');

            // Réinitialiser les filtres pour afficher le nouvel étudiant
            $this->filter_annee_academique = Auth::user()->campus->currentAcademicYear()->id;
            $this->filter_classe = $this->classe_id;
            $this->status = "list";

            $this->loadEtudiants();

            $this->dispatch('saved');

            $this->reset([
                'nom', 'prenom', 'username', 'sexe', 'date_naissance', 'lieu_naissance', 'nationalite', 'adresse', 'ville', 'email', 'tel',
                'etablissement_precedant', 'classe_id', 'montant', 'restant', 'tenue', 'montant_tenue', 'mode_paiement', 'commentaire',
                'maladie', 'description_maladie', 'traitement', 'nom_medecin', 'telephone_medecin',
                'type_tuteur', 'tuteur_id', 'nom_tuteur', 'prenom_tuteur', 'adresse_tuteur', 'tel_tuteur', 'profession_tuteur', 'relation',
                'inscription_id', 'matricule', 'etat'
            ]);
            
            // Recharger les années académiques
            $this->loadAcademicYears();
        
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'inscription', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Notification d'erreur
            session()->flash('error', 'Une erreur est survenue lors de l\'inscription: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        $this->reset();
    }

    public function updatedMaladie($value)
    {
        if (!$value) {
            $this->description_maladie = '';
            $this->traitement = false;
            $this->nom_medecin = '';
            $this->telephone_medecin = '';
        }
    }

    public function updatedTraitement($value)
    {
        if (!$value) {
            $this->nom_medecin = '';
            $this->telephone_medecin = '';
        }
    }

    protected function loadEtudiants()
    {
        if (!$this->filter_annee_academique || !$this->filter_classe) {
            $this->etudiants = collect();
            return;
        }

        $query = User::where('role', 'etudiant')
            ->where('campus_id', Auth::user()->campus_id)
            ->whereHas('inscriptions', function($q) {
                $q->where('academic_year_id', $this->filter_annee_academique)
                  ->where('classe_id', $this->filter_classe);
            });

        if ($this->search) {
            $query->where(function($q) {
                $q->where('matricule', 'like', '%' . $this->search . '%')
                    ->orWhere('nom', 'like', '%' . $this->search . '%')
                    ->orWhere('prenom', 'like', '%' . $this->search . '%');
            });
        }

        $this->etudiants = $query->get();
    }

    public function supprimer($inscriptionId)
    {
        try {
            DB::beginTransaction();
            
            // Trouver l'inscription par ID
            $inscription = Inscription::findOrFail($inscriptionId);
            
            // Récupérer l'utilisateur associé à l'inscription
            $user = $inscription->user;
            
            // Supprimer l'inscription
            $inscription->delete();
            
            // Supprimer l'utilisateur
            $user->delete();
            
            DB::commit();
            
            // Notification de succès
            $this->dispatch('deleted');

            // $this->loadEtudiants();
            return redirect()->route('etudiants');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression d\'un étudiant et de son inscription', [
                'error' => $e->getMessage(),
                'inscription_id' => $inscriptionId
            ]);
            
            // Notification d'erreur
            session()->flash('error', 'Une erreur est survenue lors de la suppression');
        }
    }

    protected function messages()
    {
        return [
            'required' => 'Le champ :attribute est obligatoire',
            'email' => 'L\'adresse email n\'est pas valide',
            'unique' => 'Cette :attribute est déjà utilisée',
            'exists' => 'La valeur sélectionnée n\'est pas valide',
            'date' => 'Le format de la date n\'est pas valide',
            'before' => 'La date doit être antérieure à aujourd\'hui',
            'regex' => 'Le format n\'est pas valide',
            'max' => 'Le champ :attribute ne doit pas dépasser :max caractères',
            'in' => 'La valeur sélectionnée n\'est pas valide',
            'telephone.regex' => 'Le numéro de téléphone doit contenir 9 chiffres',
            'telephone_medecin.regex' => 'Le numéro de téléphone du médecin doit contenir 9 chiffres'
        ];
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function reinscrire()
    {
        $this->validate([
            'classe_id' => 'required|exists:classes,id',
            'etat' => 'required|in:Payé,Avance',
            'montant' => 'required|numeric|min:0',
            'restant' => 'required_if:etat,Avance|nullable|numeric|min:0',
            'tenue' => 'required|in:1,0',
            'commentaire' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Créer la nouvelle inscription en utilisant les informations du formulaire
            $inscription = Inscription::create([
                'user_id' => $this->etudiant->id,
                'campus_id' => $this->campus_id,
                'academic_year_id' => Auth::user()->campus->currentAcademicYear()->id,
                'classe_id' => $this->classe_id,
                'tuteur_id' => $this->derniere_inscription->tuteur_id,
                'medical_id' => $this->derniere_inscription->medical_id,
                'relation' => $this->derniere_inscription->relation,
                'montant' => $this->montant,
                'restant' => $this->restant ?? 0,
                'tenue' => $this->tenue,
                'commentaire' => $this->commentaire,
                'status' => 'en_cours',
                'date_inscription' => now()
            ]);

            DB::commit();

            // Mettre à jour les filtres pour afficher la liste de la classe et de l'année de la réinscription
            $this->filter_annee_academique = Auth::user()->campus->currentAcademicYear()->id;
            $this->filter_classe = $this->classe_id;
            $this->status = "list";

            // Recharger la liste filtrée
            $this->loadEtudiants();

            // Message de succès
            $this->dispatch('saved');

            // Réinitialiser le formulaire (sauf les filtres)
            $this->reset([
                'matricule', 'classe_id', 'etat', 'montant', 'restant', 'tenue', 'commentaire'
            ]);

            // On ne fait plus de redirection ici
            // return redirect()->route('etudiants');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la réinscription:', [
                'error' => $e->getMessage(),
                'etudiant_id' => $this->etudiant->id
            ]);

            session()->flash('error', 'Une erreur est survenue lors de la réinscription: ' . $e->getMessage());
        }
    }
    
    public function edit($etudiantId)
    {
        $etudiant = User::with('inscriptions', 'medical')
            ->where('role', 'etudiant')
            ->findOrFail($etudiantId);

        $inscription = $etudiant->inscriptions()->latest()->first();

        if ($inscription) {
            // Charger les classes de l'année de l'inscription pour le formulaire
            $this->classes = \App\Models\Classe::where('academic_year_id', $inscription->academic_year_id)->get();
            $this->inscription_id = $inscription->id;
            $this->classe_id = $inscription->classe_id;
            $this->relation = $inscription->relation;
            $this->montant = $inscription->montant;
            $this->restant = $inscription->restant;
            $this->tenue = $inscription->tenue;
            $this->montant_tenue = $inscription->montant_tenue;
            $this->commentaire = $inscription->commentaire;

            // Déterminer l'état du paiement de l'inscription
            if ($this->montant > 0) {
                $this->etat = ($this->restant > 0) ? 'Avance' : 'Payé';
            } else {
                $this->etat = '';
            }

            // Charger le paiement associé à l'inscription pour trouver le mode de paiement
            $paiement = Paiement::where('user_id', $etudiant->id)
                ->where('type_paiement', 'inscription')
                ->where('academic_year_id', $inscription->academic_year_id)
                ->first();
            if ($paiement) {
                $this->mode_paiement = $paiement->mode_paiement;
            } else {
                $this->mode_paiement = '';
            }
        } else {
            // Valeurs par défaut si pas d'inscription
            $this->inscription_id = null;
            $this->classe_id = '';
            $this->relation = '';
            $this->montant = '';
            $this->restant = '';
            $this->tenue = '';
            $this->montant_tenue = '';
            $this->commentaire = '';
            $this->mode_paiement = '';
        }

        // Charger les propriétés de l'utilisateur
        $this->nom = $etudiant->nom;
        $this->prenom = $etudiant->prenom;
        $this->username = $etudiant->username;
        $this->email = $etudiant->email;
        $this->tel = $etudiant->tel;
        $this->sexe = $etudiant->sexe;
        $this->date_naissance = $etudiant->date_naissance;
        $this->lieu_naissance = $etudiant->lieu_naissance;
        $this->nationalite = $etudiant->nationalite;
        $this->adresse = $etudiant->adresse;
        $this->ville = $etudiant->ville;
        $this->etablissement_precedant = $etudiant->etablissement_precedant;

        // Charger les propriétés du tuteur
        $tuteur = User::find($inscription->tuteur_id ?? null);
        if ($tuteur) {
            $this->type_tuteur = 'Existant';
            $this->tuteur_id = $tuteur->id;
            $this->nom_tuteur = $tuteur->nom;
            $this->prenom_tuteur = $tuteur->prenom;
            $this->adresse_tuteur = $tuteur->adresse;
            $this->tel_tuteur = $tuteur->tel;
            $this->profession_tuteur = $tuteur->profession;
        } else {
            $this->type_tuteur = 'Nouveau';
            $this->tuteur_id = null;
            $this->nom_tuteur = '';
            $this->prenom_tuteur = '';
            $this->adresse_tuteur = '';
            $this->tel_tuteur = '';
            $this->profession_tuteur = '';
        }

        // Charger les informations médicales
        if ($etudiant->medical) {
            $this->maladie = $etudiant->medical->maladie;
            $this->description_maladie = $etudiant->medical->description;
            $this->traitement = $etudiant->medical->traitement;
            $this->nom_medecin = $etudiant->medical->nom_medecin;
            $this->telephone_medecin = $etudiant->medical->telephone_medecin;
        } else {
            $this->maladie = 'Non';
            $this->description_maladie = '';
            $this->traitement = 'Non';
            $this->nom_medecin = '';
            $this->telephone_medecin = '';
        }

        $this->status = "add";
        $this->title = "Formulaire d'édition d'un étudiant";
        $this->loadTuteurs();
    }

    public function updatedTenue($value)
    {
        if ($value === 'Pas encore') {
            $this->montant_tenue = 0;
        }
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        // Rafraîchir la liste des étudiants à chaque rendu pour les filtres
        if ($this->status === 'list') {
            $this->loadEtudiants();
        }
        
        return view('livewire.etudiant.etudiant');
    }
}
