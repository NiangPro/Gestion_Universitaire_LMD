<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubscriptionPaymentController extends Controller
{
    public function success(Request $request)
    {
        Log::info('PayDunya Success:', $request->all());
        return redirect()->route('abonnement')->with('success', 'Paiement effectué avec succès!');
    }

    public function cancel(Request $request)
    {
        Log::info('PayDunya Cancel:', $request->all());
        return redirect()->route('abonnement')->with('error', 'Paiement annulé');
    }

    public function callback(Request $request)
    {
        Log::info('PayDunya Callback:', $request->all());

        try {
            // Vérifier le token de la transaction
            $token = $request->input('token');
            if (!$token) {
                throw new \Exception('Token manquant');
            }

            // Récupérer les données personnalisées
            $customData = $request->input('custom_data', []);
            $subscriptionId = $customData['subscription_id'] ?? null;

            if (!$subscriptionId) {
                throw new \Exception('ID de souscription manquant');
            }

            $subscription = Subscription::find($subscriptionId);

            if (!$subscription) {
                throw new \Exception('Souscription non trouvée');
            }

            // Mettre à jour l'ancien abonnement
            if ($subscription->campus->activeSubscription()) {
                $subscription->campus->activeSubscription()->update([
                    'status' => 'expired',
                    'end_date' => now()
                ]);
            }

            // Activer le nouvel abonnement
            $subscription->update([
                'status' => 'active',
                'payment_status' => 'paid',
                'payment_reference' => $token
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Paiement traité avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('PayDunya Callback Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    protected function verifyPaydunyaSignature(Request $request)
    {
        // Vous pouvez ajouter ici une vérification de signature si PayDunya en fournit une
        return true;
    }
}
