<?php

namespace App\Livewire;

use App\Models\Retard;
use App\Models\Cour;
use App\Models\Outils;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title("Retards")]
class Retards extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $isOpen = false;
    public $selectedYear = null;
    public $selectedClasse = null;
    public $classes = [];
    public $search = '';
    public $academicYear = null;
    public $inscriptions = [];
    public $retards = [];
    public $outil;
    
    // Formulaire
    public $etudiant_id;
    public $cours_id;
    public $date;
    public $minutes_retard;
    public $motif;
    public $justifie = false;
    public $commentaire;
    public $semestre_id;
    
    // Pour l'édition
    public $retard_id;
    public $isEditing = false;

    protected $rules = [
        'etudiant_id' => 'required',
        'cours_id' => 'required',
        'date' => 'required|date',
        'minutes_retard' => 'required|integer|min:1',
        'motif' => 'nullable|string',
        'justifie' => 'boolean',
        'commentaire' => 'nullable|string'
    ];

    protected $messages = [
        'etudiant_id.required' => 'L\'étudiant est obligatoire',
        'cours_id.required' => 'Le cours est obligatoire',
        'date.required' => 'La date est obligatoire',
        'date.date' => 'La date est invalide',
        'minutes_retard.required' => 'La durée du retard est obligatoire',
        'minutes_retard.integer' => 'La durée doit être un nombre entier',
        'minutes_retard.min' => 'La durée doit être d\'au moins 1 minute',
    ];

    public function updatedCoursId($value)
    {
        if ($value) {
            $classe = Cour::find($value)->classe;
            $this->inscriptions = Auth::user()->campus->currentInscriptions()
                ->where('classe_id', $classe->id)
                ->get();
        }
    }

    public function updatedSelectedYear($value)
    {
        if ($value) {
            $this->classes = Auth::user()->campus->classes;
            $this->loadRetards();
        }
    }

    public function updatedSelectedClasse($value)
    {
        if ($value) {
            $this->loadRetards();
        }
    }

    private function loadRetards()
    {
        $query = Retard::where('campus_id', Auth::user()->campus_id);

        if ($this->selectedYear) {
            $query->where('academic_year_id', $this->selectedYear);
        }

        if ($this->selectedClasse) {
            $query->whereHas('etudiant', function($q) {
                $q->whereHas('inscriptions', function($q) {
                    $q->where('classe_id', $this->selectedClasse);
                });
            });
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('etudiant', function($q) {
                    $q->where('nom', 'like', '%' . $this->search . '%')
                      ->orWhere('prenom', 'like', '%' . $this->search . '%');
                })->orWhereHas('cours', function($q) {
                    $q->whereHas('matiere', function($q) {
                        $q->where('nom', 'like', '%' . $this->search . '%');
                    });
                });
            });
        }

        $this->retards = $query->with(['etudiant', 'cours.matiere'])->latest()->get();
    }

    public function showAddRetardModal()
    {
        if (!Auth::user()->hasPermission('retards', 'create')) {
            $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de créer des retards']);
            return;
        }
        $this->resetForm();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['etudiant_id', 'cours_id', 'date', 'minutes_retard', 'motif', 'justifie', 'commentaire', 'retard_id', 'isEditing']);
        $this->date = now()->format('Y-m-d\TH:i');
    }

    public function edit(Retard $retard)
    {
        if (!Auth::user()->hasPermission('retards', 'edit')) {
            $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de modifier']);
            return;
        }

        $this->isEditing = true;
        $this->retard_id = $retard->id;
        $this->etudiant_id = $retard->etudiant_id;
        $this->cours_id = $retard->cours_id;
        $this->date = $retard->date->format('Y-m-d\TH:i');
        $this->minutes_retard = $retard->minutes_retard;
        $this->motif = $retard->motif;
        $this->justifie = $retard->justifie;
        $this->commentaire = $retard->commentaire;
        
        $this->updatedCoursId($this->cours_id);
        $this->isOpen = true;
    }

    public function delete($id)
    {
        if (!Auth::user()->hasPermission('retards', 'delete')) {
            $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de supprimer']);
            return;
        }

        try {
            $retard = Retard::findOrFail($id);
            $etudiant = $retard->etudiant->prenom . ' ' . $retard->etudiant->nom;
            $cours = $retard->cours->matiere->nom;
            
            $retard->delete();
            
            // Ajout de l'historique
            $this->outil->addHistorique(
                "Suppression du retard de l'étudiant $etudiant pour le cours de $cours", 
                "delete"
            );
            
            $this->loadRetards();
            $this->dispatch('deleted');
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Une erreur est survenue lors de la suppression']);
        }
    }

    public function save()
    {
        if ($this->isEditing && !Auth::user()->hasPermission('retards', 'edit')) {
            $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de modifier']);
            return;
        }

        if (!$this->isEditing && !Auth::user()->hasPermission('retards', 'create')) {
            $this->dispatch('error', ['message' => 'Vous n\'avez pas la permission de créer']);
            return;
        }

        $this->validate();

        try {
            $data = [
                'etudiant_id' => $this->etudiant_id,
                'cours_id' => $this->cours_id,
                'date' => $this->date,
                'minutes_retard' => $this->minutes_retard,
                'motif' => $this->motif,
                'justifie' => $this->justifie,
                'commentaire' => $this->commentaire,
                'campus_id' => Auth::user()->campus_id,
                'academic_year_id' => Auth::user()->campus->currentAcademicYear()->id,
                'semestre_id' => Auth::user()->campus->currentSemestre()->id,
                'created_by' => Auth::user()->id
            ];

            if ($this->isEditing) {
                $retard = Retard::find($this->retard_id);
                $retard->update($data);
                
                // Ajout de l'historique pour la modification
                $this->outil->addHistorique(
                    "Modification du retard de l'étudiant {$retard->etudiant->prenom} {$retard->etudiant->nom} pour le cours de {$retard->cours->matiere->nom}", 
                    "edit"
                );
                
                $this->dispatch('updated');
            } else {
                $retard = Retard::create($data);
                
                // Ajout de l'historique pour la création
                $this->outil->addHistorique(
                    "Enregistrement d'un retard de {$this->minutes_retard} minutes pour l'étudiant {$retard->etudiant->prenom} {$retard->etudiant->nom} en {$retard->cours->matiere->nom}", 
                    "add"
                );
                
                $this->dispatch('added');
            }

            $this->closeModal();
            $this->loadRetards();
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Une erreur est survenue lors de l\'enregistrement']);
        }
    }

    public function mount()
    {
        $this->date = now()->format('Y-m-d\TH:i');
        $this->academicYear = Auth::user()->campus->currentAcademicYear();
        $this->outil = new Outils();
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        if (!Auth::user()->hasPermission('retards', 'view')) {
            return view('livewire.unauthorized', [
                'message' => 'Vous n\'avez pas la permission de voir les retards'
            ]);
        }

        return view('livewire.retard.retards', [
            'years' => Auth::user()->campus->academicYears,
            'cours' => Auth::user()->campus->cours->where('academic_year_id', $this->academicYear?->id ?? null)
        ]);
    }
}
