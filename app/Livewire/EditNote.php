<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Note;
use App\Models\Outils;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title("Modifier la note")]
class EditNote extends Component
{
    public $noteId;
    public $note;
    public $etudiant;
    public $matiere;
    public $type_evaluation;
    public $valeur_note;
    public $observation;
    public $semestre_id;

    public function mount($id)
    {
        $this->noteId = $id;
        $this->loadNote();
    }

    public function loadNote()
    {
        $note = Note::with(['etudiant', 'matiere', 'semestre'])->findOrFail($this->noteId);
        
        $this->note = $note;
        $this->etudiant = $note->etudiant;
        $this->matiere = $note->matiere;
        $this->type_evaluation = $note->type_evaluation;
        $this->valeur_note = $note->note;
        $this->observation = $note->observation;
        $this->semestre_id = $note->semestre_id;
    }

    public function updateNote()
    {
        $this->validate([
            'valeur_note' => 'required|numeric|between:0,20',
            'type_evaluation' => 'required',
            'semestre_id' => 'required'
        ]);

        try {
            $this->note->update([
                'note' => $this->valeur_note,
                'type_evaluation' => $this->type_evaluation,
                'observation' => $this->observation,
                'semestre_id' => $this->semestre_id
            ]);

            // Ajouter à l'historique
            $outil = new Outils();
            $outil->addHistorique(
                "Modification de la note de l'étudiant {$this->etudiant->prenom} {$this->etudiant->nom}",
                "update"
            );

            session()->flash('success', 'Note modifiée avec succès');
            return redirect()->route('notes');

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la modification de la note');
        }
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.note.edit-note');
    }
} 