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

    public function mount()
    {
        $this->currentSubscription = Auth::user()->campus->activeSubscription();
        $this->packs = Pack::where('is_deleting', false)->get();
        $this->calculateRemainingDays();
    }

    public function calculateRemainingDays()
    {
        if ($this->currentSubscription) {
            $this->remainingDays = Carbon::now()->diffInDays($this->currentSubscription->end_date, false);
        }
    }

    public function changePack($packId)
    {
        $this->selectedPack = Pack::findOrFail($packId);
        $this->dispatch('show-confirmation-modal');
    }

    public function confirmPackChange()
    {
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

        $this->mount();
        $this->dispatch('hide-confirmation-modal');
        session()->flash('success', 'Abonnement mis à jour avec succès!');
    }

    public function cancelSubscription()
    {
        if ($this->currentSubscription) {
            $this->currentSubscription->update(['status' => 'cancelled']);
            $this->mount();
            session()->flash('success', 'Abonnement résilié avec succès!');
        }
    }

    public function render()
    {
        return view('livewire.abonnement.abonnement');
    }
}
