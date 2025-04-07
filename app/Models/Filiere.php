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

    public function departement()
    {
        return $this->belongsTo(Departement::class, "departement_id");
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class, "campus_id");
    }

    public function uniteEnseignements()
    {
        return $this->hasMany(UniteEnseignement::class);
    }

    // Modifions la relation matieres pour utiliser une relation à travers les UE
    public function matieres()
    {
        return $this->hasManyThrough(
            Matiere::class,
            UniteEnseignement::class,
            'filiere_id', // Clé étrangère sur unite_enseignements
            'unite_enseignement_id', // Clé étrangère sur matieres
            'id', // Clé locale sur filieres
            'id' // Clé locale sur unite_enseignements
        );
    }

    public function classes()
    {
        return $this->hasMany(Classe::class, 'filiere_id');
    }
}