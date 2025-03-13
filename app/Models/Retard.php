<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retard extends Model
{
    use HasFactory;

    protected $fillable = [
        'etudiant_id',
        'cours_id',
        'date',
        'minutes_retard',
        'motif',
        'justifie',
        'commentaire',
        'campus_id',
        'academic_year_id',
        'created_by'
    ];

    protected $casts = [
        'date' => 'datetime',
        'justifie' => 'boolean',
        'minutes_retard' => 'integer'
    ];

    public function etudiant()
    {
        return $this->belongsTo(User::class, 'etudiant_id');
    }

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
}