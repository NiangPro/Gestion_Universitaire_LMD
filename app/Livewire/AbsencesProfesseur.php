<?php

namespace App\Livewire;

use App\Models\Absence;
use App\Models\Classe;
use App\Models\Cour;
use App\Models\Outils;
use App\Models\Semestre;
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

    public function updatedDate()
    {
        $this->selectedClasse = null;
        $this->etudiants = [];
        $this->loadClassesOfDay();
    }

    public function loadClassesOfDay()
    {
        $this->loading = true;
        $selectedDate = Carbon::parse($this->date);
        $jourSemaine = $selectedDate->dayOfWeek;
        
        $this->classes = Cour::where('professeur_id', Auth::id())
            ->where('semaine_id', $jourSemaine)
            ->whereHas('classe')
            ->with(['classe.filiere', 'matiere'])
            ->get()
            ->map(function($cours) {
                $effectif = $cours->classe->etudiants()
                    ->where('inscriptions.academic_year_id', Auth::user()->campus->currentAcademicYear()->id)
                    ->count();
                return [
                    'id' => $cours->classe_id,
                    'nom' => $cours->classe->nom,
                    'filiere' => $cours->classe->filiere->nom,
                    'matiere' => $cours->matiere->nom,
                    'heure_debut' => $cours->heure_debut,
                    'heure_fin' => $cours->heure_fin,
                    'cours_id' => $cours->id,
                    'effectif' => $effectif
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
            $absence = Absence::where('etudiant_id', $etudiant['id'])
                ->where('cours_id', $this->cours)
                ->where('date', $this->date)
                ->first();
            
            if ($absence) {
                $absence->delete();
                $outils = new Outils();
                $outils->addHistorique("Suppression d'une absence", "delete");
                
                $this->dispatch('alert', [
                    'type' => 'warning',
                    'message' => 'Absence supprimée avec succès'
                ]);
            }
        }
    }

    public function saveAbsences()
    {
        $this->validate([
            'date' => 'required|date',
            'cours' => 'required|exists:cours,id'
        ]);

        $cours = Cour::with(['matiere.uniteEnseignement.filiere'])->find($this->cours);
       

        foreach ($this->etudiants as $etudiant) {
            if ($etudiant['absent']) {
                Absence::create([
                    'etudiant_id' => $etudiant['id'],
                    'cours_id' => $this->cours,
                    'date' => $this->date,
                    'status' => 'absent',
                    'campus_id' => Auth::user()->campus->id,
                    'academic_year_id' => Auth::user()->campus->currentAcademicYear()->id,
                    'semestre_id' => Auth::user()->campus->currentSemestre()->id,
                    'created_by' => Auth::id()
                ]);
            }
        }

        $outils = new Outils();
        $outils->addHistorique("Enregistrement des absences", "create");
        
        $this->success = true;
        $this->message = 'Les absences ont été enregistrées avec succès.';

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Les absences ont été enregistrées avec succès'
        ]);
    }

    public function resetAbsences()
    {
        $this->reset("etudiants");

        $this->dispatch('alert', [
            'type' => 'info',
            'message' => 'Les absences ont été réinitialisées'
        ]);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.professeur.absences-professeur');
    }
}
