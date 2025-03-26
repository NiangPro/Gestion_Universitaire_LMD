<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\User;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

#[Title("Mot de passe oublié")]
class PasswordForget extends Component
{
    public $trouve=false;
    public $idUser;
    public $code;
    public $trouveCode=false, $trouveMail =true, $trouveTel =true;
    public $form = [
        'email' => "",
        'tel' => "",
        'code' => "",
    ];


    public $form2 = [
        'password' => "",
        'password_confirmation' => "",
    ];

    protected $rules = [
        'form.code' => ['required', 'string'],
        'form.email' => ['required', 'email'],
        'form.tel' => ['required', 'regex:/^[33|70|75|76|77|78]+[0-9]{7}$/'],
        'form2.password' => 'required|string|min:6|confirmed',
    ];

    protected $messages = [
        'form.email.required' => 'L\'email est obligatoire',
        'form.email.email' => 'L\'email n\'est pas valide',
        'form.tel.required' => 'Le téléphone est obligatoire',
        'form.tel.regex' => 'Le format du téléphone n\'est pas valide',
        'form2.password.required' => 'Le mot de passe est obligatoire',
        'form2.password.min' => 'Le mot de passe doit contenir au moins 6 caractères',
        'form2.password.confirmed' => 'Les mots de passe ne correspondent pas'
    ];

    protected function formInit()
    {
        $this->form['email'] = "";
        $this->form['tel'] = "";
        $this->form['code'] = "";
    }

    protected function form2Init()
    {
        $this->form['password'] = "";
        $this->form['password_confirmation'] = "";
        $this->idUser = null;
    }

    public function editPassword(){
        if($this->trouve){
            $this->validate([
                'form2.password' => 'required|confirmed'
            ]);
            $user = User::where('email', $this->form['email'])->first();
            $user->password = Hash::make($this->form2['password']);

            $user->save();
            $this->dispatch('passwordEditSuccessful');
            return redirect(route('login'));
            $this->form2Init();
            $this->formInit();
        }
    }


    public function isExact(){
        $this->validate([
            'form.code' => 'required|string',
        ]);
        if($this->code== $this->form['code']){
            $this->dispatch('accessCode');
            return  $this->trouve = true;
        }else{
            $this->dispatch('errorCode');
        }
    }


    public function isExiste(){
        $this->validate([
            'form.email' => 'required|email',
            'form.tel' => 'required',
        ]);

        $user = User::where('email', strtolower($this->form['email']))
            ->where('tel', strtolower($this->form['tel'])) ->first();

        if ($user) {
        return true;
        }

        $this->trouveMail = User::where('email', strtolower($this->form['email']))->exists();
        $this->trouveTel = User::where('tel', strtolower($this->form['tel']))->exists();

        return false;
    }

    public function sendWelcomeEmail()
    {  
        if($this->isExiste()) {
            $this->code = mt_rand(1000, 9999);
            $title = 'Renitialisation du mot passe; 
            veuiller saisir ce code sur l\'entré afin de pouvoir renitialiser votre mot de passe';

            $body = $this->code;

            Mail::to(strtolower($this->form['email']))->send(new TestMail($title, $body));
            $this->trouveCode = true;

            $this->dispatch('sendCode');
        }else{
           
            if (!$this->trouveMail) {
                $this->dispatch('errorMail');
            }
            if (!$this->trouveTel) {
                $this->dispatch('errorTel');
            }
        }
        // return "Email envoyé avec succés!";
    }

    #[Layout("components.layouts.home")]
    public function render()
    {
        return view('livewire.password-forget');
    }
}
