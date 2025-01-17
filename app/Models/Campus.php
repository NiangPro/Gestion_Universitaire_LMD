<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    use HasFactory;

    protected $table = "campuses";

    protected $fillable = [
        "nom",
        "tel",
        "adresse",
        "email",
        "image",
        "is_deleting",
        "statut",
        "date_fermeture",
        "pack_id"
    ];

    public function users(){
        return $this->hasMany(User::class);
    }

    public function admins(){
        return $this->users()->where('role', 'admin');
    }

    public function eleves(){
        return $this->users()->where('role', 'eleve');
    }

    public function professeurs(){
        return $this->users()->where('role', 'professeur');
    }

    public function parents(){
        return $this->users()->where('role', 'parent');
    }

    public function pack(){
        return $this->belongsTo(Pack::class, "pack_id");
    }

    public function academicYears(){
        return $this->hasMany(AcademicYear::class);
    }

    public function currentAcademicYear()
    {
        return $this->academicYears()->where('encours', true)->first();
    }

    public function departements(){
        return $this->hasMany(Departement::class);
    }

    public function filieres(){
        return $this->hasMany(Filiere::class);
    }

    public function niveauxEtudes(){
        return $this->hasMany(NiveauEtude::class);
    }

    public function uniteEnseignements(){
        return $this->hasMany(UniteEnseignement::class);
    }

    public function matieres(){
        return $this->hasMany(Matiere::class);
    }

    public function classes(){
        return $this->hasMany(Classe::class);
    }

    public function historiques(){
        return $this->hasMany(Historique::class);
    }
}
