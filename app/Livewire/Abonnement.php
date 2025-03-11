<?php

namespace App\Livewire;

use App\Models\Pack;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\SubscriptionRenewed;
use Livewire\Attributes\Title;

#[Title("Abonnements")]
class Abonnement extends Component
{
    public $currentSubscription;
    public $packs;
    public $remainingDays;
    public $selectedPack;
    public $renewalDuration = 'monthly';
    public $usersCount = 0;
    public $compareMode = false;

    public function mount()
    {
        $this->currentSubscription = Auth::user()->campus->activeSubscription();
        $this->packs = Pack::where('is_deleting', false)->get();
        $this->calculateRemainingDays();
        $this->usersCount = $this->getCurrentUsersCount();

        // Vérifier si l'abonnement expire bientôt
        if ($this->remainingDays <= 7) {
            session()->flash('warning', 'Votre abonnement expire dans ' . $this->remainingDays . ' jours !');
        }
    }

    private function getCurrentUsersCount()
    {
        return Auth::user()->campus->users()->count();
    }

    public function calculateRemainingDays()
    {
        if ($this->currentSubscription && $this->currentSubscription->end_date) {
            $this->remainingDays = max(0, Carbon::now()->diffInDays($this->currentSubscription->end_date, false));
        } else {
            $this->remainingDays = 0;
        }
    }

    public function getProgressWidth()
    {
        if (!$this->currentSubscription || !$this->currentSubscription->pack->limite) {
            return 0;
        }
        return min(100, ($this->usersCount / $this->currentSubscription->pack->limite) * 100);
    }

    public function getUserProgressBarColor()
    {
        if (!$this->currentSubscription) return 'bg-secondary';

        $percentage = ($this->usersCount / $this->currentSubscription->pack->limite) * 100;

        if ($percentage >= 90) {
            return 'bg-danger';
        } elseif ($percentage >= 70) {
            return 'bg-warning';
        } else {
            return 'bg-success';
        }
    }

    public function toggleCompareMode()
    {
        $this->compareMode = !$this->compareMode;
    }

    public function showRenewModal()
    {
        $this->dispatch('openRenewModal');
    }

    public function showCancelModal()
    {
        $this->dispatch('openCancelModal');
    }

    public function changePack($packId)
    {
        $this->selectedPack = Pack::findOrFail($packId);
        $this->dispatch('openChangePackModal');
    }

    public function confirmPackChange()
    {
        try {
            if ($this->currentSubscription) {
                $this->currentSubscription->update([
                    'status' => 'cancelled',
                    'end_date' => Carbon::now()
                ]);
            }

            Subscription::create([
                'campus_id' => Auth::user()->campus_id,
                'pack_id' => $this->selectedPack->id,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonth(),
                'status' => 'active',
                'payment_status' => 'pending',
                'amount_paid' => $this->selectedPack->mensuel
            ]);

            $this->dispatch('closeChangePackModal');
            $this->mount();
            session()->flash('success', 'Pack changé avec succès!');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors du changement de pack : ' . $e->getMessage());
        }
    }

    public function confirmCancellation()
    {
        try {
            if ($this->currentSubscription) {
                $this->currentSubscription->update(['status' => 'cancelled']);
                $this->dispatch('closeCancelModal');
                $this->mount();
                session()->flash('success', 'Abonnement résilié avec succès.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la résiliation : ' . $e->getMessage());
        }
    }

    public function renewSubscription()
    {
        try {
            $duration = $this->renewalDuration === 'annual' ? 12 : 1;
            $amount = $this->renewalDuration === 'annual' ?
                $this->currentSubscription->pack->annuel :
                $this->currentSubscription->pack->mensuel;

            if ($this->currentSubscription) {
                $this->currentSubscription->update([
                    'status' => 'expired',
                    'end_date' => Carbon::now()
                ]);
            }

            $newSubscription = Subscription::create([
                'campus_id' => Auth::user()->campus_id,
                'pack_id' => $this->currentSubscription->pack_id,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths($duration),
                'status' => 'active',
                'payment_status' => 'pending',
                'amount_paid' => $amount
            ]);

            try {
                Mail::to(Auth::user()->email)->send(new SubscriptionRenewed($newSubscription));
            } catch (\Exception $e) {
                Log::error('Erreur d\'envoi d\'email : ' . $e->getMessage());
            }

            // Fermer proprement la modal
            $this->dispatch('closeRenewModal');

            // Nettoyer le backdrop et réactiver le scroll
            $this->dispatch('cleanupModal');

            $this->mount();
            session()->flash('success', 'Abonnement renouvelé avec succès!');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors du renouvellement : ' . $e->getMessage());
        }
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.abonnement.abonnement');
    }
}
