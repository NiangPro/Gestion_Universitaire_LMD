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

    public function departements(){
        return $this->hasMany(Departement::class);
    }
}
