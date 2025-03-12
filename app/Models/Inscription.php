<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inscription extends Model
{
    use HasFactory;

    protected $table = "inscriptions";
    
    protected $fillable = [
        "user_id",
        "campus_id",
        "academic_year_id",
        "classe_id",
        "tuteur_id",
        "medical_id",
        "relation",
        "montant",
        "restant",
        "tenue",
        "commentaire",
        "status",
        "date_inscription",
        "etudiant_id"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class, "campus_id");
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, "academic_year_id");
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, "classe_id");
    }
    
    public function paiement()
    {
        return $this->belongsTo(Paiement::class, "paiement_id");
    }
    
    public function tenue()
    {
        return $this->belongsTo(Tenue::class, "tenue_id");
    }
    
    public function tuteur()
    {
        return $this->belongsTo(User::class, "tuteur_id");
    }

    /**
     * Obtenir les commentaires de l'inscription
     */
    public function commentaires(): HasMany
    {
        return $this->hasMany(Commentaire::class);
    }

    /**
     * Get the student that owns the inscription.
     */
    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'etudiant_id');
    }
}
