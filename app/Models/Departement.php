<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;

    protected $table = "departements";

    protected $fillable = [
        "nom",
        "is_deleting",
        "description",
        "campus_id",
    ];

    public function campus(){
        return $this->belongsTo(Campus::class, "campus_id");
    }
}
