<?php

namespace App\Livewire;

use App\Models\Classe;
use App\Models\Departement;
use App\Models\Filiere;
use App\Models\Matiere;
use App\Models\Salle;
use App\Models\Semestre;
use App\Models\UniteEnseignement;
use App\Models\Outils;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Configurations")]
class Configurations extends Component
{
    public $matieres = [];
    public $classe = [
        "nom" => "",
        "idclasse" => null,
        "filiere_id" => null
    ];

    public $ue = [
        "nom" => "",
        "coef" => 0,
        "filiere_id" => null,
        "valeur" => "",
        "disciplines" => [],
        "idue" => null
    ];

    public $filiere = [
        "nom" => "",
        "idfiliere" => null,
        "departement_id" => null,
    ];

    public $departement = [
        "nom" => "",
        "iddepartement" => null
    ];

    public $salle = [
        "nom" => "",
        "idsalle" => null
    ];


    public $semestre = [
        "nom" => "",
        "ordre" => null,
        "idsemestre" => null
    ];

    protected $messages = [
        "classe.nom.required" => "Le nom est requis",
        "classe.filiere_id.required" => "Le filière est requis",
        "ue.filiere_id.required" => "Le filière est requis",
        "filiere.nom.required" => "Le nom est requis",
        "filiere.departement_id.required" => "Le département est requis",
        "departement.nom.required" => "Le nom est requis",
        "salle.nom.required" => "Le nom est requis",
        "ue.nom.required" => "Le nom est requis",
        "ue.credit.required" => "Le crédit est requis",
        "ue.disciplines.required" => "Les disciplines sont requises",
        "semestre.ordre.required" => "L'ordre est requis",
        "semestre.ordre.unique" => "L'ordre doit être unique",
        "semestre.ordre.min" => "L'ordre doit être compris entre 1 et 4",
        "semestre.ordre.max" => "L'ordre doit être compris entre 1 et 4",
    ];

    public function getSemestre($id){
        $semestre = Semestre::where("id", $id)->first();

        $this->semestre["idsemestre"] = $semestre->id;
        $this->semestre["nom"] = $semestre->nom;
        $this->semestre["ordre"] = $semestre->ordre;
    }

    public function toggleSemestre($id) {
        $semestre = Semestre::where("id", $id)->first();
        $outils = new Outils();
        
        if (!$semestre->is_active) {
            // Désactiver tous les autres semestres
            Semestre::where('campus_id', Auth::user()->campus_id)->update(['is_active' => false]);
            
            // Activer le semestre sélectionné
            $semestre->is_active = true;
            $semestre->save();
        
            $outils->addHistorique("Activation du semestre {$semestre->nom}", "activation");
        } else {
            $outils->addHistorique("Désactivation du semestre {$semestre->nom}", "desactivation");
        }
    
        $this->dispatch("updated");
    }

    public function storeSemestre(){
        if ($this->semestre["idsemestre"]) {
            $this->validate(["semestre.ordre" => "required|min:1|max:4|unique:semestres,ordre,{$this->semestre["idsemestre"]}"]);
            $semestre = Semestre::where("id", $this->semestre["idsemestre"])->first();

            $semestre->ordre = $this->semestre["ordre"];
            $semestre->nom = "Semestre ".$this->semestre["ordre"];

            $semestre->save();

            $this->dispatch("updated");
        }else{
            $this->validate(["semestre.ordre" => "required|min:1|max:4|unique:semestres,ordre"]);
            Semestre::create(["nom" => "Semestre ".$this->semestre["ordre"], "ordre" => $this->semestre["ordre"], "campus_id" => Auth::user()->campus_id]);
            $this->dispatch("added");
        }
        
        $this->reset(["semestre"]);
    }

    public function supprimerSemestre($id){
        $semestre = Semestre::where("id", $id)->first();

        $semestre->is_deleting = true;

        $semestre->save();
    }


    public function updatedUeValeur($value)
    {
        // Vérifie si une virgule est présente
        if (str_contains($value, ',')) {
            // Découpe les éléments et ajoute au tableau des tags
            $newTags = array_map('trim', explode(',', ucfirst($value)));
            $this->ue["disciplines"] = array_unique(array_merge($this->ue["disciplines"], array_filter($newTags))); // Ajoute sans doublons
            $this->ue['valeur'] = ''; // Réinitialise l'entrée
        }
    }

    public function removeTag($index)
    {
        unset($this->ue["disciplines"][$index]); // Supprime le tag sélectionné
        $this->ue["disciplines"] = array_values($this->ue["disciplines"]); // Réindexe l'array
    }

    public function initialiser($champ){
        $this->reset([$champ, "matieres"]);
    }

    public function supprimerUe($id){
        $ue = UniteEnseignement::where("id", $id)->first();

        $ue->is_deleting = true;

        $ue->save();

        $this->dispatch("deleted");
    }

    public function getUe($id){
        $ue = UniteEnseignement::where("id", $id)->first();

        $this->ue["idue"] = $ue->id;
        $this->ue["nom"] = $ue->nom;
        $this->ue["credit"] = $ue->credit;
        $this->ue["filiere_id"] = $ue->filiere_id;

        foreach ($ue->matieres as $m) {
            $this->matieres[$m->id]= ["nom" =>$m->nom, "delete" =>false];
        }
    }

    public function supprimerMatiere($id){
        $this->matieres[$id]["delete"] = true;
    }

    public function storeUe(){
        
        if ($this->ue["idue"]) {
            $this->validate(["ue.nom" => "required","ue.credit" => "required","ue.filiere_id" => "required"]);
            $ue = UniteEnseignement::where("id", $this->ue["idue"])->first();

            $ue->nom = strtoupper($this->ue["nom"]);
            $ue->credit = $this->ue["credit"];

            $ue->save();

            foreach ($this->ue["disciplines"] as $d) {
                Matiere::create(["nom"=> $d, "unite_enseignement_id" => $ue->id, "campus_id" => Auth::user()->campus_id]);
            }

            foreach ($this->matieres as $key => $m) {
                if ($m["delete"] == true) {
                    $mat = Matiere::where("id", $key)->first();
                    $mat->delete();
                }
            }

            $this->dispatch("updated");
        }else{
            $this->validate(["ue.nom" => "required","ue.credit" => "required","ue.filiere_id" => "required","ue.disciplines" => "required"]);

            $ue = UniteEnseignement::create(["filiere_id" => $this->ue["filiere_id"],"nom" => strtoupper($this->ue["nom"]), "credit" =>$this->ue["credit"], "campus_id" => Auth::user()->campus_id]);

            foreach ($this->ue["disciplines"] as $d) {
                Matiere::create(["nom"=> $d, "unite_enseignement_id" => $ue->id, "campus_id" => Auth::user()->campus_id]);
            }
            $this->dispatch("added");
        }
        
        $this->reset(["ue", "matieres"]);
    }



    public function supprimerDepartement($id){
        $departement = Departement::where("id", $id)->first();

        $departement->is_deleting = true;

        $departement->save();

        $this->dispatch("deleted");
    }

    public function getDepartement($id){
        $departement = Departement::where("id", $id)->first();

        $this->departement["iddepartement"] = $departement->id;
        $this->departement["nom"] = $departement->nom;
    }

    public function storeDepartement(){
        $this->validate(["departement.nom" => "required"]);

        if ($this->departement["iddepartement"]) {
            $departement = Departement::where("id", $this->departement["iddepartement"])->first();

            $departement->nom = strtoupper($this->departement["nom"]);

            $departement->save();

            $this->dispatch("updated");
        }else{
            Departement::create(["nom" => strtoupper($this->departement["nom"]), "campus_id" => Auth::user()->campus_id]);
            $this->dispatch("added");
        }
        
        $this->reset(["departement"]);
    }

    public function supprimerSalle($id){
        $salle = Salle::where("id", $id)->first();

        $salle->is_deleting = true;

        $salle->save();

        $this->dispatch("deleted");
    }

    public function getSalle($id){
        $salle = Salle::where("id", $id)->first();

        $this->salle["idsalle"] = $salle->id;
        $this->salle["nom"] = $salle->nom;
    }

    public function storeSalle(){
        $this->validate(["salle.nom" => "required"]);

        if ($this->salle["idsalle"]) {
            $salle = Salle::where("id", $this->salle["idsalle"])->first();

            $salle->nom = strtoupper($this->salle["nom"]);

            $salle->save();

            $this->dispatch("updated");
        }else{
            Salle::create(["nom" => strtoupper($this->salle["nom"]), "campus_id" => Auth::user()->campus_id]);
            $this->dispatch("added");
        }
        
        $this->reset(["salle"]);
    }



   

    public function supprimerFiliere($id){
        $filiere = Filiere::where("id", $id)->first();

        $filiere->is_deleting = true;

        $filiere->save();

        $this->dispatch("deleted");
    }

    public function getFiliere($id){
        $filiere = Filiere::where("id", $id)->first();

        $this->filiere["idfiliere"] = $filiere->id;
        $this->filiere["departement_id"] = $filiere->departement_id;
        $this->filiere["nom"] = $filiere->nom;
    }

    public function storeFiliere(){
        $this->validate(["filiere.nom" => "required","filiere.departement_id" => "required"]);

        if ($this->filiere["idfiliere"]) {
            $filiere = Filiere::where("id", $this->filiere["idfiliere"])->first();

            $filiere->nom = strtoupper($this->filiere["nom"]);
            $filiere->departement_id = $this->filiere["departement_id"];

            $filiere->save();

            $this->dispatch("updated");
        }else{
            Filiere::create(["nom" => strtoupper($this->filiere["nom"]), "departement_id" => $this->filiere["departement_id"], "campus_id" => Auth::user()->campus_id]);
            $this->dispatch("added");
        }
        
        $this->reset(["filiere"]);
    }

    public function supprimerClasse($id){
        $classe = Classe::where("id", $id)->first();

        $classe->is_deleting = true;

        $classe->save();

        $this->dispatch("deleted");
    }

    public function getClasse($id){
        $classe = Classe::where("id", $id)->first();

        $this->classe["idclasse"] = $classe->id;
        $this->classe["nom"] = $classe->nom;
        $this->classe["filiere_id"] = $classe->filiere_id;
    }

    public function storeClasse(){
        $this->validate(["classe.nom" => "required", "classe.filiere_id" => "required"]);

        if ($this->classe["idclasse"]) {
            $classe = Classe::where("id", $this->classe["idclasse"])->first();

            $classe->nom = strtoupper($this->classe["nom"]);
            $classe->filiere_id = $this->classe["filiere_id"];

            $classe->save();

            $this->dispatch("updated");
        }else{
            Classe::create(["nom" => strtoupper($this->classe["nom"]), "campus_id" => Auth::user()->campus_id, "filiere_id" => $this->classe["filiere_id"]]);
            $this->dispatch("added");
        }
        
        $this->reset(["classe"]);
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.configuration.configurations', [
            "classes" => Classe::where("is_deleting", false)->orderBy("nom", "ASC")->get(),
            "filieres" => Filiere::where("is_deleting", false)->orderBy("nom", "ASC")->get(),
            "departements" => Departement::where("is_deleting", false)->orderBy("nom", "ASC")->get(),
            "ues" => UniteEnseignement::where("is_deleting", false)->orderBy("nom", "ASC")->get(),
            "salles" => Salle::where("is_deleting", false)->orderBy("nom", "ASC")->get(),
            "semestres" => Semestre::where("is_deleting", false)->orderBy("nom", "ASC")->get(),
        ]);
    }
}
