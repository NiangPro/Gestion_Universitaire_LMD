<?php

namespace App\Livewire;

use App\Models\Campus;
use App\Models\User;
use Carbon\Carbon;
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

    protected $rules = [
        'prenom' => 'required|string|max:255',
        'nom' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username',
        'adresse' => 'required|string|max:255',
        'tel' => 'required',
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
            'pack_id' => $this->idpack,
            'date_fermeture' => Carbon::now()->addWeek(), // Définit la date de fermeture à une semaine à partir de maintenant
        ]);

        // Enregistrement de l'administrateur avec le campus_id
        User::create([
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
            'campus_id' => $campus->id, // Lier l'administrateur au campus
        ]);

        session()->flash('message', 'Les informations ont été enregistrées avec succès.');
        redirect(route("login"));
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.register');
    }

    function mount($id){
        $this->idpack = $id;
    }
}
