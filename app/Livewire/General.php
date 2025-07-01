<?php

namespace App\Livewire;

use App\Models\Campus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;

#[Title("Paramètres Généraux")]
class General extends Component
{
    use WithFileUploads;

    public $campus;
    public $tampon;
    public $signature;
    public $responsable;
    public $processing = false;

    public function mount()
    {
        $this->campus = Auth::user()->campus;
        $this->responsable = $this->campus->responsable;
    }

    public function saveTampon()
    {
        if (!$this->tampon) {
            $this->dispatch('notify', ['message' => 'Veuillez sélectionner une image', 'type' => 'error']);
            return;
        }
        $this->validate([
            'tampon' => 'required|image|max:1024',
        ]);

        $oldTampon = $this->campus->tampon;
        $filename = 'images/' . uniqid() . '.jpg';
        $this->tampon->storeAs('public', $filename);
        $this->removeBackground($filename, 'tampon', $oldTampon);
    }

    public function saveSignature()
    {
        if (!$this->signature) {
            $this->dispatch('notify', ['message' => 'Veuillez sélectionner une image', 'type' => 'error']);
            return;
        }
        $this->validate([
            'signature' => 'required|image|max:1024',
        ]);

        $oldSignature = $this->campus->signature;
        $filename = 'images/' . uniqid() . '.jpg';
        $this->signature->storeAs('public', $filename);
        $this->removeBackground($filename, 'signature', $oldSignature);
    }

    public function updatedTampon()
    {
        $this->validateOnly('tampon', [
            'tampon' => 'image|max:1024',
        ]);
    }

    public function updatedSignature()
    {
        $this->validateOnly('signature', [
            'signature' => 'image|max:1024',
        ]);
    }

    protected function removeBackground($filename, $type, $oldValue)
    {
        $this->processing = true;

        $response = Http::withHeaders([
            'x-api-key' => config('services.photoroom.key')
        ])->attach(
            'image_file',
            Storage::disk('public')->get($filename),
            basename($filename)
        )->post('https://api.photoroom.com/v1/removebg');

        if ($response->successful()) {
            $outputFilename = 'images/' . $type . '_' . uniqid() . '.jpg';
            Storage::disk('public')->put($outputFilename, $response->body());
            
            if ($type === 'tampon') {
                $this->campus->tampon = $outputFilename;
            } else {
                $this->campus->signature = $outputFilename;
            }
            $this->campus->save();

            Storage::disk('public')->delete($filename);
            $oldPath = $oldValue ? Storage::url($oldValue) : 'aucune';
            $this->dispatch('notify', ['message' => 'Image mise à jour avec succès. Ancienne image : ' . $oldPath]);
        } else {
            $this->dispatch('notify', ['message' => 'Erreur lors du traitement de l\'image', 'type' => 'error']);
        }

        $this->processing = false;
    }

    public function updateResponsable()
    {
        $this->validate([
            'responsable' => 'required|string|max:255'
        ]);

        $this->campus->update([
            'responsable' => $this->responsable
        ]);

        $oldResponsable = $this->campus->getOriginal('responsable');
        $this->dispatch('notify', ['message' => 'Responsable mis à jour avec succès. Ancienne valeur : ' . $oldResponsable]);
    }

    public function deleteImage($type)
    {
        if (!in_array($type, ['tampon', 'signature'])) {
            $this->dispatch('notify', ['message' => 'Type d\'image invalide', 'type' => 'error']);
            return;
        }

        $oldImage = $this->campus->{$type};
        if ($oldImage) {
            Storage::disk('public')->delete($oldImage);
            $this->campus->update([$type => null]);
            $this->dispatch('notify', ['message' => 'Image supprimée avec succès']);
        }
    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        return view('livewire.general');
    }
}
