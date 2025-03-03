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
        "academic_year_id",
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

    public function eleves()
    {
        return $this->belongsToMany(User::class, 'classe_eleve')
            ->withPivot('academic_year_id')
            ->withTimestamps();
    }
}
