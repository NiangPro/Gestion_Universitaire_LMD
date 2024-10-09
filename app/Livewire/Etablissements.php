<?php

namespace App\Livewire;

use App\Models\Campus;
use App\Models\Outils;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Campus")]
class Etablissements extends Component
{
    public $status = "list";
    public $title = "Liste des établissements";
    public $camp;
    public $outils;

    #[Rule('required', message:'Le champ nom est obligatoire')]
    public $nom;
    #[Rule('required', message:'Le champ téléphone est obligatoire')]
    #[Rule('regex:/^[33|70|75|76|77|78]+[0-9]{7}$/', message:'Le numéro de téléphone doit être un numéro valide au Sénégal.')]
    public $telephone;
    #[Rule('required', message:'Le champ adresse est obligatoire')]
    public $adresse;
    #[Rule('required', message:'Le champ email est obligatoire')]
    public $email;
    public $id;
    public $image;
    public $etat;

    // 'email' => 'required|email|unique:campuses,email', // Validation de l'email unique
   
    public function getCampus($id){
        $c = Campus::where("id", $id)->first();
        $this->camp = $c;

        $this->nom = $c->nom;
        $this->image = $c->image;
        $this->telephone = $c->tel;
        $this->adresse = $c->adresse;
        $this->email = $c->email;
        $this->etat = $c->statut;

        $this->status = "info";
        $this->title = "Les informations de `$this->nom`";
    }

    public function change($status){
        $this->status = $status;

        $this->title = "Liste des établissements";
    }

    public function changeStatus($id, $etat){
        $val = $etat == "actif" ? 1 : 0;

        $camp = Campus::where("id", $id)->first();

        $camp->statut = $val;

        $camp->save();

        $this->dispatch($etat);
    }

    public function submit()
    {
        $this->validate();

         // Définir la date de fermeture à un mois à partir d'aujourd'hui
         $dateFermeture = Carbon::now()->addMonth();
        // Sauvegarde du campus dans la base de données
        Campus::create([
            'nom' => ucfirst($this->nom),
            'tel' => $this->telephone,
            'adresse' => ucfirst($this->adresse),
            'date_fermeture' => $dateFermeture,
            'image' => "campus.jpg",
            'email' => $this->email
        ]);

        // Réinitialiser les champs du formulaire après l'ajout
        $this->reset(["nom", "telephone", "adresse", "email"]);

        // Afficher un message de succès
        session()->flash('message', 'Campus ajouté avec succès!');
        // Émettre un événement pour fermer le modal
        $this->dispatch('campusAdded');
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.campus.etablissements', [
            "camps" => Campus::orderBy("id", "DESC")->get()
        ]);
    }

    public function mount(){
        $this->outils = new Outils();
        $this->outils->isLogged();
    }
}
