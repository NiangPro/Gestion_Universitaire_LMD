<?php

namespace App\Livewire;

use App\Models\Filiere;
use App\Models\Matiere;
use App\Models\UniteEnseignement;
use App\Models\Outils;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title("Unités d'Enseignement")]
class UniteEnseignements extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $status = "list";
    public $title = "Liste des unités d'enseignement";
    public $outil;
    public $id, $nom, $credit, $filiere_id;
    public $uniteEnseignement;
    
    // Pour les matières
    public $matieres = [];
    public $newMatiere = [
        'nom' => '',
        'credit' => '',
        'coefficient' => '',
        'volume_horaire' => ''
    ];

    // Filtres
    public $search = '';
    public $selectedFiliere = '';
    public $creditMin = '';
    public $creditMax = '';
    public $perPage = 10;
    public $sortField = 'nom';
    public $sortDirection = 'asc';

    

    protected $rules = [
        'nom' => 'required|string',
        'credit' => 'required|numeric|min:1',
        'filiere_id' => 'required',
        'matieres' => 'array',
        'matieres.*.nom' => 'required|string',
        'matieres.*.credit' => 'required|numeric|min:1',
        'matieres.*.coefficient' => 'required|numeric|min:1',
        'matieres.*.volume_horaire' => 'nullable|numeric|min:1'
    ];

    public function mount()
    {
        $this->matieres = [];
        $this->outil = new Outils();
    }

    public function addNewMatiere()
    {
        if (!empty($this->newMatiere['nom'])) {
            $this->matieres[] = $this->newMatiere;
            $this->newMatiere = [
                'nom' => '',
                'credit' => '',
                'coefficient' => '',
                'volume_horaire' => ''
            ];
        }
    }

    public function getInfo($id)
    {
        if (!Auth::user()->hasPermission('ue', 'view')) {
            $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de voir les détails']);
            return;
        }

        $this->status = "info";
        $this->title = "Détails de l'unité d'enseignement";
        
        $ue = UniteEnseignement::with([
            'filiere',
            'matieres' => function($query) {
                $query->where('is_deleting', false);
            }
        ])->findOrFail($id);

        $this->uniteEnseignement = $ue;
    }

    public function supprimer($id)
{
    if (!Auth::user()->hasPermission('ue', 'delete')) {
        $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de supprimer']);
        return;
    }

    try {
        $ue = UniteEnseignement::findOrFail($id);
        
        // Marquer comme supprimé
        $ue->update(['is_deleting' => true]);
        
        // Marquer les matières comme supprimées
        $ue->matieres()->update(['is_deleting' => true]);

        $this->dispatch('delete', ['message' => 'Unité d\'enseignement supprimée avec succès']);
    } catch (\Exception $e) {
        $this->dispatch('error', ['message' => 'Une erreur est survenue lors de la suppression']);
    }
}

    public function changeStatus($status)
{
    if (!Auth::user()->hasPermission('ue', $status === 'add' ? 'create' : 'view')) {
        $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission nécessaire']);
        return;
    }

    $this->status = $status;

    // Mise à jour du titre en fonction du status
    switch ($status) {
        case 'add':
            $this->title = "Ajouter une unité d'enseignement";
            // Réinitialisation des champs du formulaire
            $this->reset(['nom', 'credit', 'filiere_id', 'matieres', 'id']);
            $this->matieres = []; // Réinitialisation explicite du tableau des matières
            
            break;
        case 'list':
            $this->title = "Liste des unités d'enseignement";
            // Réinitialisation des filtres
            $this->reset(['search', 'selectedFiliere', 'creditMin', 'creditMax']);
            break;
        case 'info':
            $this->title = "Détails de l'unité d'enseignement";
            break;
        case 'edit':
            $this->title = "Modifier l'unité d'enseignement";
            break;
        default:
            $this->title = "Liste des unités d'enseignement";
            $this->status = "list";
    }

    // Réinitialisation de la nouvelle matière
    $this->newMatiere = [
        'nom' => '',
        'credit' => '',
        'coefficient' => '',
        'volume_horaire' => ''
    ];
}

public $matiere = [
    'nom' => '',
    'credit' => '',
    'coefficient' => '',
    'volume_horaire' => ''
];

public $listeMatieres = [];

public function addMatiere()
{
    // Validation de la matière
    $this->validate([
        'matiere.nom' => 'required',
        'matiere.credit' => 'required|numeric|min:1',
        'matiere.coefficient' => 'required|numeric|min:1',
        'matiere.volume_horaire' => 'nullable|numeric|min:1'
    ], [
        'matiere.nom.required' => 'Le nom de la matière est requis',
        'matiere.credit.required' => 'Le crédit est requis',
        'matiere.credit.numeric' => 'Le crédit doit être un nombre',
        'matiere.credit.min' => 'Le crédit doit être supérieur à 0',
        'matiere.coefficient.required' => 'Le coefficient est requis',
        'matiere.coefficient.numeric' => 'Le coefficient doit être un nombre',
        'matiere.coefficient.min' => 'Le coefficient doit être supérieur à 0',
        'matiere.volume_horaire.numeric' => 'Le volume horaire doit être un nombre',
        'matiere.volume_horaire.min' => 'Le volume horaire doit être supérieur à 0'
    ]);

    // Ajouter la matière à la liste
    $this->listeMatieres[] = $this->matiere;

    // Réinitialiser le formulaire de matière
    $this->matiere = [
        'nom' => '',
        'credit' => '',
        'coefficient' => '',
        'volume_horaire' => ''
    ];

    // Notification de succès
    $this->dispatch('success', ['message' => 'Matière ajoutée avec succès']);
}

public function removeMatiere($index)
{
    unset($this->listeMatieres[$index]);
    $this->listeMatieres = array_values($this->listeMatieres);
    $this->dispatch('success', ['message' => 'Matière supprimée']);
}

    public function store()
    {
        $this->validate();

        try {
            if ($this->id) {
                $ue = UniteEnseignement::findOrFail($this->id);
                $ue->update([
                    'nom' => $this->nom,
                    'credit' => $this->credit,
                    'filiere_id' => $this->filiere_id
                ]);

                // Mettre à jour ou créer les matières
                foreach ($this->matieres as $matiereData) {
                    Matiere::create([
                        'nom' => $matiereData['nom'],
                        'credit' => $matiereData['credit'],
                        'coefficient' => $matiereData['coefficient'],
                        'volume_horaire' => $matiereData['volume_horaire'],
                        'unite_enseignement_id' => $ue->id,
                        'campus_id' => Auth::user()->campus_id
                    ]);
                }

                $this->dispatch('update');
            } else {
                $ue = UniteEnseignement::create([
                    'nom' => $this->nom,
                    'credit' => $this->credit,
                    'filiere_id' => $this->filiere_id,
                    'campus_id' => Auth::user()->campus_id
                ]);

                foreach ($this->matieres as $matiereData) {
                    Matiere::create([
                        'nom' => $matiereData['nom'],
                        'credit' => $matiereData['credit'],
                        'coefficient' => $matiereData['coefficient'],
                        'volume_horaire' => $matiereData['volume_horaire'],
                        'unite_enseignement_id' => $ue->id,
                        'campus_id' => Auth::user()->campus_id
                    ]);
                }

                $this->dispatch('added');
            }

            $this->reset(['nom', 'credit', 'filiere_id', 'matieres', 'id']);
            $this->changeStatus('list');
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Une erreur est survenue']);
        }
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $query = UniteEnseignement::query()
            ->where('campus_id', Auth::user()->campus_id)
            ->where('is_deleting', false);

        if ($this->search) {
            $query->where('nom', 'like', '%' . $this->search . '%');
        }

        if ($this->selectedFiliere) {
            $query->where('filiere_id', $this->selectedFiliere);
        }

        if ($this->creditMin) {
            $query->where('credit', '>=', $this->creditMin);
        }

        if ($this->creditMax) {
            $query->where('credit', '<=', $this->creditMax);
        }

        $uniteEnseignements = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->with(['filiere', 'matieres'])
            ->paginate($this->perPage);

        return view('livewire.ue.unite-enseignements', [
            'uniteEnseignements' => $uniteEnseignements,
            'filieres' => Filiere::where('campus_id', Auth::user()->campus_id)
                ->where('is_deleting', false)
                ->get()
        ]);
    }
}