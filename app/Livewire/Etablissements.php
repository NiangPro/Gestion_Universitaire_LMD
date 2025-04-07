<?php

namespace App\Livewire;

use App\Models\Activation;
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
    public $deleteItem;

    #[Rule('required', message: 'Le champ nom est obligatoire')]
    public $nom;
    #[Rule('required', message: 'Le champ téléphone est obligatoire')]
    #[Rule('regex:/^[33|70|75|76|77|78]+[0-9]{7}$/', message: 'Le numéro de téléphone doit être un numéro valide au Sénégal.')]
    public $telephone;
    #[Rule('required', message: 'Le champ adresse est obligatoire')]
    public $adresse;
    #[Rule('required', message: 'Le champ email est obligatoire')]
    public $email;
    public $id;
    public $image;
    public $etat;

    // 'email' => 'required|email|unique:campuses,email', // Validation de l'email unique

    public function getCampus($id)
    {
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

    public function change($status)
    {
        $this->status = $status;

        $this->title = "Liste des établissements";
    }

    public function changeStatus($id, $etat)
    {
        $val = $etat == "actif" ? 1 : 0;
        $camp = Campus::where("id", $id)->first();
        $camp->statut = $val;
        $camp->save();

        // Ajouter l'historique
        $this->outils->addHistorique(
            "Changement de statut du campus {$camp->nom} en " . ($val ? 'actif' : 'inactif'),
            "edit",
            "campuses",
            $camp->id
        );

        $this->dispatch($etat);
    }

    public function submit()
    {
        $this->validate();

        $dateFermeture = Carbon::now()->addMonth();
        
        $campus = Campus::create([
            'nom' => ucfirst($this->nom),
            'tel' => $this->telephone,
            'adresse' => ucfirst($this->adresse),
            'date_fermeture' => $dateFermeture,
            'image' => "campus.jpg",
            'email' => $this->email
        ]);

        // Ajouter l'historique
        $this->outils->addHistorique(
            "Création du nouveau campus {$campus->nom}",
            "add",
            "campuses",
            $campus->id
        );

        $this->reset(["nom", "telephone", "adresse", "email"]);
        $this->dispatch('campusAdded');
    }

    public function delete($id)
    {
        $c = Campus::where("id", $id)->first();
        $oldNom = $c->nom;
        $c->is_deleting = true;
        $c->save();

        // Ajouter l'historique
        $this->outils->addHistorique(
            "Suppression du campus {$oldNom}",
            "delete",
            "campuses",
            $c->id
        );

        $this->dispatch("deleteCampus");
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.campus.etablissements', [
            "camps" => Campus::where("is_deleting", false)->orderBy("id", "DESC")->get()
        ]);
    }

    public function mount()
    {
        $this->outils = new Outils();
        $this->outils->isLogged();

        $activation = Activation::where("nom", "campuses")->first();
        $this->deleteItem = $activation ? $activation->status : false; // Provide a default value if no record exists
    }
}
