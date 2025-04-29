<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    use HasFactory;

    protected $table = "matieres";

    protected $fillable = [
        "nom",
        "is_deleting",
        "credit",
        "coefficient",
        "volume_horaire",
        "unite_enseignement_id",
        "campus_id",
    ];


    public function uniteEnseignement(){
        return $this->belongsTo(UniteEnseignement::class, "unite_enseignement_id");
    }

    public function campus(){
        return $this->belongsTo(Campus::class, "campus_id");
    }

    public function semestres(){
        return $this->belongsToMany(Semestre::class, 'matiere_semestre', 'matiere_id', 'semestre_id');
    }

}
