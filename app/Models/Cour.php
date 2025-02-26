<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cour extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'professeur_id',
        'academic_year_id',
        'matiere_id',
        'semaine_id',
        'heure_debut',
        'heure_fin',
        'statut',
        'is_deleting',
        'campus_id',
        'classe_id'
    ];

    // public function jours()
    // {
    //     return $this->belongsToMany(Semaine::class, 'cours_semaine', 'cours_id', 'semaine_id');
    // }

    public function semaine()
    {
        return $this->belongsTo(Semaine::class, "semaine_id");
    }

    public function professeur()
    {
        return $this->belongsTo(User::class, "professeur_id");
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class, "matiere_id");
    }

    public function anneeAcademic()
    {
        return $this->belongsTo(AcademicYear::class, "academic_year_id");
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class, "campus_id");
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, "classe_id");
    }
}
