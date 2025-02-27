<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FALaravel\Google2FA;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TwoFactorAuth extends Component
{
    public function enableTwoFactorAuth(Google2FA $google2fa)
    {
        try {
            DB::beginTransaction();
            
            $userId = Auth::id();
            $user = User::findOrFail($userId);
            
            // Générer la clé secrète
            $secret = $google2fa->generateSecretKey();
            
            // Générer les codes de récupération
            $recoveryCodes = Collection::times(8, function () {
                return sprintf('%s-%s-%s-%s', 
                    substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4),
                    substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4),
                    substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4),
                    substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4)
                );
            })->all();

            DB::table('users')
                ->where('id', $userId)
                ->update([
                    'two_factor_secret' => encrypt($secret),
                    'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes))
                ]);

            DB::commit();
            
            $this->emit('twoFactorEnabled');
            session()->flash('success', 'La double authentification a été activée avec succès.');
            
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Une erreur est survenue lors de l\'activation de la 2FA.');
        }
    }

    public function disableTwoFactorAuth()
    {
        try {
            DB::beginTransaction();
            
            $userId = Auth::id();
            
            DB::table('users')
                ->where('id', $userId)
                ->update([
                    'two_factor_secret' => null,
                    'two_factor_recovery_codes' => null,
                    'two_factor_confirmed_at' => null
                ]);

            DB::commit();
            
            $this->emit('twoFactorDisabled');
            session()->flash('success', 'La double authentification a été désactivée.');
            
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Une erreur est survenue lors de la désactivation de la 2FA.');
        }
    }

    public function getQrCodeUrlProperty(Google2FA $google2fa)
    {
        $user = User::find(Auth::id());
        if (!$user || !$user->two_factor_secret) {
            return null;
        }

        return $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            decrypt($user->two_factor_secret)
        );
    }

    public function render()
    {
        $user = User::find(Auth::id());
        $recoveryCodes = [];
        
        if ($user && $user->two_factor_recovery_codes) {
            try {
                $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);
            } catch (\Exception $e) {
                $recoveryCodes = [];
            }
        }
        
        return view('livewire.auth.two-factor-auth', [
            'qrCodeUrl' => $this->qrCodeUrl,
            'recoveryCodes' => $recoveryCodes
        ]);
    }
}
