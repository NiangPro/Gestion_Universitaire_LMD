<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semaine extends Model
{
    use HasFactory;

    protected $table = "semaines";

    protected $fillable = ["jour"];

    public function cours()
    {
        return $this->belongsToMany(Cour::class, 'cours_semaine', 'semaine_id', 'cours_id');
    }
}
