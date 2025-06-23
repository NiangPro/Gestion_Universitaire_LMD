<?php

namespace App\Livewire;

use App\Models\Campus;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Outils;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Inscription")]
class Register extends Component
{
    public $idpack;
    public $prenom, $nom, $username, $adresse, $tel, $sexe, $email, $password, $password_confirmation;
    public $nomc, $emailc, $telc, $adressec;
    public $showPassword = false;

    protected $rules = [
        'prenom' => 'required|string|max:255',
        'nom' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username',
        'adresse' => 'required|string|max:255',
        'tel' => ['required', 'unique:users,tel', 'regex:/^[33|70|75|76|77|78]+[0-9]{7}$/'],
        'sexe' => 'required|in:Homme,Femme',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',

        'nomc' => 'required|string|max:255',
        'emailc' => 'required|email|unique:campuses,email',
        'telc' => 'required',
        'adressec' => 'required|string|max:255',
    ];

    public function messages()
    {
        return [
            'prenom.required' => 'Le prénom est obligatoire.',
            'nom.required' => 'Le nom est obligatoire.',
            'username.required' => "Le nom d'utilisateur est obligatoire.",
            'username.unique' => "Ce nom d'utilisateur existe déjà.",
            'tel.unique' => "Ce numéro de téléphone existe déjà.",
            'adresse.required' => "L'adresse est obligatoire.",
            'tel.required' => 'Le numéro de téléphone est obligatoire.',
            'tel.regex' => 'Le numéro de téléphone doit être un numéro valide au Sénégal.',
            'sexe.required' => 'Le sexe est obligatoire.',
            'email.required' => "L'adresse e-mail est obligatoire.",
            'email.unique' => "Cette adresse e-mail existe déjà.",
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',

            'nomc.required' => 'Le nom du campus est obligatoire.',
            'emailc.required' => "L'adresse e-mail du campus est obligatoire.",
            'emailc.unique' => 'Cette adresse e-mail du campus existe déjà.',
            'telc.required' => 'Le numéro de téléphone du campus est obligatoire.',
            'telc.regex' => 'Le numéro de téléphone du campus doit être un numéro valide au Sénégal.',
            'adressec.required' => "L'adresse du campus est obligatoire.",
        ];
    }


    public function submit()
    {
        $this->validate();

        // Enregistrement du campus
        $campus = Campus::create([
            'nom' => $this->nomc,
            'email' => $this->emailc,
            'image' => "campus.jpg",
            'tel' => $this->telc,
            'adresse' => $this->adressec,
        ]);

        $subscription = new Subscription([
            'campus_id' => $campus->id,
            'pack_id' => $this->idpack,
            'start_date' => now(),
            'end_date' => Carbon::now()->addWeek(),
            'status' => 'active',
            'payment_status' => 'paid',
            'amount_paid' => 0,
            'payment_method' => 'free'
        ]);

        $subscription->save();

        // Enregistrement de l'administrateur
        $user = User::create([
            'prenom' => $this->prenom,
            'nom' => $this->nom,
            'username' => $this->username,
            'adresse' => $this->adresse,
            'tel' => $this->tel,
            'sexe' => $this->sexe,
            "role" => "admin",
            "image" => "profil.jpg",
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'campus_id' => $campus->id,
        ]);

        // Définition des modules pour les permissions
        $modules = [
            'academic_years', 'absences', 'classes', 'comptabilite', 
            'cours', 'departements', 'etudiants', 'evaluations',
            'filieres', 'messages', 'notes', 'paiements', 
            'professeurs', 'rapports', 'retards', 'ue', 
            'personnel', 'surveillants', 'parents', 
            'configuration', 'historiques'
        ];

        // Attribution des permissions pour chaque module
        foreach ($modules as $module) {
            // Permissions pour l'utilisateur
            \App\Models\Permission::create([
                'user_id' => $user->id,
                'campus_id' => $campus->id,
                'module' => $module,
                'can_view' => true,
                'can_create' => true,
                'can_edit' => true,
                'can_delete' => false // Pas de permission de suppression
            ]);

            // Permissions pour le rôle admin
            \App\Models\Permission::create([
                'role' => 'admin',
                'campus_id' => $campus->id,
                'module' => $module,
                'can_view' => true,
                'can_create' => true,
                'can_edit' => true,
                'can_delete' => false // Pas de permission de suppression
            ]);
        }

        // Connexion automatique de l'utilisateur pour pouvoir utiliser Auth::user()
        Auth::login($user);

        // Utilisation de la fonction existante pour l'historique
        $outils = new Outils();
        $outils->addHistorique(
            "Création d'un nouveau campus : {$campus->nom} avec son administrateur : {$user->prenom} {$user->nom}",
            'info'
        );

        Auth::logout(); // Déconnexion après l'enregistrement de l'historique

        session()->flash('message', 'Les informations ont été enregistrées avec succès.');
        return redirect()->route("login");
    }

    #[Layout("components.layouts.home")]
    public function render()
    {
        return view('livewire.register');
    }

    function mount($id)
    {
        $this->idpack = $id;

        if (Auth::user()) {
            redirect(route("dashboard"));
        }
    }

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }
}
