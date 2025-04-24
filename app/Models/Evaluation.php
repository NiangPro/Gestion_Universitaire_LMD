<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Evaluation extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'date_evaluation',
        'heure_debut',
        'duree',
        'type_evaluation_id',
        'matiere_id',
        'academic_year_id',
        'campus_id',
        'semestre_id',
        'statut'
    ];

    protected $casts = [
        'date_evaluation' => 'date',
        'heure_debut' => 'datetime',
        'duree' => 'integer'
    ];

    public function typeEvaluation()
    {
        return $this->belongsTo(TypeEvaluation::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }


    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classe::class, 'evaluation_classe')
                    ->withTimestamps();
    }

    public function academicYear(){
        return $this->belongsTo(AcademicYear::class, "academic_year_id");
    }

    public function semestre(){
        return $this->belongsTo(Semestre::class, "semestre_id");
    }

    // ... autres relations existantes ...
}
