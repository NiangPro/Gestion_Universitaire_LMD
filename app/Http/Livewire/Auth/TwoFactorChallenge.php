<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PragmaRX\Google2FALaravel\Google2FA;

class TwoFactorChallenge extends Component
{
    public $code;
    public $recovery_code;

    protected $rules = [
        'code' => 'nullable|string|size:6',
        'recovery_code' => 'nullable|string',
    ];

    public function confirmTwoFactorAuth(Google2FA $google2fa)
    {
        $this->validate();
        
        try {
            DB::beginTransaction();
            
            $userId = Auth::id();
            $user = User::findOrFail($userId);

            if ($this->code) {
                $valid = $google2fa->verifyKey(
                    decrypt($user->two_factor_secret),
                    $this->code
                );
            } else {
                $valid = collect(json_decode(decrypt($user->two_factor_recovery_codes), true))
                    ->contains($this->recovery_code);

                if ($valid) {
                    // Retirer le code de récupération utilisé
                    $recoveryCodes = collect(json_decode(decrypt($user->two_factor_recovery_codes), true))
                        ->reject(function ($code) {
                            return $code === $this->recovery_code;
                        })
                        ->values()
                        ->all();

                    DB::table('users')
                        ->where('id', $userId)
                        ->update([
                            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes))
                        ]);
                }
            }

            if ($valid) {
                DB::table('users')
                    ->where('id', $userId)
                    ->update([
                        'two_factor_confirmed_at' => now()
                    ]);
                    
                DB::commit();
                session()->put('auth.two_factor.authenticated', true);
                return redirect()->intended(route('dashboard'));
            }

            DB::rollback();
            $this->addError(
                $this->code ? 'code' : 'recovery_code',
                'Le code fourni est invalide.'
            );
            
        } catch (\Exception $e) {
            DB::rollback();
            $this->addError(
                $this->code ? 'code' : 'recovery_code',
                'Une erreur est survenue lors de la vérification du code.'
            );
        }
    }

    public function render()
    {
        return view('livewire.auth.two-factor-challenge');
    }
}
