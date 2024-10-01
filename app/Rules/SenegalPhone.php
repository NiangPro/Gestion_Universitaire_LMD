<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SenegalPhone implements Rule
{
    public function passes($attribute, $value)
    {
        // Valider que le numéro est composé de 9 chiffres et commence par un préfixe sénégalais
        return preg_match('/^(70|76|77|78|33)\d{7}$/', $value);
    }

    public function message()
    {
        return 'Le numéro de téléphone doit être un numéro valide au Sénégal.';
    }
}
