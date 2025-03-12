<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    protected $fillable = [
        'etudiant_id',
        'cours_id',
        'type_evaluation',  // (CC, TP, Examen)
        'note',
        'coefficient',
        'observation',
        'date_evaluation',
        'semestre'
    ];

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'etudiant_id');
    }

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cour::class, 'cours_id');
    }
}
