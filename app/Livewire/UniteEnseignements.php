<?php

namespace App\Livewire;

use App\Models\Filiere;
use App\Models\Matiere;
use App\Models\UniteEnseignement;
use App\Models\Outils;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        'nom' => 'required|string|min:2',
        'credit' => 'required|numeric|min:1',
        'filiere_id' => 'required|exists:filieres,id',
        'listeMatieres' => 'required|array|min:1', // Au moins une matière requise
        'listeMatieres.*.nom' => 'required|string|min:2',
        'listeMatieres.*.credit' => 'required|numeric|min:1',
        'listeMatieres.*.coefficient' => 'required|numeric|min:1',
        'listeMatieres.*.volume_horaire' => 'required|numeric|min:1' // Changé de nullable à required
    ];

    protected $messages = [
        'nom.required' => 'Le nom de l\'UE est requis',
        'nom.min' => 'Le nom doit contenir au moins 2 caractères',
        'credit.required' => 'Le crédit est requis',
        'credit.numeric' => 'Le crédit doit être un nombre',
        'credit.min' => 'Le crédit doit être au moins 1',
        'filiere_id.required' => 'La filière est requise',
        'filiere_id.exists' => 'La filière sélectionnée n\'existe pas',
        'listeMatieres.required' => 'Au moins une matière est requise',
        'listeMatieres.min' => 'Au moins une matière est requise',
        'listeMatieres.*.nom.required' => 'Le nom de la matière est requis',
        'listeMatieres.*.credit.required' => 'Le crédit de la matière est requis',
        'listeMatieres.*.coefficient.required' => 'Le coefficient de la matière est requis',
        'listeMatieres.*.volume_horaire.required' => 'Le volume horaire de la matière est requis'
    ];

    public function mount()
    {
        $this->listeMatieres = [];
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
            $this->reset(['nom', 'credit', 'filiere_id', 'listeMatieres', 'id']);
            $this->listeMatieres = []; 
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
public $editingMatiere = false;
public $editingMatiereIndex = null;

public function editMatiere($index)
{
    if (!Auth::user()->hasPermission('ue', 'edit')) {
        $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de modifier']);
        return;
    }

    $this->editingMatiere = true;
    $this->editingMatiereIndex = $index;
    $this->matiere = $this->listeMatieres[$index];
}

public function updateMatiere()
{
    $this->validate([
        'matiere.nom' => 'required',
        'matiere.credit' => 'required|numeric|min:1',
        'matiere.coefficient' => 'required|numeric|min:1',
        'matiere.volume_horaire' => 'required|numeric|min:1'
    ]);

    $this->listeMatieres[$this->editingMatiereIndex] = $this->matiere;

    $this->cancelEdit();
    $this->dispatch('success', ['message' => 'Matière modifiée avec succès']);
}

public function edit($id)
{
    if (!Auth::user()->hasPermission('ue', 'edit')) {
        $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de modifier']);
        return;
    }

    $this->status = "add";
    $this->title = "Modifier l'unité d'enseignement";
    
    $ue = UniteEnseignement::with(['matieres' => function($query) {
        $query->where('is_deleting', false);
    }])->findOrFail($id);
    
    // Remplir les informations de l'UE
    $this->id = $ue->id;
    $this->nom = $ue->nom;
    $this->credit = $ue->credit;
    $this->filiere_id = $ue->filiere_id;

    // Remplir la liste des matières
    $this->listeMatieres = $ue->matieres->map(function($matiere) {
        return [
            'nom' => $matiere->nom,
            'credit' => $matiere->credit,
            'coefficient' => $matiere->coefficient,
            'volume_horaire' => $matiere->volume_horaire
        ];
    })->toArray();
}

public function cancelEdit()
{
    $this->editingMatiere = false;
    $this->editingMatiereIndex = null;
    $this->matiere = [
        'nom' => '',
        'credit' => '',
        'coefficient' => '',
        'volume_horaire' => ''
    ];
}

public function addMatiere()
{
    $this->validate([
        'matiere.nom' => 'required',
        'matiere.credit' => 'required|numeric|min:1',
        'matiere.coefficient' => 'required|numeric|min:1',
        'matiere.volume_horaire' => 'numeric|min:1'
    ]);

    $this->listeMatieres[] = $this->matiere;

    $this->matiere = [
        'nom' => '',
        'credit' => '',
        'coefficient' => '',
        'volume_horaire' => ''
    ];

    $this->dispatch('success', ['message' => 'Matière ajoutée avec succès']);
}

public function removeMatiere($index)
{
    if (!Auth::user()->hasPermission('ue', 'edit')) {
        $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de supprimer']);
        return;
    }

    unset($this->listeMatieres[$index]);
    $this->listeMatieres = array_values($this->listeMatieres);
    $this->dispatch('success', ['message' => 'Matière supprimée']);
}

public function store()
{
    \Log::info('Store method called');
    \Log::info('Form Data:', [
        'id' => $this->id,
        'nom' => $this->nom,
        'credit' => $this->credit,
        'filiere_id' => $this->filiere_id,
        'listeMatieres' => $this->listeMatieres
    ]);

    try {
        $this->validate();

        if (empty($this->listeMatieres)) {
            $this->addError('listeMatieres', 'Au moins une matière est requise');
            return;
        }

        // Vérifier que le total des crédits des matières correspond au crédit de l'UE
        $totalCreditsMatiere = array_sum(array_column($this->listeMatieres, 'credit'));
        if ($totalCreditsMatiere != $this->credit) {
            $this->addError('credit', 'Le total des crédits des matières (' . $totalCreditsMatiere . ') doit être égal au crédit de l\'UE (' . $this->credit . ')');
            return;
        }

        DB::beginTransaction();

        if ($this->id) {
            \Log::info('Updating existing UE');
            if (!Auth::user()->hasPermission('ue', 'edit')) {
                $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de modifier']);
                return;
            }

            $ue = UniteEnseignement::findOrFail($this->id);
            $ue->update([
                'nom' => $this->nom,
                'credit' => $this->credit,
                'filiere_id' => $this->filiere_id
            ]);

            // Marquer les anciennes matières comme supprimées
            $ue->matieres()->update(['is_deleting' => true]);

            // Créer les nouvelles matières
            foreach ($this->listeMatieres as $matiereData) {
                Matiere::create([
                    'nom' => $matiereData['nom'],
                    'credit' => $matiereData['credit'],
                    'coefficient' => $matiereData['coefficient'],
                    'volume_horaire' => $matiereData['volume_horaire'],
                    'unite_enseignement_id' => $ue->id,
                    'campus_id' => Auth::user()->campus_id
                ]);
            }

            $message = 'Unité d\'enseignement mise à jour avec succès';
            $event = 'update';
        } else {
            if (!Auth::user()->hasPermission('ue', 'create')) {
                $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de créer']);
                return;
            }

            $ue = UniteEnseignement::create([
                'nom' => $this->nom,
                'credit' => $this->credit,
                'filiere_id' => $this->filiere_id,
                'campus_id' => Auth::user()->campus_id
            ]);

            foreach ($this->listeMatieres as $matiereData) {
                Matiere::create([
                    'nom' => $matiereData['nom'],
                    'credit' => $matiereData['credit'],
                    'coefficient' => $matiereData['coefficient'],
                    'volume_horaire' => $matiereData['volume_horaire'],
                    'unite_enseignement_id' => $ue->id,
                    'campus_id' => Auth::user()->campus_id
                ]);
            }

            $message = 'Unité d\'enseignement créée avec succès';
            $event = 'added';
        }

        DB::commit();
        $this->dispatch($event, ['message' => $message]);
        $this->reset(['id', 'nom', 'credit', 'filiere_id', 'listeMatieres', 'matiere']);
        $this->changeStatus('list');

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation failed:', $e->errors());
        DB::rollBack();
        throw $e;
    } catch (\Exception $e) {
        \Log::error('Error in store method: ' . $e->getMessage());
        DB::rollBack();
        $this->dispatch('error', ['message' => 'Une erreur est survenue : ' . $e->getMessage()]);
    }
}

public function updated($propertyName)
{
    $this->validateOnly($propertyName);
    
    // Validation spécifique pour le crédit
    if ($propertyName === 'credit') {
        $totalCreditsMatiere = array_sum(array_column($this->listeMatieres, 'credit'));
        if ($totalCreditsMatiere > $this->credit) {
            $this->addError('credit', 'Le crédit total ne peut pas être inférieur à la somme des crédits des matières (' . $totalCreditsMatiere . ')');
        }
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