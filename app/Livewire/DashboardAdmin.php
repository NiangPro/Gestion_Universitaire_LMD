<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Classe;
use App\Models\Cour;
use App\Models\AcademicYear;
use App\Models\Inscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardAdmin extends Component
{
    public $user;
    public $currentAcademicYear;
    public $totalEtudiants;
    public $totalProfesseurs;
    public $totalClasses;
    public $totalCours;
    public $inscriptionsRecentes;
    public $tauxPresence;
    public $totalInscriptions;
    public $montantTotal;

    public function mount()
    {
        $this->user = Auth::user();
        $this->currentAcademicYear = AcademicYear::getCurrentAcademicYear($this->user->campus_id);
        $this->loadStatistiques();
        $this->loadInscriptionsRecentes();
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
            ->where('academic_year_id', $this->currentAcademicYear->id)
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

    public function render()
    {
        return view('livewire.dashboard-admin');
    }
}