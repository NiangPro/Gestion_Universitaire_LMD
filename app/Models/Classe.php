<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $table = "classes";

    protected $fillable =[
        "nom",
        "filiere_id",
        "campus_id",
        "type_periode",
        "duree",
        "academic_year_id",
        "is_active",
        "cout_formation",
        "cout_inscription",
        "mensualite",
        "is_deleting",
    ];

    public function cours(){
        return $this->hasMany(Cour::class);
    }
    public function filiere(){
        return $this->belongsTo(Filiere::class, "filiere_id");
    }

    public function campus(){
        return $this->belongsTo(Campus::class, "campus_id");
    }

    public function academicYear(){
        return $this->belongsTo(AcademicYear::class, "academic_year_id");
    }

    public function etudiants()
    {
        return $this->belongsToMany(User::class, 'inscriptions', 'classe_id', 'user_id')
            ->withPivot(['academic_year_id', 'montant', 'restant', 'status'])
            ->withTimestamps();
    }

    public function getDureeFormatteeAttribute()
    {
        if ($this->type_periode === 'annee') {
            return $this->duree . ' ' . ($this->duree > 1 ? 'années' : 'année');
        } else {
            return $this->duree . ' ' . ($this->duree > 1 ? 'mois' : 'mois');
        }
    }
}
