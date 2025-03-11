<?php

namespace App\Livewire;

use App\Models\Pack;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Abonnement extends Component
{
    public $currentSubscription;
    public $packs;
    public $remainingDays;
    public $selectedPack;
    public $showRenewModal = false;
    public $renewalDuration = 'monthly'; // monthly ou annual
    public $compareMode = false;
    public $usersCount = 0; // Nombre d'utilisateurs actuels

    protected $listeners = ['resetRenewal'];

    public function mount()
    {
        $this->currentSubscription = Auth::user()->campus->activeSubscription();
        $this->packs = Pack::where('is_deleting', false)->get();
        $this->calculateRemainingDays();
        $this->usersCount = $this->getCurrentUsersCount();
    }

    public function calculateRemainingDays()
    {
        if ($this->currentSubscription && $this->currentSubscription->end_date) {
            $this->remainingDays = max(0, Carbon::now()->diffInDays($this->currentSubscription->end_date, false));
        } else {
            $this->remainingDays = 0;
        }
    }

    private function getCurrentUsersCount()
    {
        // Remplacez ceci par votre logique réelle de comptage d'utilisateurs
        return Auth::user()->campus->users()->count();
    }

    public function getUsersProgressColor()
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

    public function changePack($packId)
    {
        $this->selectedPack = Pack::findOrFail($packId);

        // Vérifier si c'est le même pack
        if ($this->currentSubscription && $this->currentSubscription->pack_id === $packId) {
            session()->flash('info', 'Vous utilisez déjà ce pack. Vous pouvez le renouveler si vous le souhaitez.');
            return;
        }

        $this->dispatch('show-confirmation-modal');
    }

    public function showRenewModal()
    {
        if ($this->currentSubscription) {
            $this->dispatch('show-renew-modal');
        }
    }

    public function showCancelModal()
    {
        $this->dispatch('show-cancel-modal');
    }

    public function confirmCancellation()
    {
        try {
            if ($this->currentSubscription) {
                $this->currentSubscription->update(['status' => 'cancelled']);
                $this->mount();
                $this->dispatch('hide-cancel-modal');
                session()->flash('success', 'Votre abonnement a été résilié avec succès.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Une erreur est survenue lors de la résiliation.');
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

            Subscription::create([
                'campus_id' => Auth::user()->campus_id,
                'pack_id' => $this->currentSubscription->pack_id,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths($duration),
                'status' => 'active',
                'payment_status' => 'pending',
                'amount_paid' => $amount
            ]);

            $this->dispatch('hide-renew-modal');
            $this->mount();
            session()->flash('success', 'Abonnement renouvelé avec succès!');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors du renouvellement : ' . $e->getMessage());
        }
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

            $this->mount(); // Rafraîchir les données
            $this->dispatch('hide-confirmation-modal');
            session()->flash('success', 'Votre abonnement a été mis à jour avec succès!');
        } catch (\Exception $e) {
            session()->flash('error', 'Une erreur est survenue lors du changement de pack.');
        }
    }

    public function cancelSubscription()
    {
        if ($this->currentSubscription) {
            $this->currentSubscription->update(['status' => 'cancelled']);
            $this->mount();
            session()->flash('success', 'Abonnement résilié avec succès!');
        }
    }

    public function resetRenewal()
    {
        $this->renewalDuration = 'monthly';
        $this->mount();
    }

    public function render()
    {
        return view('livewire.abonnement.abonnement');
    }
}
