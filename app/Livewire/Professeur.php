<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use App\Models\Campus;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Professeurs")]
class Professeur extends Component
{
    public $status = "list";
    public $prenom, $nom, $username, $adresse, $tel, $sexe, $email, $password,$campus_id, $password_confirmation;
    public $id;

    protected $rules = [
        'prenom' => 'required|string|max:255',
        'nom' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username',
        'adresse' => 'required|string|max:255',
        'tel' => ['required', 'unique:users,tel', 'regex:/^[33|70|75|76|77|78]+[0-9]{7}$/'],
        'sexe' => 'required|in:Homme,Femme',
        'email' => 'required|email|unique:users,email',
        'campus_id' => 'required',
        'password' => 'nullable',
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
        ];
    }

    public function delete($id){
        $p = User::where("id", $id)->first();
        $p->delete();

        $this->dispatch("deleteCampus");
    }

    public function getProf($id){
        $p = User::where("id", $id)->first();
        $this->id = $p->id;

        $this->prenom = $p->prenom;
        $this->nom = $p->nom;
        $this->username = $p->username;
        $this->adresse = $p->adresse;
        $this->tel = $p->tel;
        $this->sexe = $p->sexe;
        $this->email = $p->email;
        $this->campus_id = $p->campus_id;

        $this->status="edit";
    }

    public function store()
    {
       
        if ($this->id) {
            $this->validate([
                'prenom' => 'required|string|max:255',
                'nom' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,'.$this->id,
                'adresse' => 'required|string|max:255',
                'tel' => 'required',
                'sexe' => 'required|in:Homme,Femme',
                'email' => 'required|email|unique:users,email,'.$this->id,
                'campus_id' => 'required',
                'password' => 'nullable',
            ]);
            
            $p = User::where("id", $this->id)->first();
            
            if ($p) {
                $p->prenom = $this->prenom;
                $p->nom = $this->nom;
                $p->username = $this->username;
                $p->adresse = $this->adresse;
                $p->tel = $this->tel;
                $p->sexe = $this->sexe;
                // $p->role = "professeur";
                $p->email = $this->email;
                $p->campus_id = $this->campus_id;

                $p->save();
                $this->dispatch("updateSuccessful");
                $this->init();
            }
        }else{
            $this->validate();
            User::create([
                'prenom' => $this->prenom,
                'nom' => $this->nom,
                'username' => $this->username,
                'adresse' => $this->adresse,
                'tel' => $this->tel,
                'sexe' => $this->sexe,
                "role" => "professeur",
                "image" => "profil.jpg",
                'email' => $this->email,
                'password' => null,
                'campus_id' => $this->campus_id, // Lier l'administrateur au campus
            ]);
            $this->dispatch("addSuccessful");
        }

        // Réinitialiser les champs du formulaire
        $this->status = "list";
    }

    public function changeStatus($status)
    {
        $this->status = $status;
        $this->init();
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.personnel.professeur.professeur', [
            "campus" => Campus::orderBy("id", "desc")->get(),
            "users" => User::where("campus_id", Auth::user()->campus_id)->where("role","professeur")->get(),
        ]);
    }

    public function init(){
        $this->id=null;
        $this->reset(['prenom', 'nom', 'username', 'adresse', 'tel', 'sexe', 'campus_id','email']);
    }
}
