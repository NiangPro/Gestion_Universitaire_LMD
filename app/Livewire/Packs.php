<?php

namespace App\Livewire;

use App\Models\Activation;
use App\Models\Pack;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Packs")]
class Packs extends Component
{
    public $deleteItem;
    public $status = "list";
    public $nom;
    public $limite;
    public $mensuel;
    public $annuel;
    public $couleur;
    public $text;
    public $id;

    protected $rules = [
        'nom' => 'required|string|max:255',
        'limite' => 'required|integer|min:0',
        'mensuel' => 'required|numeric|min:0',
        'annuel' => 'required|numeric|min:0',
        'couleur' => 'required|string',
        'text' => 'required|string',
    ];

    // Messages personnalisés pour chaque règle de validation
    protected $messages = [
        'nom.required' => 'Le nom est obligatoire.',
        'nom.max' => 'Le nom ne doit pas dépasser 255 caractères.',
        'limite.required' => 'Le nombre total d\'élèves est obligatoire.',
        'limite.integer' => 'Le nombre total d\'élèves doit être un nombre entier.',
        'limite.min' => 'Le nombre total d\'élèves doit être au moins 0.',
        'mensuel.required' => 'Le montant mensuel est obligatoire.',
        'mensuel.numeric' => 'Le montant mensuel doit être un nombre.',
        'mensuel.min' => 'Le montant mensuel doit être au moins 0.',
        'annuel.required' => 'Le montant annuel est obligatoire.',
        'annuel.numeric' => 'Le montant annuel doit être un nombre.',
        'annuel.min' => 'Le montant annuel doit être au moins 0.',
        'couleur.required' => 'La couleur d\'arriere plan est obligatoire.',
        'text.required' => 'La couleur du text est obligatoire.',
    ];

    public function delete($id){
        $pack = Pack::where("id", $id)->first();
        $pack->is_deleting = true;

        $pack->save();

        $this->dispatch("deletePack");
    }

    public function info($id){
        $p = Pack::where("id", $id)->first();

        $this->id = $p->id;
        $this->nom = $p->nom;
        $this->annuel = $p->annuel;
        $this->mensuel = $p->mensuel;
        $this->couleur = $p->couleur;
        $this->text = $p->text;
        $this->limite = $p->limite;

        $this->status = 'edit';
    }

    public function store()
    {
        // Valider les données
        $this->validate();
        if ($this->id) {
            $p = Pack::where("id", $this->id)->first();
            
            if ($p) {
                $p->nom = $this->nom;
                $p->annuel = $this->annuel;
                $p->mensuel = $this->mensuel;
                $p->couleur = $this->couleur;
                $p->limite = $this->limite;
                $p->text = $this->text;

                $p->save();
                $this->dispatch("updateSuccessful");
            }
        }else{

            // Enregistrer les données dans la base de données
            Pack::create([
                'nom' => $this->nom,
                'limite' => $this->limite,
                'mensuel' => $this->mensuel,
                'annuel' => $this->annuel,
                'couleur' => $this->couleur,
                'text' => $this->text
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
        return view('livewire.pack.packs', [
            "pks" => Pack::where("is_deleting", false)->orderBy("annuel", "asc")->get()
        ]);
    }

    public function init(){
        $this->reset(['nom', 'limite', 'mensuel', 'annuel', 'couleur', 'text', 'id']);
    }

    public function mount(){
        // $this->deleteItem = Activation::where("nom", "packs")->first()->status;
    }
}
