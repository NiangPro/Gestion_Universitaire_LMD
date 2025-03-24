<?php

namespace App\Livewire;

use App\Models\Absence;
use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\Cour;
use App\Models\Cours;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title("Absences")]
class Absences extends Component
{
    use WithPagination;

    public $isOpen = false;
    public $selectedCampus = null;
    public $selectedYear = null;
    public $selectedClasse = null;
    public $classes = [];
    public $search = '';
    public $academicYear = null;
    public $inscriptions = [];
    public $absences = [];
    
    // Formulaire
    public $etudiant_id;
    public $cours_id;
    public $date;
    public $status = 'absent';
    public $motif;
    public $justifie = false;
    public $commentaire;
    
    // Pour l'édition
    public $absence_id;
    public $isEditing = false;

    protected $rules = [
        'etudiant_id' => 'required',
        'cours_id' => 'required',
        'date' => 'required|date',
        'status' => 'required|in:absent,present',
        'motif' => 'nullable|string',
        'justifie' => 'boolean',
        'commentaire' => 'nullable|string'
    ];

    public function updatedCoursId($value)
    {
        $classe = Cour::find($value)->classe;
        $this->inscriptions = Auth::user()->campus->currentInscriptions()->where('classe_id', $classe->id)->get();
    }

    public function updatedSelectedYear($value)
    {
        $this->classes = Auth::user()->campus->classes;
        // $this->absences = Absence::where('academic_year_id', $value)->get();
    }

    public function updatedSelectedClasse($value)
    {
        $this->absences = Absence::where('academic_year_id', $this->selectedYear)
            ->whereHas('etudiant', function($query) use ($value) {
                $query->whereHas('inscriptions', function($query) use ($value) {
                    $query->where('classe_id', $value);
                });
            })->get();
    }

    public function updatedSearch($value)
    {
        $this->absences = Absence::where('academic_year_id', $this->selectedYear)
            ->where(function($query) use ($value) {
                $query->whereHas('etudiant', function($query) use ($value) {
                    $query->where('nom', 'like', '%' . $value . '%')
                          ->orWhere('prenom', 'like', '%' . $value . '%');
                })->orWhereHas('cours', function($query) use ($value) {
                    $query->whereHas('matiere', function($query) use ($value) {
                        $query->where('nom', 'like', '%' . $value . '%');
                    });
                });
            })->get();
    }

    public function showAddAbsenceModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function mount()
    {
        $this->date = now()->format('Y-m-d\TH:i');
        $this->academicYear = Auth::user()->campus->currentAcademicYear();
    }

    #[Layout("components.layouts.app")]
    public function render()
    {

        return view('livewire.absence.absences', [
            // 'absences' => $query->latest()->paginate(10),
            'years' => Auth::user()->campus->academicYears,
            'etudiants' => Auth::user()->campus->etudiants,
            'cours' => Auth::user()->campus->cours->where('academic_year_id', $this->academicYear->id)
        ]);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'etudiant_id' => $this->etudiant_id,
            'cours_id' => $this->cours_id,
            'date' => $this->date,
            'status' => $this->status,
            'motif' => $this->motif,
            'justifie' => $this->justifie,
            'commentaire' => $this->commentaire,
            'campus_id' => Auth::user()->campus_id,
            'academic_year_id' => Auth::user()->campus->currentAcademicYear()->id,
            'created_by' => Auth::user()->id
        ];

        if ($this->isEditing) {
            Absence::find($this->absence_id)->update($data);
            $this->dispatch('updated');
        } else {
            Absence::create($data);
            $this->dispatch('added');
        }

        $this->reset(['etudiant_id', 'cours_id', 'status', 'motif', 'justifie', 'commentaire', 'isEditing', 'absence_id', 'inscriptions']);
        $this->closeModal();
    }

    public function edit(Absence $absence)
    {
        $this->isEditing = true;
        $this->absence_id = $absence->id;
        $this->etudiant_id = $absence->etudiant_id;
        $this->cours_id = $absence->cours_id;
        $this->date = $absence->date;
        $this->status = $absence->status;
        $this->motif = $absence->motif;
        $this->justifie = $absence->justifie;
        $this->commentaire = $absence->commentaire;
    }

    public function delete(Absence $absence)
    {
        $absence->delete();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Absence supprimée avec succès']);
    }
}
