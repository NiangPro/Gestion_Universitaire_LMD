<?php

namespace App\Livewire;

use App\Models\AcademicYear;
use App\Models\Classe;
use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Bulletins")]
class Bulletin extends Component
{
    public $selectedYear = null;
    public $selectedClasse = null;
    public $selectedStudent = null;
    
    public $academicYears;
    public $classes = [];
    public $students = [];

    public function mount()
    {
        $this->academicYears = AcademicYear::where('campus_id', Auth::user()->campus_id)
            ->orderBy('debut', 'desc')
            ->get();
    }

    public function updatedSelectedYear($value)
    {
        $this->selectedClasse = null;
        $this->selectedStudent = null;
        $this->classes = [];
        $this->students = [];
        
        if ($value) {
            $this->classes = Auth::user()->campus->classes;
            // $this->classes = Classe::where('academic_year_id', $value)
            //     ->where('campus_id', Auth::user()->campus_id)
            //     ->get();
        }
    }

    public function updatedSelectedClasse($value)
    {
        $this->selectedStudent = null;
        $this->students = [];
        
        if ($value) {
            $this->students = User::whereHas('classes', function($query) {
                $query->where('classe_id', $this->selectedClasse)
                      ->where('academic_year_id', $this->selectedYear);
            })->get();
        }
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        $bulletin = null;
        if ($this->selectedStudent) {
            $bulletin = Note::with(['matiere', 'etudiant'])
                ->where('etudiant_id', $this->selectedStudent)
                ->where('academic_year_id', $this->selectedYear)
                ->get()
                ->groupBy('semestre_id');
        }

        return view('livewire.bulletin.bulletin', [
            'bulletin' => $bulletin
        ]);
    }
}
