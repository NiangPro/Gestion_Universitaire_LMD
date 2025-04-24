<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\User;
use App\Models\Outils;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

#[Title("Gestion des Surveillants")]
class Surveillant extends Component
{
    use WithPagination, WithFileUploads;
    protected $paginationTheme = 'bootstrap';

    public $status = "list";
    public $title = "Liste des surveillants";
    public $outil;
    public $selectedSurveillant;
    
    // Propriétés du formulaire
    public $id, $prenom, $nom, $username, $adresse, $tel, $sexe, $email;
    public $photo;
    public $specialite;

    // Filtres
    public $search = '';
    public $specialiteFilter = '';
    public $disponibiliteFilter = '';
    public $perPage = 10;
    public $sortField = 'nom';
    public $sortDirection = 'asc';

    public $showDeleteModal = false;
    public $surveillantToDelete = null;

    public $notesData = [];

    protected $rules = [
        'prenom' => 'required|string|max:255',
        'nom' => 'required|string|max:255',
        'username' => 'required|string|max:255',
        'adresse' => 'required|string|max:255',
        'tel' => ['required', 'regex:/^[33|70|75|76|77|78]+[0-9]{7}$/'],
        'sexe' => 'required|in:Homme,Femme',
        'email' => 'required|email',
        'specialite' => 'required|string',
        'photo' => 'nullable|image|max:1024'
    ];

    public function mount()
    {
        $this->outil = new Outils();
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:1024'
        ]);
    }

    public function changeStatus($status, $id = null)
    {
        if (!Auth::user()->hasPermission('surveillants', $status === 'add' ? 'create' : 'view')) {
            $this->dispatch('error', ['message' => 'Permission non accordée']);
            return;
        }

        $this->status = $status;
        
        if ($status === 'details' && $id) {
            $this->loadSurveillantDetails($id);
            $this->title = "Détails du surveillant";
        } else {
            $this->title = $status === "add" ? "Ajouter un surveillant" : "Liste des surveillants";
            $this->resetForm();
        }
    }

    public function loadSurveillantDetails($id)
    {
        $currentAcademicYearId = Auth::user()->campus->currentAcademicYear()->id;
        
        $this->selectedSurveillant = User::where('role', 'surveillant')
            ->where('campus_id', Auth::user()->campus_id)
            ->whereHas('someRelation', function($query) use ($currentAcademicYearId) {
                $query->where('academic_year_id', $currentAcademicYearId);
            })->findOrFail($id);
    }

    public function confirmDelete($id)
    {
        $this->surveillantToDelete = User::find($id);
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->surveillantToDelete = null;
        $this->showDeleteModal = false;
    }

    public function delete()
    {
        if (!Auth::user()->hasPermission('surveillants', 'delete')) {
            $this->dispatch('error', ['message' => 'Permission non accordée']);
            return;
        }

        try {
            $surveillant = $this->surveillantToDelete;
            
            // Enregistrer l'historique avant la suppression
            $this->outil->addHistorique(
                "Suppression du surveillant {$surveillant->prenom} {$surveillant->nom}",
                'delete',
                'users',
                $surveillant->id
            );

            // Marquer comme supprimé au lieu de supprimer physiquement
            $surveillant->update([
                'is_deleting' => true
            ]);

            $this->showDeleteModal = false;
            $this->surveillantToDelete = null;
            
            $this->dispatch('success', ['message' => 'Surveillant supprimé avec succès']);
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Une erreur est survenue']);
        }
    }

    public function store()
    {
        if ($this->id && !Auth::user()->hasPermission('surveillants', 'edit')) {
            $this->dispatch('error', ['message' => 'Permission non accordée']);
            return;
        }

        if (!$this->id && !Auth::user()->hasPermission('surveillants', 'create')) {
            $this->dispatch('error', ['message' => 'Permission non accordée']);
            return;
        }

        $this->validate();

        try {
            $photoPath = $this->photo ? $this->photo->store('photos', 'public') : null;

            if ($this->id) {
                $surveillant = User::findOrFail($this->id);
                $oldData = $surveillant->toArray();
                
                $surveillant->update([
                    'prenom' => $this->prenom,
                    'nom' => $this->nom,
                    'username' => $this->username,
                    'adresse' => $this->adresse,
                    'tel' => $this->tel,
                    'sexe' => $this->sexe,
                    'email' => $this->email,
                    'specialite' => $this->specialite,
                    'image' => $this->photo ? $photoPath : $surveillant->image
                ]);

                // Historique de modification
                $this->outil->addHistorique(
                    "Modification du surveillant {$surveillant->prenom} {$surveillant->nom}",
                    'edit',
                    'users',
                    $surveillant->id,
                    $oldData,
                    $surveillant->toArray()
                );
            } else {
                $surveillant = User::create([
                    'prenom' => $this->prenom,
                    'nom' => $this->nom,
                    'username' => $this->username,
                    'adresse' => $this->adresse,
                    'tel' => $this->tel,
                    'sexe' => $this->sexe,
                    'email' => $this->email,
                    'role' => 'surveillant',
                    'specialite' => $this->specialite,
                    'image' => $photoPath ?? 'profil.jpg',
                    'campus_id' => Auth::user()->campus_id
                ]);

                // Historique d'ajout
                $this->outil->addHistorique(
                    "Ajout du surveillant {$surveillant->prenom} {$surveillant->nom}",
                    'add',
                    'users',
                    $surveillant->id
                );
            }

            $this->status = "list";
            $this->resetForm();
            $this->dispatch('success', ['message' => 'Surveillant ' . ($this->id ? 'modifié' : 'ajouté') . ' avec succès']);
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Une erreur est survenue']);
        }
    }

    public function edit($id)
    {
        if (!Auth::user()->hasPermission('surveillants', 'edit')) {
            $this->dispatch('error', ['message' => 'Permission non accordée']);
            return;
        }

        $surveillant = User::findOrFail($id);
        
        // Remplir les propriétés avec les données du surveillant
        $this->id = $surveillant->id;
        $this->prenom = $surveillant->prenom;
        $this->nom = $surveillant->nom;
        $this->username = $surveillant->username;
        $this->adresse = $surveillant->adresse;
        $this->tel = $surveillant->tel;
        $this->sexe = $surveillant->sexe;
        $this->email = $surveillant->email;
        $this->specialite = $surveillant->specialite;

        // Changer le statut pour afficher le formulaire d'édition
        $this->status = 'add';
        $this->title = "Modifier le surveillant";
    }

     // Règles de validation personnalisées pour la mise à jour
     public function updatedRules()
     {
         return [
             'username' => [
                 'required',
                 'string',
                 'max:255',
                 Rule::unique('users')->ignore($this->id),
             ],
             'email' => [
                 'required',
                 'email',
                 Rule::unique('users')->ignore($this->id),
             ],
             'tel' => [
                 'required',
                 'regex:/^[33|70|75|76|77|78]+[0-9]{7}$/',
                 Rule::unique('users')->ignore($this->id),
             ],
         ];
     }

    #[Layout("components.layouts.app")]
    public function render()
    {
        $query = User::query()
            ->where('campus_id', Auth::user()->campus_id)
            ->where('role', 'surveillant')
            ->where('is_deleting', false)
            ->when($this->search, function($q) {
                $q->where(function($q) {
                    $q->where('nom', 'like', '%'.$this->search.'%')
                      ->orWhere('prenom', 'like', '%'.$this->search.'%')
                      ->orWhere('email', 'like', '%'.$this->search.'%')
                      ->orWhere('tel', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->specialiteFilter, function($q) {
                $q->where('specialite', $this->specialiteFilter);
            });

        $surveillants = $query->orderBy($this->sortField, $this->sortDirection)
                              ->paginate($this->perPage);

        return view('livewire.personnel.surveillant.surveillant', [
            'surveillants' => $surveillants
        ]);
    }

    private function resetForm()
    {
        $this->reset([
            'id', 'prenom', 'nom', 'username', 'adresse', 'tel', 
            'sexe', 'email', 'photo', 'specialite'
        ]);
    }
}
