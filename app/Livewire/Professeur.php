<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Classe;
use App\Models\Cour;
use App\Models\Note;
use App\Models\Outils;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Validation\Rule;

#[Title("Gestion des Professeurs")]
class Professeur extends Component
{
    use WithPagination, WithFileUploads;
    protected $paginationTheme = 'bootstrap';

    public $status = "list";
    public $title = "Liste des professeurs";
    public $outil;
    public $selectedProfesseur;
    
    // Propriétés du formulaire
    public $id, $prenom, $nom, $username, $adresse, $tel, $sexe, $email;
    public $photo;
    public $specialite;
    public $disponibilites = [];
    public $cours_assignes = [];

    // Filtres
    public $search = '';
    public $specialiteFilter = '';
    public $disponibiliteFilter = '';
    public $perPage = 10;
    public $sortField = 'nom';
    public $sortDirection = 'asc';

    public $showDeleteModal = false;
    public $professorToDelete = null;

    protected $rules = [
        'prenom' => 'required|string|max:255',
        'nom' => 'required|string|max:255',
        'username' => 'required|string|max:255',
        'adresse' => 'required|string|max:255',
        'tel' => ['required', 'regex:/^[33|70|75|76|77|78]+[0-9]{7}$/'],
        'sexe' => 'required|in:Homme,Femme',
        'email' => 'required|email',
        'specialite' => 'required|string',
        'disponibilites' => 'required|array|min:1',
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
        if (!Auth::user()->hasPermission('professeurs', $status === 'add' ? 'create' : 'view')) {
            $this->dispatch('error', ['message' => 'Permission non accordée']);
            return;
        }

        $this->status = $status;
        
        if ($status === 'details' && $id) {
            $this->loadProfesseurDetails($id);
            $this->title = "Détails du professeur";
        } else {
            $this->title = $status === "add" ? "Ajouter un professeur" : "Liste des professeurs";
            $this->resetForm();
        }
    }

    public function loadProfesseurDetails($id)
    {
        $currentAcademicYearId = Auth::user()->campus->currentAcademicYear()->id;
        
        $this->selectedProfesseur = User::with([
            'cours' => function($query) use ($currentAcademicYearId) {
                $query->where('cours.academic_year_id', $currentAcademicYearId)
                      ->with(['matiere', 'classe']);
            }
        ])->findOrFail($id);

        // Calculer les statistiques spécifiques au professeur
        $this->stats = [
            'total_cours' => Cour::where('professeur_id', $id)
                                ->where('academic_year_id', $currentAcademicYearId)
                                ->count(),
            'total_notes' => Note::whereHas('matiere', function($query) use ($id, $currentAcademicYearId) {
                                $query->whereIn('id', function($subquery) use ($id) {
                                    $subquery->select('matiere_id')
                                            ->from('cours')
                                            ->where('professeur_id', $id);
                                });
                            })
                            ->where('academic_year_id', $currentAcademicYearId)
                            ->count(),
            'total_classes' => Cour::where('professeur_id', $id)
                                  ->where('academic_year_id', $currentAcademicYearId)
                                  ->distinct('classe_id')
                                  ->count()
        ];
    }

    public function confirmDelete($id)
    {
        $this->professorToDelete = User::find($id);
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->professorToDelete = null;
        $this->showDeleteModal = false;
    }

    public function delete()
    {
        if (!Auth::user()->hasPermission('professeurs', 'delete')) {
            $this->dispatch('error', ['message' => 'Permission non accordée']);
            return;
        }

        try {
            $professor = $this->professorToDelete;
            
            // Enregistrer l'historique avant la suppression
            $this->outil->addHistorique(
                "Suppression du professeur {$professor->prenom} {$professor->nom}",
                'delete',
                'users',
                $professor->id
            );

            // Marquer comme supprimé au lieu de supprimer physiquement
            $professor->update([
                'is_deleting' => true
            ]);

            $this->showDeleteModal = false;
            $this->professorToDelete = null;
            
            $this->dispatch('success', ['message' => 'Professeur supprimé avec succès']);
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Une erreur est survenue']);
        }
    }

    public function store()
    {
        if ($this->id && !Auth::user()->hasPermission('professeurs', 'edit')) {
            $this->dispatch('error', ['message' => 'Permission non accordée']);
            return;
        }

        if (!$this->id && !Auth::user()->hasPermission('professeurs', 'create')) {
            $this->dispatch('error', ['message' => 'Permission non accordée']);
            return;
        }

        $this->validate();

        try {
            $photoPath = $this->photo ? $this->photo->store('photos', 'public') : null;
       
        if ($this->id) {
                $prof = User::findOrFail($this->id);
                $oldData = $prof->toArray();
                
                $prof->update([
                    'prenom' => $this->prenom,
                    'nom' => $this->nom,
                    'username' => $this->username,
                    'adresse' => $this->adresse,
                    'tel' => $this->tel,
                    'sexe' => $this->sexe,
                    'email' => $this->email,
                    'specialite' => $this->specialite,
                    'disponibilites' => $this->disponibilites,
                    'image' => $this->photo ? $photoPath : $prof->image
                ]);

                // Historique de modification
                $this->outil->addHistorique(
                    "Modification du professeur {$prof->prenom} {$prof->nom}",
                    'edit',
                    'users',
                    $prof->id,
                    $oldData,
                    $prof->toArray()
                );
            } else {
                $prof = User::create([
                'prenom' => $this->prenom,
                'nom' => $this->nom,
                'username' => $this->username,
                'adresse' => $this->adresse,
                'tel' => $this->tel,
                'sexe' => $this->sexe,
                'email' => $this->email,
                    'role' => 'professeur',
                    'specialite' => $this->specialite,
                    'disponibilites' => $this->disponibilites,
                    'image' => $photoPath ?? 'profil.jpg',
                    'campus_id' => Auth::user()->campus_id
                ]);

                // Historique d'ajout
                $this->outil->addHistorique(
                    "Ajout du professeur {$prof->prenom} {$prof->nom}",
                    'add',
                    'users',
                    $prof->id
                );
            }

            $this->status = "list";
            $this->resetForm();
            $this->dispatch('success', ['message' => 'Professeur ' . ($this->id ? 'modifié' : 'ajouté') . ' avec succès']);
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Une erreur est survenue']);
        }
    }

    public function edit($id)
    {
        if (!Auth::user()->hasPermission('professeurs', 'edit')) {
            $this->dispatch('error', ['message' => 'Permission non accordée']);
            return;
        }

        $professeur = User::findOrFail($id);
        
        // Remplir les propriétés avec les données du professeur
        $this->id = $professeur->id;
        $this->prenom = $professeur->prenom;
        $this->nom = $professeur->nom;
        $this->username = $professeur->username;
        $this->adresse = $professeur->adresse;
        $this->tel = $professeur->tel;
        $this->sexe = $professeur->sexe;
        $this->email = $professeur->email;
        $this->specialite = $professeur->specialite;
        $this->disponibilites = $professeur->disponibilites ?? [];

        // Changer le statut pour afficher le formulaire d'édition
        $this->status = 'add';
        $this->title = "Modifier le professeur";
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
            ->where('role', 'professeur')
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

        $professeurs = $query->orderBy($this->sortField, $this->sortDirection)
                           ->paginate($this->perPage);

        $currentAcademicYearId = Auth::user()->campus->currentAcademicYear()->id;

        $stats = [
            'total_cours' => Cour::where('campus_id', Auth::user()->campus_id)
                                ->where('academic_year_id', $currentAcademicYearId)
                                ->count(),
            'total_notes' => Note::where('campus_id', Auth::user()->campus_id)
                               ->where('academic_year_id', $currentAcademicYearId)
                               ->count(),
            'total_classes' => Classe::where('campus_id', Auth::user()->campus_id)
                                   ->where('academic_year_id', $currentAcademicYearId)
                                   ->count()
        ];

        return view('livewire.personnel.professeur.professeur', [
            'professeurs' => $professeurs,
            'stats' => $stats
        ]);
    }

    private function resetForm()
    {
        $this->reset([
            'id', 'prenom', 'nom', 'username', 'adresse', 'tel', 
            'sexe', 'email', 'photo', 'specialite', 'disponibilites'
        ]);
    }
}
