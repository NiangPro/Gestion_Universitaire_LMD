<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $table = "paiements";

    protected $fillable = [
        "status",
        "type_paiement",
        "mode_paiement",
        "montant",
        "user_id",
        "campus_id",
        "academic_year_id",
        "date_paiement",
        "reference",
        "observation",
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

    public static function genererReference()
    {
        return "P" . str_pad(self::count() + 1, 6, "0", STR_PAD_LEFT);
    }

    public function isEditable()
    {
        return $this->created_at->addDay()->greaterThan(now());
    }
}
