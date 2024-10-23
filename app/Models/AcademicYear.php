<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $table = "academic_years";

    protected $fillable = [
        "debut",
        "fin",
        "encours",
        "campus_id"
    ];

    public function campus(){
        return $this->belongsTo(Campus::class, "campus_id");
    }

    /**
     * Méthode pour récupérer l'année académique en cours unique pour un campus donné.
     *
     * @param int $campusId
     * @return AcademicYear|null
     */
    public static function getCurrentAcademicYear($campusId)
    {
        return self::where('encours', true)
                   ->where('campus_id', $campusId)
                   ->first();
    }
}
