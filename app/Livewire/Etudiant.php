<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Inscription;
use App\Models\AcademicYear;
use App\Models\Medical;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;
use App\Services\MatriculeService;
use App\Livewire\Traits\WithCustomTabs;
use App\Models\Campus;

#[Title("Etudiants")]
class Etudiant extends Component
{
    // Propriétés existantes
    public $status = "";
    public $etat = "";
    public $classe = "";
    public $matricule = "";

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
    public $academicYears = [];

    // Propriétés pour la liste et le filtrage
    public $annee_academique = '';
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
    public $academic_years = [];
   

    // Méthodes existantes
    public function updatedClasse($value)
    {
        $this->classe = $value;
    }

    public function updatedPaiement($value)
    {
        $this->etat = $value;
    }

    public function updatedMatricule($value)
    {
        if ($value) {
            $this->etudiant = User::where('matricule', $value)
                ->where('role', 'etudiant')
                ->where('campus_id', auth()->user()->campus_id)
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

    // Nouvelles méthodes pour l'inscription
    public function mount()
    {
        $this->loadCampuses();
        $this->loadClasses();
        $this->loadAcademicYears();
        $this->loadEtudiants();
    }

    protected function loadCampuses()
    {
        // Si l'utilisateur est un admin, il voit tous les campus
        // Sinon, il ne voit que son campus
        if (auth()->user()->role === 'admin') {
            $this->campuses = Campus::all();
        } else {
            $this->campuses = collect([auth()->user()->campus]);
        }
    }

    protected function loadClasses()
    {
        $this->classes = auth()->user()->campus->classes;
    }

    protected function loadAcademicYears()
    {
        $this->academic_years = AcademicYear::orderBy('debut', 'desc')->get();
    }

    protected function loadTuteurs()
    {
        $this->tuteurs = User::where('role', 'tuteur')
            ->where('campus_id', auth()->user()->campus_id)
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
            $matricule = MatriculeService::generateMatricule(auth()->user()->campus_id);

            // Créer l'utilisateur (étudiant)
            $user = User::create([
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
                'role' => 'etudiant',
                'password' => Hash::make(str_replace(' ', '', strtolower($this->prenom)) . '@' . date('Y')),
                'campus_id' => auth()->user()->campus_id,
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
                    'password' => Hash::make('password'),
                    'campus_id' => auth()->user()->campus_id,
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

            // Créer l'inscription
            $inscription = Inscription::create([
                'user_id' => $user->id,
                'campus_id' => auth()->user()->campus_id,
                'academic_year_id' => auth()->user()->campus->currentAcademicYear()->id,
                'classe_id' => $this->classe_id,
                'tuteur_id' => $tuteur_id,
                'medical_id' => $medical_id,
                'relation' => $this->relation,
                'montant' => $this->montant,
                'restant' => $this->restant ?? 0,
                'tenue' => $this->tenue,
                'commentaire' => $this->commentaire,
                'status' => 'en_cours',
                'date_inscription' => now()
            ]);
            Log::info('Inscription créée', ['inscription_id' => $inscription->id]);

            DB::commit();
            Log::info('Transaction validée avec succès');

            // Réinitialiser le formulaire
            $this->reset();
            
            // Notification de succès
            $this->dispatch('saved');
            // Redirection vers la liste des étudiants
            return redirect()->route('etudiants');
            
            Log::info('Redirection vers la liste des étudiants');
            return redirect()->route('etudiants.index');

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

    public function updatedAnneeAcademique()
    {
        $this->loadEtudiants();
    }

    protected function loadEtudiants()
    {
        $query = User::where('role', 'etudiant')
            ->where('campus_id', auth()->user()->campus_id);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('matricule', 'like', '%' . $this->search . '%')
                    ->orWhere('nom', 'like', '%' . $this->search . '%')
                    ->orWhere('prenom', 'like', '%' . $this->search . '%');
            });
        }

        $this->etudiants = $query->get();
    }

    public function voir($id)
    {
        return redirect()->route('etudiant', $id);
    }

    // public function modifier($id)
    // {
    //     return redirect()->route('etudiant.edit', $id);
    // }

    public function confirmerSuppression($id)
    {
        $this->dispatch('confirmerSuppression', $id);
    }

    public function supprimer($id)
    {
        try {
            DB::beginTransaction();
            
            $inscription = Inscription::findOrFail($id);
            $user = $inscription->user;
            
            // Supprimer l'inscription
            $inscription->delete();
            
            // Supprimer l'utilisateur
            $user->delete();
            
            DB::commit();
            
            // Notification de succès
            session()->flash('success', 'Étudiant supprimé avec succès.');
            
            $this->loadEtudiants();
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression d\'un étudiant', [
                'error' => $e->getMessage(),
                'inscription_id' => $id
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
                'academic_year_id' => auth()->user()->campus->currentAcademicYear()->id,
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

            // Réinitialiser le formulaire
            $this->reset([
                'matricule', 'classe_id', 'etat', 'montant', 'restant', 'tenue', 'commentaire'
            ]);

            // Message de succès
            $this->dispatch('saved');

            // Redirection vers la liste des étudiants
            return redirect()->route('etudiants');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la réinscription:', [
                'error' => $e->getMessage(),
                'etudiant_id' => $this->etudiant->id
            ]);

            session()->flash('error', 'Une erreur est survenue lors de la réinscription: ' . $e->getMessage());
        }
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        $this->loadEtudiants();
        
        return view('livewire.etudiant.etudiant');
    }
}
