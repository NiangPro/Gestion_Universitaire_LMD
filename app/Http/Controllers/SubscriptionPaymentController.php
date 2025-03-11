<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Paydunya\Checkout\CheckoutInvoice;
use Illuminate\Http\Request;

class SubscriptionPaymentController extends Controller
{
    public function success()
    {
        return redirect()->route('abonnement')->with('success', 'Paiement effectué avec succès!');
    }

    public function cancel()
    {
        return redirect()->route('abonnement')->with('error', 'Paiement annulé');
    }

    public function callback(Request $request)
    {
        $invoice = new CheckoutInvoice();

        if ($invoice->confirm($request->token)) {
            $customData = $invoice->getCustomData();
            $subscription = Subscription::find($customData['subscription_id']);

            if ($subscription) {
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
                    'payment_reference' => $invoice->token
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }
}
