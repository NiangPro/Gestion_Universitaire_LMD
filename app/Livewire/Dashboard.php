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
    public $totalRetards;
    public $totalNotes;
    public $recentActivities = [];
    public $showEmploiModal = false;
    public $showNotesModal = false;
    public $emploiDuTemps;
    public $moyennesParMatiere = [];
    public $totalEtudiants;
    public $currentClasse;
    public $moyenneGenerale;

    public function mount()
    {
        $this->user = Auth::user();
        $this->currentAcademicYear = AcademicYear::getCurrentAcademicYear($this->user->campus_id);
        
        if ($this->user->estProfesseur()) {
            $this->loadProfesseurData();
        } else if ($this->user->estEtudiant()) {
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

    public function toggleEmploiModal()
    {
        $this->showEmploiModal = !$this->showEmploiModal;
        if ($this->showEmploiModal) {
            $this->loadEmploiDuTemps();
        }
    }

    public function toggleNotesModal()
    {
        $this->showNotesModal = !$this->showNotesModal;
        if ($this->showNotesModal) {
            $this->loadNotes();
        }
    }

    private function loadEmploiDuTemps()
    {
        if ($this->currentClasse) {
            $this->emploiDuTemps = Cour::where('classe_id', $this->currentClasse->id)
                ->where('academic_year_id', $this->currentAcademicYear->id)
                ->with(['professeur', 'matiere', 'salle', 'semaine'])
                ->orderBy('semaine_id')
                ->orderBy('heure_debut')
                ->get()
                ->groupBy('semaine.nom');
        }
    }

    private function loadNotes()
    {
        if ($this->user->estEtudiant()) {
            $notes = Note::where('etudiant_id', $this->user->id)
                ->where('academic_year_id', $this->currentAcademicYear->id)
                ->with(['cours.matiere'])
                ->get();

            // Grouper les notes par matière et calculer les moyennes
            $this->moyennesParMatiere = $notes->groupBy('cours.matiere.nom')
                ->map(function ($notesMatiere) {
                    return [
                        'notes' => $notesMatiere,
                        'moyenne' => round($notesMatiere->avg('note'), 2)
                    ];
                });
        }
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.dashboard');
    }
}
