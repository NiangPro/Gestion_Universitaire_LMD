@component('mail::message')
# Confirmation de renouvellement

Bonjour,

Votre abonnement a été renouvelé avec succès.

**Détails de l'abonnement :**
- Pack : {{ $subscription->pack->nom }}
- Durée : {{ $subscription->start_date->format('d/m/Y') }} au {{ $subscription->end_date->format('d/m/Y') }}
- Montant : {{ \App\Helpers\MoneyHelper::formatMontant($subscription->amount_paid) }}

@component('mail::button', ['url' => route('dashboard')])
Accéder à mon espace
@endcomponent

Merci de votre confiance,<br>
{{ config('app.name') }}
@endcomponent