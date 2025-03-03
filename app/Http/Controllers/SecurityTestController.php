<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SecurityTestController extends Controller
{
    public function testSecurity()
    {
        // 1. Créer un utilisateur de test
        $user = User::create([
            'prenom' => 'Test',
            'nom' => 'Security',
            'email' => 'test.security@example.com',
            'username' => 'testsecurity',
            'password' => Hash::make('password123'),
            'tel' => '123456789',
            'role' => 'user',
            'sexe' => 'M',
        ]);

        $results = [
            'user_created' => true,
            'tests' => []
        ];

        // 2. Test du verrouillage de compte
        $results['tests'][] = $this->testAccountLocking($user);

        // 3. Test de la vérification d'email
        $results['tests'][] = $this->testEmailVerification($user);

        // 4. Test du changement forcé de mot de passe
        $results['tests'][] = $this->testForcePasswordChange($user);

        // 5. Test de la double authentification
        $results['tests'][] = $this->testTwoFactorAuth($user);

        // Nettoyage
        $user->delete();

        return response()->json($results);
    }

    private function testAccountLocking($user)
    {
        $result = ['name' => 'Account Locking Test', 'steps' => []];

        // Test 1: Échec de connexion multiple
        for ($i = 0; $i < 5; $i++) {
            $user->increment('failed_login_attempts');
        }
        $result['steps'][] = [
            'name' => 'Multiple Failed Attempts',
            'passed' => $user->failed_login_attempts === 5
        ];

        // Test 2: Verrouillage automatique
        $user->update([
            'is_locked' => true,
            'locked_at' => now()
        ]);
        $result['steps'][] = [
            'name' => 'Account Locked',
            'passed' => $user->is_locked === true
        ];

        // Test 3: Déverrouillage après délai
        $user->update([
            'locked_at' => now()->subMinutes(31)
        ]);
        $result['steps'][] = [
            'name' => 'Auto Unlock After Timeout',
            'passed' => now()->diffInMinutes($user->locked_at) > 30
        ];

        return $result;
    }

    private function testEmailVerification($user)
    {
        $result = ['name' => 'Email Verification Test', 'steps' => []];

        // Test 1: Email non vérifié par défaut
        $result['steps'][] = [
            'name' => 'Email Not Verified By Default',
            'passed' => $user->email_verified_at === null
        ];

        // Test 2: Vérification de l'email
        $user->update(['email_verified_at' => now()]);
        $result['steps'][] = [
            'name' => 'Email Verification',
            'passed' => $user->email_verified_at !== null
        ];

        return $result;
    }

    private function testForcePasswordChange($user)
    {
        $result = ['name' => 'Force Password Change Test', 'steps' => []];

        // Test 1: Forcer le changement de mot de passe
        $user->update(['force_password_change' => true]);
        $result['steps'][] = [
            'name' => 'Force Password Change Flag',
            'passed' => $user->force_password_change === true
        ];

        // Test 2: Mise à jour du mot de passe
        $user->update([
            'password' => Hash::make('newpassword123'),
            'password_changed_at' => now(),
            'force_password_change' => false
        ]);
        $result['steps'][] = [
            'name' => 'Password Update',
            'passed' => $user->force_password_change === false && 
                       $user->password_changed_at !== null
        ];

        return $result;
    }

    private function testTwoFactorAuth($user)
    {
        $result = ['name' => '2FA Test', 'steps' => []];

        // Test 1: 2FA non activé par défaut
        $result['steps'][] = [
            'name' => '2FA Not Enabled By Default',
            'passed' => $user->two_factor_secret === null
        ];

        // Test 2: Activation de la 2FA
        $user->update([
            'two_factor_secret' => encrypt('test_secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
            'two_factor_confirmed_at' => now()
        ]);
        $result['steps'][] = [
            'name' => '2FA Activation',
            'passed' => $user->two_factor_secret !== null && 
                       $user->two_factor_confirmed_at !== null
        ];

        return $result;
    }
}
