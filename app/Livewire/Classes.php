<?php

namespace App\Livewire;

use App\Models\Classe;
use App\Models\Filiere;
use App\Models\NiveauEtude;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title("Classes")]
class Classes extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $isOpen = false;
    public $modalTitle = "Ajouter une classe";
    public $classeId;
    public $nom;
    public $filiere_id;
    public $search = '';
    public $perPage = 10;

    protected $rules = [
        'nom' => 'required|string|max:255',
        'filiere_id' => 'required|exists:filieres,id',
    ];

    public function openModal()
    {
        $this->reset(['classeId', 'nom', 'filiere_id']);
        $this->modalTitle = "Ajouter une classe";
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function save()
    {
        $this->validate();

        Classe::updateOrCreate(
            ['id' => $this->classeId],
            [
                'nom' => ucfirst($this->nom),
                'filiere_id' => $this->filiere_id,
                'campus_id' => Auth::user()->campus_id
            ]
        );

        $this->dispatch('saved');
        $this->closeModal();
    }

    public function edit($id)
    {
        $classe = Classe::findOrFail($id);
        $this->classeId = $classe->id;
        $this->nom = $classe->nom;
        $this->filiere_id = $classe->filiere_id;
        $this->modalTitle = "Modifier la classe";
        $this->isOpen = true;
    }

    public function delete($id)
    {
        Classe::findOrFail($id)->delete();
        $this->dispatch('deleted');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function changePerPage($value)
    {
        $this->perPage = $value;
        $this->resetPage();
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        $classes = Classe::where('campus_id', Auth::user()->campus_id)
            ->when($this->search, function ($query) {
                $query->where('nom', 'like', '%' . $this->search . '%')
                    ->orWhereHas('filiere', function ($q) {
                        $q->where('nom', 'like', '%' . $this->search . '%');
                    });
            })
            ->with(['filiere'])
            ->orderBy('nom')
            ->paginate($this->perPage);

        $filieres = Filiere::where('campus_id', Auth::user()->campus_id)->get();

        return view('livewire.classes', [
            'classes' => $classes,
            'filieres' => $filieres,
        ]);
    }
}
