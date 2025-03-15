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
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function admins()
    {
        return $this->users()->where('role', 'admin');
    }

    public function eleves()
    {
        return $this->users()->where('role', 'eleve');
    }

    public function professeurs()
    {
        return $this->users()->where('role', 'professeur');
    }

    public function parents()
    {
        return $this->users()->where('role', 'parent');
    }


    public function academicYears()
    {
        return $this->hasMany(AcademicYear::class);
    }

    public function currentAcademicYear()
    {
        return $this->academicYears()->where('encours', true)->first();
    }

    public function departements()
    {
        return $this->hasMany(Departement::class);
    }

    public function filieres()
    {
        return $this->hasMany(Filiere::class);
    }

    public function niveauxEtudes()
    {
        return $this->hasMany(NiveauEtude::class);
    }

    public function uniteEnseignements()
    {
        return $this->hasMany(UniteEnseignement::class);
    }

    public function matieres()
    {
        return $this->hasMany(Matiere::class);
    }

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }

    public function historiques()
    {
        return $this->hasMany(Historique::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('payment_status', 'paid')
            ->where('end_date', '>', now())
            ->latest()
            ->first();
    }

    public function hasActiveSubscription()
    {
        return $this->activeSubscription() !== null;
    }

    public function coefficients(){
        return $this->hasMany(Coefficient::class);
    }
}
