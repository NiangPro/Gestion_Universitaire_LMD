<?php

namespace App\Livewire;

use App\Models\Departement;
use App\Models\Outils;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title("Departements")]
class Departements extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $outil;
    public $status = "list";
    public $title = "Gestion des départements";

    // Form fields
    public $id, $nom, $description;

    // Filtres et tri
    public $search = '';
    public $sortBy = 'nom';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Delete confirmation
    public $departementToDelete = null;

    protected $rules = [
        "nom" => "required|min:2",
        "description" => "required|min:10",
    ];

    protected $messages = [
        "nom.required" => "Le nom est obligatoire",
        "nom.min" => "Le nom doit contenir au moins 2 caractères",
        "description.required" => "La description est obligatoire",
        "description.min" => "La description doit contenir au moins 10 caractères",
    ];

    public function mount()
    {
        if (!Auth::user()->hasPermission('departements', 'view')) {
            return redirect()->route('dashboard');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function changeStatus($status)
    {
        if ($status === "add" && !Auth::user()->hasPermission('departements', 'create')) {
            $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de créer un département']);
            return;
        }

        $this->status = $status;
        $this->title = $status === "add" ? "Nouveau département" : 
                      ($status === "edit" ? "Modifier le département" : "Gestion des départements");
        $this->reset(['nom', 'description', 'id']);
    }

    public function getInfo($id)
    {
        if (!Auth::user()->hasPermission('departements', 'view')) {
            $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de voir les détails']);
            return;
        }

        $this->status = "info";
        $this->title = "Détails du département";
        $dept = Departement::findOrFail($id);
        $this->nom = $dept->nom;
        $this->description = $dept->description;
        $this->id = $dept->id;
    }

    public function edit($id)
    {
        if (!Auth::user()->hasPermission('departements', 'edit')) {
            $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de modifier']);
            return;
        }

        $this->status = "edit";
        $this->title = "Modifier le département";
        $dept = Departement::findOrFail($id);
        $this->nom = $dept->nom;
        $this->description = $dept->description;
        $this->id = $dept->id;
    }

    public function confirmDelete($id)
    {
        if (!Auth::user()->hasPermission('departements', 'delete')) {
            $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de supprimer']);
            return;
        }

        $this->departementToDelete = $id;
        $this->dispatch('show-delete-modal');
    }

    public function delete()
    {
        $dept = Departement::findOrFail($this->departementToDelete);
        $dept->is_deleting = true;
        $dept->save();

        $this->outil->addHistorique("Suppression du département : " . $dept->nom, "delete");
        $this->dispatch('hide-delete-modal');
        $this->dispatch('deleted');
        $this->departementToDelete = null;
    }

    public function store()
    {
        $this->validate();

        if ($this->id) {
            if (!Auth::user()->hasPermission('departements', 'edit')) {
                $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de modifier']);
                return;
            }

            $dept = Departement::findOrFail($this->id);
            $dept->update([
                "nom" => $this->nom,
                "description" => $this->description,
            ]);

            $this->outil->addHistorique("Modification du département : " . $dept->nom, "edit");
            $this->dispatch('updated');
        } else {
            if (!Auth::user()->hasPermission('departements', 'create')) {
                $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de créer']);
                return;
            }

            $dept = Departement::create([
                "nom" => $this->nom,
                "description" => $this->description,
                "campus_id" => Auth::user()->campus_id
            ]);

            $this->outil->addHistorique("Création du département : " . $dept->nom, "add");
            $this->dispatch('added');
        }

        $this->changeStatus("list");
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        $this->outil = new Outils();

        $query = Departement::where("is_deleting", false)
            ->where(function($query) {
                $query->where('nom', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection);

        return view('livewire.departement.departements', [
            "departements" => $query->paginate($this->perPage)
        ]);
    }
}