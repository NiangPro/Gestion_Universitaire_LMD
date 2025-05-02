<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    use HasFactory;

    protected $table = "campuses";

    protected $fillable = [
        "nom",
        "tel",
        "adresse",
        "email",
        "image",
        "is_deleting",
        "statut",
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function admins()
    {
        return $this->users()->where('role', 'admin');
    }

    public function etudiants()
    {
        return $this->users()->where('role', 'etudiant');
    }

    public function etudiantsByClasse($classe_id)
    {
        return $this->etudiants()->where('classe_id', $classe_id);
    }

    public function etudiantsByClasseForCurrentAcademicYear($classe_id)
    {
        return $this->currentInscriptions()
            ->where('classe_id', $classe_id)
            ->with('user')
            ->get()
            ->pluck('user');
    }
    public function etudiantsByDepartement($departement_id)
    {
        return $this->etudiants()->where('departement_id', $departement_id);
    }
    public function etudiantsByDepartementAndClasse($departement_id, $classe_id)
    {
        return $this->etudiants()->where('departement_id', $departement_id)->where('classe_id', $classe_id);
    }

    public function professeurs()
    {
        return $this->users()->where('role', 'professeur');
    }

    public function parents()
    {
        return $this->users()->where('role', 'parent');
    }


    public function academicYears()
    {
        return $this->hasMany(AcademicYear::class);
    }

    public function currentAcademicYear()
    {
        return $this->academicYears()->where('encours', true)->first();
    }

    public function departements()
    {
        return $this->hasMany(Departement::class);
    }

    public function filieres()
    {
        return $this->hasMany(Filiere::class);
    }

    public function typeEvaluations()
    {
        return $this->hasMany(TypeEvaluation::class);
    }

    public function packs()
    {
        return $this->belongsToMany(Pack::class, 'subscriptions')
            ->withPivot(['start_date', 'end_date', 'status', 'payment_status', 'amount_paid', 'payment_method', 'payment_reference'])
            ->withTimestamps();
    }
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
    public function currentEvaluations()
    {
        return $this->evaluations()->where('academic_year_id', $this->currentAcademicYear()->id);
    }
    public function currentEvaluationsBySemestre($semestre_id)
    {
        return $this->currentEvaluations()->where('semestre_id', $semestre_id);
    }
    public function currentEvaluationsByMatiere($matiere_id)
    {
        return $this->currentEvaluations()->where('matiere_id', $matiere_id);
    }
    public function currentEvaluationsByEtudiant($etudiant_id)
    {
        return $this->currentEvaluations()->where('etudiant_id', $etudiant_id);
    }
    public function currentEvaluationsByEtudiantAndMatiere($etudiant_id, $matiere_id)
    {
        return $this->currentEvaluations()->where('etudiant_id', $etudiant_id)->where('matiere_id', $matiere_id);
    }
    public function currentEvaluationsBySemestreAndMatiere($semestre_id, $matiere_id)
    {
        return $this->currentEvaluations()->where('semestre_id', $semestre_id)->where('matiere_id', $matiere_id);
    }

    public function uniteEnseignements()
    {
        return $this->hasMany(UniteEnseignement::class);
    }

    public function matieres()
    {
        return $this->hasMany(Matiere::class);
    }

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }

    public function historiques()
    {
        return $this->hasMany(Historique::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('payment_status', 'paid')
            ->where('end_date', '>', now())
            ->latest()
            ->first();
    }

    public function hasActiveSubscription()
    {
        return $this->activeSubscription() !== null;
    }

    public function pack()
    {
        return $this->hasOneThrough(
            Pack::class,
            Subscription::class,
            'campus_id',
            'id',
            'id',
            'pack_id'
        )
        ->where('subscriptions.status', 'active')
        ->where('subscriptions.payment_status', 'paid')
        ->where('subscriptions.end_date', '>', now())
        ->orderBy('subscriptions.created_at', 'desc')
        ->select('packs.*')
        ->first();
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function cours()
    {
        return $this->hasMany(Cour::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function currentNotes()
    {
        return $this->notes()->where('academic_year_id', $this->currentAcademicYear()->id);
    }

    public function currentNotesBySemestre($semestre_id)
    {
        return $this->currentNotes()->where('semestre_id', $semestre_id);
    }

    public function currentNotesByEtudiant($etudiant_id)
    {
        return $this->currentNotes()->where('etudiant_id', $etudiant_id);
    }

    public function currentNotesByMatiere($matiere_id)
    {
        return $this->currentNotes()->where('matiere_id', $matiere_id);
    }

    public function currentNotesByEtudiantAndMatiere($etudiant_id, $matiere_id)
    {
        return $this->currentNotes()->where('etudiant_id', $etudiant_id)->where('matiere_id', $matiere_id);
    }

    public function currentNotesByEtudiantAndSemestre($etudiant_id, $semestre_id)
    {
        return $this->currentNotes()->where('etudiant_id', $etudiant_id)->where('semestre_id', $semestre_id);
    }

    public function currentNotesBySemestreAndMatiere($semestre_id, $matiere_id)
    {
        return $this->currentNotes()->where('semestre_id', $semestre_id)->where('matiere_id', $matiere_id);
    }
    
    public function currentInscriptions()
    {
        return $this->inscriptions()->where('academic_year_id', $this->currentAcademicYear()->id);
    }

    public function semestres()
    {
        return $this->hasMany(Semestre::class);
    }

    public function currentSemestre()
    {
        return $this->semestres()->where('is_active', true)->first();
    }
    
}
