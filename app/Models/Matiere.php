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
        "coef",
        "credit",
        "filiere_id",
        "campus_id",
    ];


    public function filiere(){
        return $this->belongsTo(Filiere::class, "filiere_id");
    }

    public function campus(){
        return $this->belongsTo(Campus::class, "campus_id");
    }
}
