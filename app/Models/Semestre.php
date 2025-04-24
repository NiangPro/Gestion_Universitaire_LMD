<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semestre extends Model
{
    protected $table = 'semestres';
    
    protected $fillable = ['nom', 'ordre', 'is_active', 'is_deleting', 'campus_id'];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    
    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
