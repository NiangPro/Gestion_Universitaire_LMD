<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeEvaluation extends Model
{
    protected $fillable = ['nom', 'description', 'campus_id'];

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
