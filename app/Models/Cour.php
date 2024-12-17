<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cour extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre', 'description', 'professeur_id', 
        'academic_year_id', 'matiere_id', 
        'heure_debut', 'heure_fin', 'statut','is_deleting'
    ];

    public function jours()
    {
        return $this->belongsToMany(Semaine::class, 'cours_semaine', 'cours_id', 'semaine_id');
    }
}
