<?php

namespace App\Livewire;

use App\Models\Classe;
use App\Models\Cour;
use App\Models\Note;
use App\Models\User;
use App\Models\Outils;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Notes")]
class NotesProfesseur extends Component
{
    public $selectedClasse = null;
    public $selectedMatiere = null;
    public $selectedTypeEvaluation = null;
    public $notes = [];
    public $etudiants = null;
    public $classes = [];
    public $matieres = [];
    public $typesEvaluation = [];
    public $message = '';

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->estProfesseur()) {
            $this->classes = $user->cours()
                ->with('classe')
                ->where('academic_year_id', $user->campus->currentAcademicYear()->id)
                ->get()
                ->pluck('classe')
                ->unique('id');
                $this->typesEvaluation = Auth::user()->campus->typeEvaluations;

        }
    }

    public function updatedSelectedClasse($value)
    {
        $this->selectedMatiere = null;
        $this->etudiants = [];
        $this->notes = [];
        
        if ($value) {
            $user = Auth::user();
            $this->matieres = Cour::where('professeur_id', $user->id)
                ->where('classe_id', $value)
                ->where('academic_year_id', $user->campus->currentAcademicYear()->id)
                ->with('matiere')
                ->get()
                ->pluck('matiere')
                ->unique('id');
        }
    }

    public function chargerEtudiants()
    {
        if ($this->selectedClasse && $this->selectedMatiere && $this->selectedTypeEvaluation) {
            $classe = Classe::find($this->selectedClasse);
            $this->etudiants = $classe->etudiants()
                ->where('academic_year_id', Auth::user()->campus->currentAcademicYear()->id)
                ->get();

            $this->notes = Note::where('matiere_id', $this->selectedMatiere)
                ->where('type_evaluation', $this->selectedTypeEvaluation)
                ->whereIn('etudiant_id', $this->etudiants->pluck('id'))
                ->where('academic_year_id', Auth::user()->campus->currentAcademicYear()->id)
                ->get()
                ->keyBy('etudiant_id');
        }
    }

    public function sauvegarderNotes($etudiantId, $note)
    {
        if (!is_numeric($note) || $note < 0 || $note > 20) {
            $this->message = 'La note doit être comprise entre 0 et 20';
            return;
        }

        $currentSemestre = Auth::user()->campus->currentSemestre();
        if (!$currentSemestre) {
            $this->message = 'Aucun semestre actif trouvé';
            return;
        }

        $noteModel = Note::updateOrCreate(
            [
                'matiere_id' => $this->selectedMatiere,
                'etudiant_id' => $etudiantId,
                'type_evaluation_id' => $this->selectedTypeEvaluation,
                'academic_year_id' => Auth::user()->campus->currentAcademicYear()->id,
                'campus_id' => Auth::user()->campus_id,
                'semestre_id' => $currentSemestre->id,
            ],
            [
                'note' => $note
            ]
        );

        // Enregistrer l'historique
        $action = $noteModel->wasRecentlyCreated ? 'Ajout' : 'Modification';
        $etudiant = User::find($etudiantId);
        Outils::historique(
            $action . ' de note',
            'Note ' . $action . ' pour l\'étudiant ' . $etudiant->prenom . ' ' . $etudiant->nom . ' (Matricule: ' . $etudiant->username . ') - Note: ' . $note,
            Auth::user()->id
        );

        $this->message = 'Note enregistrée avec succès';
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.note.notes-professeur');
    }
}
