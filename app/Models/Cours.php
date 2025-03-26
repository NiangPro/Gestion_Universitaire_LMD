<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'unite_enseignement_id',
        'professeur_id',
        'campus_id',
        'academic_year_id',
        'created_by'
    ];

    public function professeur()
    {
        return $this->belongsTo(User::class, 'professeur_id');
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function uniteEnseignement()
    {
        return $this->belongsTo(UniteEnseignement::class);
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function retards()
    {
        return $this->hasMany(Retard::class);
    }
}
