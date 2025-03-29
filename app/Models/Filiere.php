<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;

    protected $table = "filieres";

    protected $fillable = [
        "nom",
        "is_deleting",
        "departement_id",
        "campus_id",
    ];

    public function departement(){
        return $this->belongsTo(Departement::class, "departement_id");
    }

    public function campus(){
        return $this->belongsTo(Campus::class, "campus_id");
    }

    public function uniteEnseignements(){
        return $this->hasMany(UniteEnseignement::class);
    }

    public function matieres(){
        return $this->hasMany(Matiere::class);
    }

    public function classes()
    {
        return $this->hasMany(Classe::class, 'filiere_id');
    }
}
