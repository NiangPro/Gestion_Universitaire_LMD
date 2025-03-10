<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class MatriculeService
{
    /**
     * Génère un matricule unique pour un étudiant
     * Format: ETU-ANNEE-CAMPUS-XXXX
     * Exemple: ETU-2025-1-0001
     */
    public static function generateMatricule(int $campus_id): string
    {
        $year = Carbon::now()->year;
        
        // Récupérer le dernier numéro utilisé pour ce campus et cette année
        $lastUser = User::where('matricule', 'like', "ETU-{$year}-{$campus_id}-%")
            ->orderBy('matricule', 'desc')
            ->first();

        if ($lastUser) {
            // Extraire le numéro de séquence du dernier matricule
            $lastNumber = (int) substr($lastUser->matricule, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // Formater le numéro avec des zéros devant
        $sequence = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        
        return "ETU-{$year}-{$campus_id}-{$sequence}";
    }
}
