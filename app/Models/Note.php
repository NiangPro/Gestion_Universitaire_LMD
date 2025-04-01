<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    protected $fillable = [
        'matiere_id',
        'etudiant_id',
        'academic_year_id',
        'type_evaluation',  // (CC, TP, Examen)
        'note',
        'observation',
        'campus_id',
        'coefficient_id',
        'semestre_id'
    ];

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function etudiant()
    {
        return $this->belongsTo(User::class, 'etudiant_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function coefficient()
    {
        return $this->belongsTo(Coefficient::class);
    }

   
}
