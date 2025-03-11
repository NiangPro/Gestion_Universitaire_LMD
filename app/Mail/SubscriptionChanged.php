<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class SubscriptionChanged extends Mailable
{
    public $subscription;
    public $action;

    public function __construct($subscription, $action)
    {
        $this->subscription = $subscription;
        $this->action = $action;
    }

    public function build()
    {
        return $this->markdown('emails.subscriptions.changed')
            ->subject("Modification de votre abonnement - {$this->action}");
    }
}
