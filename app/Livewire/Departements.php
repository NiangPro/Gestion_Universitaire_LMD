<?php

namespace App\Livewire;

use App\Models\Departement;
use App\Models\User;
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
    public $id, $nom, $description, $user_id;

    // Filtres et tri
    public $search = '';
    public $sortBy = 'nom';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Pour la liste des utilisateurs
    public $users = [];

    public $departementToDelete = null;
    public $departement = null;
    public $professeurs = [];
    protected $rules = [
        "nom" => "required|min:2",
        "description" => "required|min:10",
        "user_id" => "nullable|exists:users,id"
    ];

    protected $messages = [
        "nom.required" => "Le nom est obligatoire",
        "nom.min" => "Le nom doit contenir au moins 2 caractères",
        "description.required" => "La description est obligatoire",
        "description.min" => "La description doit contenir au moins 10 caractères",
        "user_id.exists" => "Le responsable sélectionné n'existe pas"
    ];

    public function mount()
    {
        if (!Auth::user()->hasPermission('departements', 'view')) {
            return redirect()->route('dashboard');
        }
        
        // Charger la liste des utilisateurs pour le select
        $this->loadUsers();
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
    if (!Auth::user()->hasPermission('departements', 'delete')) {
        $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de supprimer']);
        return;
    }

    try {
        $dept = Departement::findOrFail($this->departementToDelete);
        $dept->is_deleting = true;
        $dept->save();

        $this->outil->addHistorique("Suppression du département : " . $dept->nom, "delete");
        
        $this->dispatch('deleted');
        $this->departementToDelete = null;
    } catch (\Exception $e) {
        $this->dispatch('error', ['message' => 'Une erreur est survenue lors de la suppression']);
    }
}

public function edit($id)
{
    if (!Auth::user()->hasPermission('departements', 'edit')) {
        $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de modifier']);
        return;
    }

    $this->status = "add"; // On utilise le même formulaire que pour l'ajout
    $this->title = "Modifier le département";
    
    $dept = Departement::findOrFail($id);
    $this->id = $dept->id;
    $this->nom = $dept->nom;
    $this->description = $dept->description;
    $this->user_id = $dept->user_id;
}

    public function loadUsers()
    {
        // Récupérer uniquement les utilisateurs du même campus
        // en excluant les étudiants et les parents
        $this->users = User::where('campus_id', Auth::user()->campus_id)
                        ->whereNotIn('role', ['etudiant', 'parent']) // Exclure les étudiants et les parents
                        ->orderBy('prenom')
                        ->orderBy('nom')
                        ->get();
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
        $this->reset(['nom', 'description', 'id', 'user_id']);
    }

    public function getInfo($id)
    {
        if (!Auth::user()->hasPermission('departements', 'view')) {
            $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de voir les détails']);
            return;
        }

        $this->status = "info";
        $this->title = "Détails du département";
        
        $dept = Departement::with([
            'responsable',
            'filieres',
            'filieres.classes',
            'filieres.classes.cours' => function($query) {
                $query->where('is_deleting', false);
            },
            'filieres.classes.cours.professeur'
        ])->findOrFail($id);
        
        // Récupérer les professeurs uniques
        $professeurs = collect();
        foreach ($dept->filieres as $filiere) {
            foreach ($filiere->classes as $classe) {
                foreach ($classe->cours as $cours) {
                    if ($cours->professeur) {
                        $professeurs->push($cours->professeur);
                    }
                }
            }
        }
        
        $this->departement = $dept;
        $this->professeurs = $professeurs->unique('id')->values();
        $this->nom = $dept->nom;
        $this->description = $dept->description;
        $this->user_id = $dept->user_id;
        $this->id = $dept->id;
    }
    public function store()
    {
        $this->validate();

        try {
            if ($this->id) {
                if (!Auth::user()->hasPermission('departements', 'edit')) {
                    $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de modifier']);
                    return;
                }

                $dept = Departement::findOrFail($this->id);
                $dept->update([
                    "nom" => mb_strtoupper($this->nom),
                    "description" => $this->description,
                    "user_id" => $this->user_id
                ]);

                $this->outil->addHistorique("Modification du département : " . $dept->nom, "edit");
                $this->dispatch('updated');
            } else {
                if (!Auth::user()->hasPermission('departements', 'create')) {
                    $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de créer']);
                    return;
                }

                $dept = Departement::create([
                    "nom" => mb_strtoupper($this->nom),
                    "description" => $this->description,
                    "user_id" => $this->user_id,
                    "campus_id" => Auth::user()->campus_id
                ]);

                $this->outil->addHistorique("Création du département : " . $dept->nom, "add");
                $this->dispatch('added');
            }

            $this->changeStatus("list");
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Une erreur est survenue lors de l\'enregistrement']);
        }
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        $this->outil = new Outils();

        $query = Departement::where("is_deleting", false)
            ->where('campus_id', Auth::user()->campus_id)
            ->where(function($query) {
                $query->where('nom', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhereHas('responsable', function($q) {
                        $q->where('prenom', 'like', '%' . $this->search . '%')
                        ->orWhere('nom', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortBy, $this->sortDirection);
            
        return view('livewire.departement.departements', [
            "departements" => $query->paginate($this->perPage)
        ]);
    }
}