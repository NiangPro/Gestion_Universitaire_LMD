<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\User;
use Livewire\WithFileUploads;

#[Title("Profil")]
class Profil extends Component
{
    use WithFileUploads;
    
    public $user, $profil, $current_password;
    public $prenom, $nom, $username, $adresse, $tel, $sexe, $email, $role, $image, $password, $campus_id, $password_confirmation;

    protected $rules = [
        'prenom' => 'required|string|max:255',
        'nom' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username',
        'adresse' => 'required|string|max:255',
        'tel' => ['required', 'unique:users,tel', 'regex:/^[33|70|75|76|77|78]+[0-9]{7}$/'],
        'sexe' => 'required|in:Homme,Femme',
        'email' => 'required|email|unique:users,email',
        'campus_id' => 'required',
        'password' => 'required|string|min:6|confirmed',
        'current_password' => 'required',
    ];

    public function messages(){
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
            'campus_id.required' => "Le campus est obligatoire.",
            'current_password.required' => "Le mot de passe est requis",
            'password.required' => "Le mot de passe est requis",
            'password.min' => "Minimum 6 caracteres",
            'password.confirmed' => "Les deux mots de passe sont differents",
        ];
    }

    public function editPassword(){
        $this->validate([
            'password' => 'required|string|min:6|confirmed',
            'current_password' => 'required',
        ]);

        if (Hash::check($this->current_password, Auth::user()->password) == 0) {
            $user = User::where('id', Auth::user()->id)->first();

            $user->password = Hash::make($this->password);

            $user->save();

            $this->dispatch('passwordEditSuccessful');
            $this->init();
        } else {
            $this->dispatch("passwordNotFound");
        }

    }

    public function editProfil()
    {
       
        if ($this->profil) {
            $this->validate([
                'profil' => 'image'
            ]);
            $imageName = 'user'.\md5($this->user->id).'.jpg';
            
            $this->profil->storeAs('public/images', $imageName);

            $user = User::where('id', $this->user->id)->first();

            $user->image = $imageName;
            $user->save();

            $this->profil = "";
            $this->initForm();

            $this->dispatch('profilEditSuccessful');
        }
    }


    public function store(){
        $u = User::where("id", Auth::user()->id)->first();

        if ($u) {
            $u->prenom = $this->prenom;
            $u->nom = $this->nom;
            $u->username = $this->username;
            $u->adresse = $this->adresse;
            $u->tel = $this->tel;
            $u->sexe = $this->sexe;
            $u->role = $this->role;
            $u->email = $this->email;
            $u->campus_id = $this->campus_id;

            $u->save();
            $this->dispatch("updateSuccessful");
            $this->initForm();
        }
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.profil', [
            "user" => User::where("id", Auth::user()->id)->first(),
        ]);
    }

    public function mount(){
        if(!Auth::user()){
            return redirect(route('login'));
        }

        $this->initForm();
    }

    public function initForm()
    {

        $this->user = Auth::user();
        $this->prenom = $this->user->prenom;
        $this->nom = $this->user->nom;
        $this->username = $this->user->username;
        $this->adresse = $this->user->adresse;
        $this->tel = $this->user->tel;
        $this->sexe = $this->user->sexe;
        $this->email =  $this->user->email;
        $this->role =  $this->user->role;
        $this->image =  $this->user->image;
        $this->campus_id = $this->user->campus_id;
    }

    public function init()
    {
        $this->current_password = "";
        $this->password = "";
        $this->password_confirmation = "";
    }
}
