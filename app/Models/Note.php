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
        'coefficient',
        'observation',
        'date_evaluation',
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
        return $this->belongsTo(AcademicYear::class);
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }
}
