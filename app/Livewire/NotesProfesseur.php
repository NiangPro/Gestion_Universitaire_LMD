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
    public $noteTemp = [];

    // Propriétés pour la visualisation
    public $selectedClasseVisu = null;
    public $selectedMatiereVisu = null;
    public $selectedTypeEvaluationVisu = null;
    public $matieresVisu = [];
    public $notesVisu = [];
    public $moyenneGenerale = 0;
    public $meilleureNote = 0;
    public $tauxReussite = 0;

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

            $currentSemestre = Auth::user()->campus->currentSemestre();
            if ($currentSemestre) {
                $this->notes = Note::where('matiere_id', $this->selectedMatiere)
                    ->where('type_evaluation_id', $this->selectedTypeEvaluation)
                    ->whereIn('etudiant_id', $this->etudiants->pluck('id'))
                    ->where('academic_year_id', Auth::user()->campus->currentAcademicYear()->id)
                    ->where('semestre_id', $currentSemestre->id)
                    ->get()
                    ->keyBy('etudiant_id');

                // Pré-remplir noteTemp avec les notes existantes
                foreach ($this->notes as $etudiantId => $note) {
                    $this->noteTemp[$etudiantId] = $note->note;
                }
            }
        }
    }

    public function sauvegarderNotes($etudiantId, $note)
    {
        $note = str_replace(',', '.', trim($note));
        if (!isset($this->noteTemp[$etudiantId]) || !is_numeric($this->noteTemp[$etudiantId])) {
            $this->message = 'Veuillez saisir une note valide';
            return;
        }

        $note = $this->noteTemp[$etudiantId];

        // Nettoyage et formatage de la note
        
        // Vérification que la valeur est numérique
        if (!is_numeric($note)) {
            $this->message = 'La note doit être un nombre valide';
            return;
        }

        // Conversion en nombre à virgule flottante et arrondi à 2 décimales
        $note = round(floatval($note), 2);

        if ($note < 0 || $note > 20) {
            $this->message = 'La note doit être comprise entre 0 et 20';
            return;
        }

        if (!$this->selectedTypeEvaluation) {
            $this->message = 'Veuillez sélectionner un type d\'évaluation';
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
        $outils = new Outils();
        $outils->addHistorique(
            $action . ' de note',
            'Note ' . $action . ' pour l\'étudiant ' . $etudiant->prenom . ' ' . $etudiant->nom . ' (Matricule: ' . $etudiant->username . ') - Note: ' . $note,
            Auth::user()->id
        );

        $this->message = 'Note enregistrée avec succès';
    }

    #[Layout('components.layouts.app')]
    public function updatedSelectedClasseVisu($value)
    {
        $this->selectedMatiereVisu = null;
        $this->notesVisu = [];
        $this->resetStats();
        
        if ($value) {
            $user = Auth::user();
            $this->matieresVisu = Cour::where('professeur_id', $user->id)
                ->where('classe_id', $value)
                ->where('academic_year_id', $user->campus->currentAcademicYear()->id)
                ->with('matiere')
                ->get()
                ->pluck('matiere')
                ->unique('id');
        }
    }

    public function updatedSelectedMatiereVisu($value)
    {
        if ($value) {
            $this->chargerNotesVisu();
        }
    }

    public function updatedSelectedTypeEvaluationVisu($value)
    {
        if ($value) {
            $this->chargerNotesVisu();
        }
    }

    public function chargerNotesVisu()
    {
        if ($this->selectedClasseVisu && $this->selectedMatiereVisu && $this->selectedTypeEvaluationVisu) {
            $this->notesVisu = Note::with('etudiant')
                ->where('matiere_id', $this->selectedMatiereVisu)
                ->where('type_evaluation_id', $this->selectedTypeEvaluationVisu)
                ->where('academic_year_id', Auth::user()->campus->currentAcademicYear()->id)
                ->get();

            $this->calculerStatistiques();
        }
    }

    private function calculerStatistiques()
    {
        if ($this->notesVisu->isEmpty()) {
            $this->resetStats();
            return;
        }

        $this->moyenneGenerale = $this->notesVisu->avg('note');
        $this->meilleureNote = $this->notesVisu->max('note');
        $notesValidees = $this->notesVisu->filter(function($note) {
            return $note->note >= 10;
        })->count();
        $this->tauxReussite = ($notesValidees / $this->notesVisu->count()) * 100;
    }

    private function resetStats()
    {
        $this->moyenneGenerale = 0;
        $this->meilleureNote = 0;
        $this->tauxReussite = 0;
    }

    public function render()
    {
        return view('livewire.professeur.notes-professeur');
    }
}
