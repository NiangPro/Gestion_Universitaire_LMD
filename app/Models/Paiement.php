<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $table = "paiements";

    protected $fillable = [
        "status",
        "type_paiement",
        "mode_paiement",
        "montant",
        "user_id",
        "campus_id",
        "academic_year_id",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class, "campus_id");
    }

    public function etudiant()
    {
        return $this->user->where("role", "etudiant")->first();
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, "academic_year_id");
    }
    use HasFactory;
}
