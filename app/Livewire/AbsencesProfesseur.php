<?php

namespace App\Livewire;

use App\Models\Absence;
use App\Models\Classe;
use App\Models\Cour;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Absences")]
class AbsencesProfesseur extends Component
{
    public $classes = [];
    public $selectedClasse = null;
    public $etudiants = [];
    public $date;
    public $absences = [];
    public $cours = null;
    public $loading = false;
    public $success = false;
    public $message = '';

    public function mount()
    {
        $this->date = Carbon::now()->format('Y-m-d');
        $this->loadClassesOfDay();
    }

    public function loadClassesOfDay()
    {
        $this->loading = true;
        $jourSemaine = Carbon::now()->dayOfWeek;
        
        $this->classes = Cour::where('professeur_id', Auth::id())
            ->where('semaine_id', $jourSemaine)
            ->whereHas('classe')
            ->with(['classe', 'matiere'])
            ->get()
            ->map(function($cours) {
                return [
                    'id' => $cours->classe_id,
                    'nom' => $cours->classe->nom,
                    'matiere' => $cours->matiere->nom,
                    'heure_debut' => $cours->heure_debut,
                    'heure_fin' => $cours->heure_fin,
                    'cours_id' => $cours->id
                ];
            })
            ->unique('id')
            ->values()
            ->toArray();
            
        $this->loading = false;
    }

    public function selectClasse($classeId, $coursId)
    {
        $this->selectedClasse = $classeId;
        $this->cours = $coursId;
        $this->loadEtudiants();
    }

    public function loadEtudiants()
    {
        if (!$this->selectedClasse) return;

        $classe = Classe::find($this->selectedClasse);
        if (!$classe) return;

        $existingAbsences = Absence::where('cours_id', $this->cours)
            ->where('date', $this->date)
            ->pluck('etudiant_id')
            ->toArray();

        $this->etudiants = $classe->etudiants()
            ->where('inscriptions.academic_year_id', Auth::user()->campus->currentAcademicYear()->id)
            ->orderBy('users.nom')
            ->get()
            ->map(function($etudiant) use ($existingAbsences) {
                return [
                    'id' => $etudiant->id,
                    'nom' => $etudiant->nom,
                    'prenom' => $etudiant->prenom,
                    'matricule' => $etudiant->matricule,
                    'absent' => in_array($etudiant->id, $existingAbsences)
                ];
            })
            ->toArray();
    }

    public function toggleAbsence($etudiantIndex)
    {
        $etudiant = $this->etudiants[$etudiantIndex];
        $this->etudiants[$etudiantIndex]['absent'] = !$etudiant['absent'];
        
        if (!$etudiant['absent']) {
            // Supprimer l'absence si elle existe
            Absence::where('etudiant_id', $etudiant['id'])
                ->where('cours_id', $this->cours)
                ->where('date', $this->date)
                ->delete();
        }
    }

    public function saveAbsences()
    {
        $this->validate([
            'date' => 'required|date',
            'cours' => 'required|exists:cours,id'
        ]);

        foreach ($this->etudiants as $etudiant) {
            if ($etudiant['absent']) {
                Absence::create([
                    'etudiant_id' => $etudiant['id'],
                    'cours_id' => $this->cours,
                    'date' => $this->date,
                    'status' => 'absent',
                    'campus_id' => currentCampus(),
                    'academic_year_id' => currentAcademicYear(),
                    'created_by' => Auth::id()
                ]);
            }
        }

        $this->success = true;
        $this->message = 'Les absences ont été enregistrées avec succès.';

        $this->dispatch('show-message', [
            'type' => 'success',
            'message' => $this->message
        ]);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.professeur.absences-professeur');
    }
}
