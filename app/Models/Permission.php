<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'module',
        'can_view',
        'can_create',
        'can_edit',
        'can_delete',
        'user_id',
        'role',
        'campus_id'
    ];

    protected $casts = [
        'can_view' => 'boolean',
        'can_create' => 'boolean',
        'can_edit' => 'boolean',
        'can_delete' => 'boolean'
    ];

    // Modules disponibles dans l'application
    public static $modules = [
        'academic_years' => 'Années académiques',
        'absences' => 'Absences',
        'classes' => 'Classes',
        'cours' => 'Cours',
        'departements' => 'Départements',
        'etudiants' => 'Étudiants',
        'filieres' => 'Filières',
        'messages' => 'Messages',
        'notes' => 'Notes',
        'paiements' => 'Paiements',
        'professeurs' => 'Professeurs',
        'rapports' => 'Rapports',
        'retards' => 'Retards',
        'ue' => 'Unités d\'enseignement',
        'emplois' => 'Emplois du temps',
        'configurations' => 'Configurations'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
