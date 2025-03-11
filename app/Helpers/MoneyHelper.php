<?php

namespace App\Helpers;

class MoneyHelper
{
    public static function formatMontant($montant)
    {
        // Formater le nombre avec espace comme séparateur de milliers et virgule pour décimales
        $montant_formatte = number_format($montant, 0, ',', ' ');
        return $montant_formatte . ' F CFA';
    }

    public static function getStatusTraduction($status)
    {
        $traductions = [
            'active' => 'Actif',
            'expired' => 'Expiré',
            'cancelled' => 'Résilié',
            'pending' => 'En attente',
            'paid' => 'Payé',
            'failed' => 'Échoué'
        ];

        return $traductions[$status] ?? $status;
    }
}
