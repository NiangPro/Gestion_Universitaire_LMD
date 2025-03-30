<?php

namespace App\Livewire;

use App\Models\Classe;
use App\Models\Filiere;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Outils;

#[Title("Classes")]
class Classes extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $status = "list";
    public $title = "Liste des classes";
    public $id, $nom, $filiere_id, $cout_formation, $cout_inscription, $mensualite, $type_periode, $duree;
    public $classe = null;
    public $outil;

    // Filtres
    public $search = '';
    public $selectedFiliere = '';
    public $perPage = 10;
    public $sortField = 'nom';
    public $sortDirection = 'asc';

    // Ajout des propriétés pour l'année académique
    public $selectedAcademicYear = null;
    public $etudiants = [];
    public $currentAcademicYear = null;
    public $academicYears = [];

    protected $rules = [
        'nom' => 'required|string|max:255',
        'filiere_id' => 'required|exists:filieres,id',
        'cout_inscription' => 'nullable|numeric|min:0',
        'mensualite' => 'nullable|numeric|min:0',
        'type_periode' => 'required|in:annee,mois',
        'duree' => 'required|integer|min:1',
    ];

    protected $messages = [
        'nom.required' => 'Le nom est obligatoire',
        'filiere_id.required' => 'La filière est obligatoire',
        'type_periode.required' => 'Le type de période est obligatoire',
        'type_periode.in' => 'Le type de période doit être année ou mois',
        'duree.required' => 'La durée est obligatoire',
        'duree.integer' => 'La durée doit être un nombre entier',
        'duree.min' => 'La durée minimum est de 1',
    ];

    public function mount()
    {
        // Charger les années académiques dès le début
        $this->academicYears = \App\Models\AcademicYear::where('campus_id', Auth::user()->campus_id)
            ->where('is_deleting', false)
            ->orderBy('debut', 'desc')
            ->get();
    }

    public function changeStatus($status)
    {
        if (!Auth::user()->hasPermission('classes', $status === 'add' ? 'create' : 'view')) {
            $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission nécessaire']);
            return;
        }

        $this->status = $status;
        switch ($status) {
            case 'add':
                $this->title = "Ajouter une classe";
                $this->reset(['id', 'nom', 'filiere_id', 'cout_formation', 'cout_inscription', 'mensualite', 'type_periode', 'duree']);
                break;
            case 'list':
                $this->title = "Liste des classes";
                $this->reset(['search', 'selectedFiliere']);
                break;
            case 'info':
                $this->title = "Détails de la classe";
                break;
        }
    }

    public function updatedSelectedAcademicYear($value)
    {
        if ($value) {
            $this->etudiants = $this->classe->etudiants()
                ->whereHas('inscriptions', function($query) use ($value) {
                    $query->where('academic_year_id', $value)
                        ->where('classe_id', $this->classe->id);
                })
                ->orderBy('nom')
                ->get();

            $this->currentAcademicYear = \App\Models\AcademicYear::find($value);
        } else {
            $this->etudiants = collect();
            $this->currentAcademicYear = null;
        }
    }

    public function getInfo($id)
    {
        if (!Auth::user()->hasPermission('classes', 'view')) {
            $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de voir les détails']);
            return;
        }

        $this->status = "info";
        $this->title = "Détails de la classe";
        
        $this->classe = Classe::with(['filiere'])->findOrFail($id);
        
        // Réinitialiser la sélection
        $this->selectedAcademicYear = null;
        $this->etudiants = collect();
        
        // Recharger les années académiques
        $this->academicYears = \App\Models\AcademicYear::where('campus_id', Auth::user()->campus_id)
            ->where('is_deleting', false)
            ->orderBy('debut', 'desc')
            ->get();
    }

    public function updatedTypePeriode()
    {
        $this->calculateCoutFormation();
    }

    public function updatedDuree()
    {
        $this->calculateCoutFormation();
    }

    public function updatedMensualite()
    {
        $this->calculateCoutFormation();
    }

    public function updatedCoutInscription()
    {
        $this->calculateCoutFormation();
    }

    protected function calculateCoutFormation()
    {
        if (!$this->mensualite || !$this->duree || !$this->type_periode) {
            $this->cout_formation = 0;
            return;
        }

        $inscription = $this->cout_inscription ?: 0;
        $mensualite = $this->mensualite;
        $duree = $this->duree;

        if ($this->type_periode === 'mois') {
            // Si la période est en mois, calcul direct
            $this->cout_formation = ($mensualite * $duree) + $inscription;
        } else {
            // Si la période est en années, multiplier par 9 mois (période scolaire standard)
            $this->cout_formation = ($mensualite * 9 * $duree) + $inscription;
        }
    }

    public function getCalculatedCoutFormationProperty()
    {
        if (!$this->mensualite || !$this->duree || !$this->type_periode) {
            return 0;
        }

        $inscription = $this->cout_inscription ?: 0;
        $mensualite = $this->mensualite ?: 0;
        $duree = $this->duree;

        if ($this->type_periode === 'mois') {
            return ($mensualite * $duree) + $inscription;
        } else {
            return ($mensualite * 9 * $duree) + $inscription;
        }
    }

    public function store()
    {
        $this->validate();

        $this->cout_formation = $this->calculated_cout_formation;

        $this->outil = new Outils();
        if ($this->id) {
            if (!Auth::user()->hasPermission('classes', 'edit')) {
                $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de modifier']);
                return;
            }

            $classe = Classe::findOrFail($this->id);
            $classe->update([
                'nom' => $this->nom,
                'filiere_id' => $this->filiere_id,
                'cout_formation' => $this->cout_formation,
                'cout_inscription' => $this->cout_inscription,
                'mensualite' => $this->mensualite,
                'type_periode' => $this->type_periode,
                'duree' => $this->duree
            ]);

            $this->outil->addHistorique("Modification de la classe {$this->nom}", "edit");
            $this->dispatch('updated');
        } else {
            if (!Auth::user()->hasPermission('classes', 'create')) {
                $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de créer']);
                return;
            }

            Classe::create([
                'nom' => $this->nom,
                'filiere_id' => $this->filiere_id,
                'cout_formation' => $this->cout_formation,
                'cout_inscription' => $this->cout_inscription,
                'mensualite' => $this->mensualite,
                'type_periode' => $this->type_periode,
                'duree' => $this->duree,
                'campus_id' => Auth::user()->campus_id
            ]);

            $this->outil->addHistorique("Création de la nouvelle classe {$this->nom}", "add");
            $this->dispatch('added');
        }

        $this->changeStatus('list');
    }

    public function edit($id)
    {
        if (!Auth::user()->hasPermission('classes', 'edit')) {
            $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de modifier']);
            return;
        }

        $this->status = "add";
        $this->title = "Modifier la classe";
        
        $classe = Classe::findOrFail($id);
        
        $this->id = $classe->id;
        $this->nom = $classe->nom;
        $this->filiere_id = $classe->filiere_id;
        $this->cout_formation = $classe->cout_formation;
        $this->cout_inscription = $classe->cout_inscription;
        $this->mensualite = $classe->mensualite;
        $this->type_periode = $classe->type_periode;
        $this->duree = $classe->duree;
    }

    public function supprimer($id)
    {
        if (!Auth::user()->hasPermission('classes', 'delete')) {
            $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de supprimer']);
            return;
        }

        try {
            $classe = Classe::findOrFail($id);
            
            // Marquer comme supprimé au lieu de supprimer physiquement
            $classe->update(['is_deleting' => true]);

            $this->outil = new Outils();
            $this->outil->addHistorique("Suppression de la classe {$classe->nom}", "delete");

            $this->dispatch('delete', ['message' => 'Classe supprimée avec succès']);
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Une erreur est survenue lors de la suppression']);
        }
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        $query = Classe::query()
            ->where('campus_id', Auth::user()->campus_id)
            ->where('is_deleting', false);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nom', 'like', '%' . $this->search . '%')
                    ->orWhereHas('filiere', function($q) {
                        $q->where('nom', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->selectedFiliere) {
            $query->where('filiere_id', $this->selectedFiliere);
        }

        // Utiliser le campus de l'utilisateur connecté
        $campus = Auth::user()->campus;
        $currentAcademicYear = $campus->currentAcademicYear();

        $classes = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->with(['filiere'])
            ->withCount(['etudiants' => function($query) use ($campus) {
                $query->whereHas('inscriptions', function($q) use ($campus) {
                    $q->whereIn('id', $campus->currentInscriptions()->pluck('id'));
                });
            }])
            ->paginate($this->perPage);

        return view('livewire.classe.classes', [
            'classes' => $classes,
            'filieres' => Filiere::where('campus_id', Auth::user()->campus_id)
                ->where('is_deleting', false)
                ->get(),
            'currentAcademicYear' => $currentAcademicYear
        ]);
    }
}
