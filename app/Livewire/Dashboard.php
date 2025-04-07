<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Cour;
use App\Models\Note;
use App\Models\Absence;
use App\Models\Retard;
use App\Models\AcademicYear;
use App\Models\Classe;
use App\Models\Inscription;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title("Tableau de bord")]
class Dashboard extends Component
{
    public $user;
    public $currentAcademicYear;
    public $totalCours = 0;
    public $totalAbsences = 0;
    public $totalRetards = 0;
    public $totalNotes = 0;
    public $recentActivities = [];
    public $showEmploiModal = false;
    public $showNotesModal = false;
    public $emploiDuTemps;
    public $moyennesParMatiere = [];
    public $totalEtudiants = 0;
    public $currentClasse;
    public $moyenneGenerale;
    public $totalProfesseurs = 0;
    public $totalClasses = 0;
    public $inscriptionsRecentes = [];
    public $totalInscriptions = 0;
    public $montantTotal = 0;
    public $coursAujourdhui = [];

    public function mount()
    {
        $this->user = Auth::user();
        $campus = Auth::user()->campus;
        
        // Vérifie si l'utilisateur n'est pas superadmin et qu'il n'y a pas d'année académique active
        if (!$this->user->estSuperAdmin() && !$campus->currentAcademicYear()) {
            return;
        }

        if (!$this->user->estSuperAdmin()) {
            $currentAcademicYear = $campus->currentAcademicYear();
            $this->currentAcademicYear = $currentAcademicYear;
            // Calcul du nombre d'étudiants pour l'année académique en cours
            $this->totalEtudiants = User::where('campus_id', $campus->id)
            ->where('role', 'etudiant')
            ->whereHas('inscriptions', function($query) use ($currentAcademicYear) {
                $query->where('academic_year_id', $currentAcademicYear->id)
                    ->where('status', 'en_cours');
            })
            ->count();
        }
        
        if ($this->user->estProfesseur()) {
            $this->loadProfesseurData();
        }else if($this->user->estAdmin()){
            if ($this->currentAcademicYear) {
                $this->loadStatistiques();
                $this->loadInscriptionsRecentes();
                $this->loadCoursAujourdhui();
            }
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
        // $this->totalEtudiants = User::whereHas('classes', function($query) {
        //     $query->whereHas('cours', function($q) {
        //         $q->where('professeur_id', $this->user->id)
        //             ->where('academic_year_id', $this->currentAcademicYear->id);
        //     });
        // })->where('role', 'etudiant')->count();

        $this->totalEtudiants = Auth::user()->campus->etudiants->count();

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

    private function loadStatistiques()
    {
        // Statistiques des étudiants
        $this->totalEtudiants = User::where('role', 'eleve')
            ->where('campus_id', $this->user->campus_id)
            ->count();

        // Statistiques des professeurs
        $this->totalProfesseurs = User::where('role', 'professeur')
            ->where('campus_id', $this->user->campus_id)
            ->count();

        // Statistiques des classes
        $this->totalClasses = Classe::where('campus_id', $this->user->campus_id)
            // ->where('academic_year_id', $this->currentAcademicYear->id)
            ->count();

        // Statistiques des cours
        $this->totalCours = Cour::where('campus_id', $this->user->campus_id)
            ->where('academic_year_id', $this->currentAcademicYear->id)
            ->count();

        // Statistiques des inscriptions et montants
        $inscriptions = Inscription::where('campus_id', $this->user->campus_id)
            ->where('academic_year_id', $this->currentAcademicYear->id)
            ->get();

        $this->totalInscriptions = $inscriptions->count();
        $this->montantTotal = $inscriptions->sum('montant');
    }

    private function loadInscriptionsRecentes()
    {
        $this->inscriptionsRecentes = Inscription::where('campus_id', $this->user->campus_id)
            ->where('academic_year_id', $this->currentAcademicYear->id)
            ->with(['etudiant', 'classe'])
            ->latest()
            ->take(5)
            ->get();
    }

    public function loadCoursAujourdhui()
    {
        $campus = Auth::user()->campus;
        $currentAcademicYear = $campus->currentAcademicYear();
        
        if ($currentAcademicYear) {
            $jourSemaine = now()->format('l'); // Récupère le jour actuel en anglais (Monday, Tuesday, etc.)
            
            // Convertir en français
            $joursFr = [
                'Monday' => 'Lundi',
                'Tuesday' => 'Mardi',
                'Wednesday' => 'Mercredi',
                'Thursday' => 'Jeudi',
                'Friday' => 'Vendredi',
                'Saturday' => 'Samedi',
                'Sunday' => 'Dimanche'
            ];
            
            $jourFr = $joursFr[$jourSemaine];

            $this->coursAujourdhui = Cour::where('campus_id', $campus->id)
                ->where('academic_year_id', $currentAcademicYear->id)
                ->whereHas('semaine', function($query) use ($jourFr) {
                    $query->where('jour', $jourFr);
                })
                ->with(['classe', 'professeur', 'matiere', 'salle', 'semaine'])
                ->orderBy('heure_debut')
                ->get();
        }
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        if (Auth::user()->estSuperAdmin()) {
            return view('livewire.dashboard.dashboardSuperAdmin');
        }else if(Auth::user()->estAdmin()){
            return view('livewire.dashboard.dashboard-admin');

        } else {
            return view('livewire.dashboard.dashboard');
        }
        
    }
}
