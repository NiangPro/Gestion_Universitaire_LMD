<?php

namespace App\Livewire;

use App\Models\Retard;
use App\Models\Classe;
use App\Models\Cour;
use App\Models\Outils;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Retards")]
class RetardsProfesseur extends Component
{
    public $classes = [];
    public $selectedClasse = null;
    public $etudiants = [];
    public $date;
    public $cours = null;
    public $loading = false;
    public $success = false;
    public $message = '';
    public $retardModal = false;
    public $selectedEtudiant = null;
    public $minutes_retard = '';
    public $motif = '';
    public $commentaire = '';

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

        $existingRetards = Retard::where('cours_id', $this->cours)
            ->where('date', $this->date)
            ->get()
            ->pluck('minutes_retard', 'etudiant_id')
            ->toArray();

        $this->etudiants = $classe->etudiants()
            ->where('inscriptions.academic_year_id', Auth::user()->campus->currentAcademicYear()->id)
            ->orderBy('users.nom')
            ->get()
            ->map(function($etudiant) use ($existingRetards) {
                return [
                    'id' => $etudiant->id,
                    'nom' => $etudiant->nom,
                    'prenom' => $etudiant->prenom,
                    'matricule' => $etudiant->matricule,
                    'minutes_retard' => $existingRetards[$etudiant->id] ?? null
                ];
            })
            ->toArray();
    }

    public function openRetardModal($etudiantIndex)
    {
        $this->selectedEtudiant = $this->etudiants[$etudiantIndex];
        $this->minutes_retard = '';
        $this->motif = '';
        $this->commentaire = '';
        $this->retardModal = true;
    }

    public function saveRetard()
    {
        $this->validate([
            'minutes_retard' => 'required|integer|min:1',
            'motif' => 'nullable|string|max:255',
            'commentaire' => 'nullable|string'
        ]);

        Retard::updateOrCreate(
            [
                'etudiant_id' => $this->selectedEtudiant['id'],
                'cours_id' => $this->cours,
                'date' => $this->date
            ],
            [
                'minutes_retard' => $this->minutes_retard,
                'motif' => $this->motif,
                'commentaire' => $this->commentaire,
                'campus_id' => Auth::user()->campus->id,
                'academic_year_id' => Auth::user()->campus->currentAcademicYear()->id,
                'semestre_id' => Auth::user()->campus->currentSemestre()->id,
                'created_by' => Auth::id()
            ]
        );

        $outils = new Outils();
        $outils->addHistorique("Enregistrement d'un retard", "create");

        $this->retardModal = false;
        $this->loadEtudiants();

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Le retard a été enregistré avec succès'
        ]);
    }

    public function deleteRetard($etudiantId)
    {
        $retard = Retard::where('etudiant_id', $etudiantId)
            ->where('cours_id', $this->cours)
            ->where('date', $this->date)
            ->first();

        if ($retard) {
            $retard->delete();
            $outils = new Outils();
            $outils->addHistorique("Suppression d'un retard", "delete");

            $this->loadEtudiants();
            
            $this->dispatch('alert', [
                'type' => 'warning',
                'message' => 'Retard supprimé avec succès'
            ]);
        }
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.professeur.retards-professeur');
    }
}
