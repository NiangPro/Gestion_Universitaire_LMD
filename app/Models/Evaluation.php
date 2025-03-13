<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'type', // 'devoir', 'examen', 'controle', etc.
        'date',
        'heure_debut',
        'heure_fin',
        'coefficient',
        'cours_id',
        'campus_id',
        'academic_year_id',
        'created_by',
        'status', // 'planifie', 'en_cours', 'termine'
        'description',
        'salle_id'
    ];

    protected $casts = [
        'date' => 'datetime',
        'coefficient' => 'float'
    ];

    public function cours()
    {
        return $this->belongsTo(Cour::class, 'cours_id');
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}