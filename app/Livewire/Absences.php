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

    #[Layout("components.layouts.app")]
    public $selectedCampus = '';
    public $selectedYear = '';
    public $selectedStatus = '';
    public $search = '';
    
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

    public function mount()
    {
        $this->date = now()->format('Y-m-d\TH:i');
    }

    public function render()
    {
        $query = Absence::query()
            ->with(['etudiant', 'cours'])
            ->when($this->selectedCampus, function($q) {
                return $q->where('campus_id', $this->selectedCampus);
            })
            ->when($this->selectedYear, function($q) {
                return $q->where('academic_year_id', $this->selectedYear);
            })
            ->when($this->selectedStatus, function($q) {
                return $q->where('status', $this->selectedStatus);
            })
            ->when($this->search, function($q) {
                return $q->whereHas('etudiant', function($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('cours', function($query) {
                    $query->where('nom', 'like', '%' . $this->search . '%');
                });
            });

        return view('livewire.absences', [
            'absences' => $query->latest()->paginate(10),
            'campus' => Campus::all(),
            'years' => AcademicYear::all(),
            'etudiants' => Auth::user()->campus->etudiants,
            'cours' => Cour::all()
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
            'academic_year_id' => Auth::user()->campus->getCurrentAcademicYear()->id,
            'created_by' => Auth::user()->id
        ];

        if ($this->isEditing) {
            Absence::find($this->absence_id)->update($data);
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Absence modifiée avec succès']);
        } else {
            Absence::create($data);
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Absence enregistrée avec succès']);
        }

        $this->reset(['etudiant_id', 'cours_id', 'status', 'motif', 'justifie', 'commentaire', 'isEditing', 'absence_id']);
        $this->dispatch('close-modal');
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
