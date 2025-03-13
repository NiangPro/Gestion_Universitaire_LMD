<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Cour;
use App\Models\Note;
use App\Models\Absence;
use App\Models\Retard;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class Dashboard extends Component
{
    public $user;
    public $currentAcademicYear;
    public $totalCours;
    public $totalAbsences;
    public $totalEtudiants;
    public $totalRetards;
    public $totalNotes;
    public $recentActivities = []	;
    public $currentClasse;
    public $moyenneGenerale;

    public function mount()
    {
        $this->user = Auth::user();
        $this->currentAcademicYear = AcademicYear::getCurrentAcademicYear($this->user->campus_id);
        
        if ($this->user->estProfesseur()) {
            $this->loadProfesseurData();
        } else if ($this->user->estEleve()) {
            $this->loadEleveData();
        }
    }

    private function loadProfesseurData()
    {
        // Récupération des cours du professeur pour l'année en cours
        $this->totalCours = Cour::where('professeur_id', $this->user->id)
            ->where('academic_year_id', $this->currentAcademicYear->id)
            ->count();

        // Nombre total d'élèves
        $this->totalEtudiants = User::whereHas('classes', function($query) {
            $query->whereHas('cours', function($q) {
                $q->where('professeur_id', $this->user->id)
                    ->where('academic_year_id', $this->currentAcademicYear->id);
            });
        })->where('role', 'eleve')->count();

        // Dernières activités (notes, absences)
        $this->recentActivities = Note::where('academic_year_id', $this->currentAcademicYear->id)
            ->whereHas('cours', function($query) {
                $query->where('professeur_id', $this->user->id);
            })
            ->with(['etudiant', 'cours'])
            ->latest()
            ->take(5)
            ->get();
    }

    private function loadEleveData()
    {
        // Récupération de la classe actuelle de l'élève
        $this->currentClasse = $this->user->classes()
            ->wherePivot('academic_year_id', $this->currentAcademicYear->id)
            ->first();

        if ($this->currentClasse) {
            // Total des cours de sa classe
            $this->totalCours = Cour::where('classe_id', $this->currentClasse->id)
                ->where('academic_year_id', $this->currentAcademicYear->id)
                ->count();

            // Absences
            $this->totalAbsences = Absence::where('etudiant_id', $this->user->id)
                ->where('academic_year_id', $this->currentAcademicYear->id)
                ->where('status', 'absent')
                ->count();

            // Retards
            $this->totalRetards = Retard::where('etudiant_id', $this->user->id)
                ->where('academic_year_id', $this->currentAcademicYear->id)
                ->count();

            // Notes récentes
            $this->recentActivities = Note::where('etudiant_id', $this->user->id)
                ->where('academic_year_id', $this->currentAcademicYear->id)
                ->with(['cours'])
                ->latest()
                ->take(5)
                ->get();

            // Calcul de la moyenne générale
            $notes = Note::where('etudiant_id', $this->user->id)
                ->where('academic_year_id', $this->currentAcademicYear->id)
                ->get();

            if ($notes->count() > 0) {
                $this->moyenneGenerale = round($notes->avg('note'), 2);
            }
        }
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.dashboard');
    }
}
