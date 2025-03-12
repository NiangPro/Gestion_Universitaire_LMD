<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commentaire extends Model
{
    protected $fillable = [
        'inscription_id',
        'contenu',
        'user_id'
    ];

    /**
     * Obtenir l'inscription associée au commentaire
     */
    public function inscription(): BelongsTo
    {
        return $this->belongsTo(Inscription::class);
    }

    /**
     * Obtenir l'utilisateur qui a fait le commentaire
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
