<?php

namespace App\Livewire;

use App\Models\Departement;
use App\Models\Filiere;
use App\Models\Outils;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title("Filières")]
class Filieres extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Ajout du thème de pagination

    public $status = "list";
    public $title = "Liste des filières";
    public $outil;
    public $id, $nom, $departement_id;
    public $filiere = null;
    
    // Filtres
    public $search = '';
    public $selectedDepartement = '';
    public $perPage = 10;
    public $sortField = 'nom';
    public $sortDirection = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedDepartement' => ['except' => ''],
        'sortField' => ['except' => 'nom'],
        'sortDirection' => ['except' => 'asc']
    ];

    
    // Ajout des listeners pour les événements de mise à jour
    protected function getListeners()
    {
        return [
            'refresh' => '$refresh',
        ];
    }

    public function updatingSearch()
    {
        $this->gotoPage(1);
    }

    public function updatingSelectedDepartement()
    {
        $this->gotoPage(1);
    }

    public function updatingPerPage()
    {
        $this->gotoPage(1);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->gotoPage(1);
    }

    public function mount()
    {
        $this->outil = new Outils();
    }


    protected $rules =[
        "nom" => "required",
        "departement_id" => "required",
    ];

    protected $messages = [
        "nom.required" => "Le nom est obligatoire",
        "departement_id.required" => "Le département est obligatoire",
    ];

    public function changeStatus($status){
        $this->status = $status;

        if ($status == "add") {
            $this->title = "Formulaire d'ajout filière";
        }elseif($status == "edit"){
            $this->title = "Formulaire d'édition filière";
        }else{
            $this->title = "Liste des filière";
        }

        $this->reset(["nom", "departement_id", "id"]);
    }

    public function getInfo($id)
    {
        $this->changeStatus("info");
        
        $filiere = Filiere::with([
            'departement',
            'classes',
            'uniteEnseignements',
            'matieres'
        ])->findOrFail($id);

        $this->filiere = $filiere;
        $this->nom = $filiere->nom;
        $this->departement_id = $filiere->departement_id;
        $this->id = $filiere->id;
    }

    public function supprimer($id){
        $ac = Filiere::where("id", $id)->first();

        $ac->is_deleting = true;

        $ac->save();

        $this->outil->addHistorique("Suppression d'un filière", "edit");


        $this->dispatch("delete");
    }

    public function store(){
        $this->validate();

        if ($this->id) {
            $a = Filiere::where("id", $this->id)->first();

            $a->nom = $this->nom;
            $a->departement_id = $this->departement_id;

            $a->save();
            $this->outil->addHistorique("Mis à jour des données d'un filière", "edit");

            $this->dispatch("update");
        }else{
            Filiere::create([
                "nom" => $this->nom,
                "departement_id" => $this->departement_id,
                "campus_id" => Auth::user()->campus_id
            ]);
    
            $this->outil->addHistorique("Ajout d'un filière", "add");

            $this->dispatch("added");
        }


        $this->changeStatus("list");
    }

    #[Layout("components.layouts.app")]
    public function render()
{
    $this->outil = new Outils();
    
    $query = Filiere::query()
        ->where("campus_id", Auth::user()->campus_id)
        ->where("is_deleting", false);

    if ($this->search) {
        $query->where('nom', 'like', '%' . $this->search . '%');
    }

    if ($this->selectedDepartement) {
        $query->where('departement_id', $this->selectedDepartement);
    }

    $filieres = $query
        ->orderBy($this->sortField, $this->sortDirection)
        ->with(['departement', 'classes', 'uniteEnseignements', 'uniteEnseignements.matieres'])
        ->withCount(['classes', 'uniteEnseignements'])
        ->paginate($this->perPage);

    // Calculer le nombre de matières pour chaque filière
    $filieres->getCollection()->transform(function ($filiere) {
        $filiere->matieres_count = $filiere->uniteEnseignements->sum(function ($ue) {
            return $ue->matieres->count();
        });
        return $filiere;
    });

    return view('livewire.filiere.filieres', [
        "filieres" => $filieres,
        "depts" => Departement::where("campus_id", Auth::user()->campus_id)
            ->where("is_deleting", false)
            ->get(),
    ]);
}
}
