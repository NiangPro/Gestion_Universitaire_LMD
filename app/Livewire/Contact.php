<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Contact")]
class Contact extends Component
{
    public $name;
    public $email;
    public $subject;
    public $message;
    public $successMessage = '';

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'subject' => 'required|min:5',
        'message' => 'required|min:10'
    ];

    public function submitForm()
    {
        $this->validate();

        // Envoyer l'email
        // Mail::to('contact@schoolmanager.ci')->send(new ContactFormMail($this->name, $this->email, $this->subject, $this->message));

        $this->successMessage = 'Votre message a été envoyé avec succès !';
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->subject = '';
        $this->message = '';
    }

    #[Layout("components.layouts.home")]
    public function render()
    {
        return view('livewire.contact');
    }
}
