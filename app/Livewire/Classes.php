<?php

namespace App\Livewire;

use App\Models\Classe;
use App\Models\Filiere;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title("Classes")]
class Classes extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $status = "list";
    public $title = "Liste des classes";
    public $id, $nom, $filiere_id, $cout_formation, $cout_inscription, $mensualite;
    public $classe = null;

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
        'cout_formation' => 'nullable|numeric|min:0',
        'cout_inscription' => 'nullable|numeric|min:0',
        'mensualite' => 'nullable|numeric|min:0',
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
                $this->reset(['id', 'nom', 'filiere_id', 'cout_formation', 'cout_inscription', 'mensualite']);
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

    public function store()
    {
        $this->validate();

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
                'mensualite' => $this->mensualite
            ]);

            $this->dispatch('update', ['message' => 'Classe mise à jour avec succès']);
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
                'campus_id' => Auth::user()->campus_id
            ]);

            $this->dispatch('added', ['message' => 'Classe ajoutée avec succès']);
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
        
        // Remplir les informations de la classe
        $this->id = $classe->id;
        $this->nom = $classe->nom;
        $this->filiere_id = $classe->filiere_id;
        $this->cout_formation = $classe->cout_formation;
        $this->cout_inscription = $classe->cout_inscription;
        $this->mensualite = $classe->mensualite;
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
