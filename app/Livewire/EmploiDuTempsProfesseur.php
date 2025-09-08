<?php

namespace App\Livewire;

use App\Models\AcademicYear;
use App\Models\Cour;
use App\Models\Semaine;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

#[Title('Emploi du temps')]
class EmploiDuTempsProfesseur extends Component
{
    #[Layout('components.layouts.app')]
    
    public $title = "Emploi du temps";
    public $layout = "layouts.app";
    
    public $emploiDuTemps = [];
    public $currentAcademicYear;
    public $user;
    public $semaines = [];
    public $jours = [];
    public $heures = [];
    public $selectedSemaine = "";
    
    public function mount()
    {
        $this->user = Auth::user();
        
        // Récupérer l'année académique en cours
        $this->currentAcademicYear = AcademicYear::where('encours', true)
            ->where('campus_id', $this->user->campus_id)
            ->first();
            
        if (!$this->currentAcademicYear) {
            // Si aucune année académique n'est marquée comme en cours, prendre la plus récente
            $this->currentAcademicYear = AcademicYear::where('campus_id', $this->user->campus_id)
                ->orderBy('debut', 'desc')
                ->first();
        }
        
        // Récupérer toutes les semaines (jours de la semaine)
        $this->semaines = Semaine::orderBy('id')->get();
        
        // Définir les jours de la semaine dans l'ordre
        $this->jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        
        // Générer les heures pour l'emploi du temps
        $this->generateHeures();
        
        // Charger l'emploi du temps
        $this->loadEmploiDuTemps();
    }
    
    public function loadEmploiDuTemps()
    {
        $this->emploiDuTemps = [];
        
        if (!$this->currentAcademicYear) {
            return;
        }
        
        try {
            // Construire la requête de base pour les cours du professeur
            $query = Cour::where('professeur_id', $this->user->id)
                ->where('academic_year_id', $this->currentAcademicYear->id)
                ->whereIn('statut', ['actif', 'encours'])
                ->with(['classe', 'matiere', 'salle', 'semaine'])
                ->orderBy('heure_debut');
            
            // Filtrer par semaine si sélectionnée
            if (!empty($this->selectedSemaine)) {
                $query->where('semaine_id', $this->selectedSemaine);
            }
            
            // Récupérer les cours
            $cours = $query->get();
            
            // Organiser les cours par jour
            foreach ($cours as $cour) {
                $jour = $cour->semaine->jour;
                if (!isset($this->emploiDuTemps[$jour])) {
                    $this->emploiDuTemps[$jour] = [];
                }
                $this->emploiDuTemps[$jour][] = $cour;
            }
            
            // Trier les cours par heure de début pour chaque jour
            foreach ($this->emploiDuTemps as $jour => $coursDuJour) {
                usort($this->emploiDuTemps[$jour], function ($a, $b) {
                    return $a->heure_debut <=> $b->heure_debut;
                });
            }
            
            Log::info('Emploi du temps professeur chargé avec succès', [
                'professeur_id' => $this->user->id,
                'academic_year_id' => $this->currentAcademicYear->id,
                'nombre_cours' => count($cours),
                'jours_avec_cours' => array_keys($this->emploiDuTemps)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement de l\'emploi du temps professeur', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    
    public function generateHeures()
    {
        // Générer des créneaux horaires de 8h à 20h par pas de 1h
        $this->heures = [];
        for ($i = 8; $i <= 20; $i++) {
            $this->heures[] = sprintf('%02d:00', $i);
        }
    }
    
    public function updatedSelectedSemaine()
    {
        $this->loadEmploiDuTemps();
    }
    
    public function render()
    {
        return view('livewire.emploi-du-temps-professeur');
    }
}
