<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\Outils;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title("Messages")]
class Messages extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Optionnel : définit le style de pagination, par exemple pour Bootstrap
    
    public $type = "list";
    public $back = "";
    public $receiver_id;
    public $receiver;
    public $users = [];
    public $outils;
    public $msg;


    public $title;
    public $content;
    public $image;

    protected $messages = [
        'receiver_id.required' => 'Le champ "À" est requis.',
        'receiver_id.email' => 'Veuillez entrer une adresse email valide.',
        'receiver_id.exists' => 'Aucun utilisateur ne correspond à cet email.',
        'title.required' => 'Le champ "Titre" est requis.',
        'title.max' => 'Le titre ne doit pas dépasser 255 caractères.',
        'content.required' => 'Le champ "Contenu" est requis.',
        'image.image' => 'Le fichier doit être une image.',
        'image.max' => 'L\'image ne doit pas dépasser 5 Mo.',
    ];

    public function sendMessage()
    {
        // Validation des champs
        $this->validate([
            'receiver_id' => 'required|email|exists:users,email',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:5120', // Limite de 5 Mo
        ]);

        // Enregistrer l'image dans le dossier 'images' de storage, si une image est fournie
        if ($this->image) {
            $filePath = "msg_".uniqid().".jpg";
            $this->image->storeAs('public/images', $filePath);
        }else{
            $filePath = null;
        }

        // Créer le message
        Message::create([
            'receiver_id' => User::where('email', $this->receiver_id)->first()->id,
            'sender_id' => Auth::user()->id,
            'titre' => $this->title,
            'content' => $this->content,
            'image' => $filePath,
        ]);

        // Réinitialiser les champs du formulaire
        $this->reset(['receiver_id', 'title', 'content', 'image', 'users']);

        // Notification de succès
        $this->dispatch("sentMsg");
    }

    public function changeType($type){
        $this->back = $this->type;
        $this->type = $type;
    }

    public function readMessage($id){
        $this->changeType("read");

        $this->msg = Message::where("id", $id)->first();
        if (Auth::user()->id === $this->msg->receiver_id) {
            $this->msg->is_read = 1;
            $this->msg->save();
        }
        
    }

    public function getReceiver($id){
        $this->receiver = User::where("id", $id)->first();

        if ($this->receiver) {
            $this->receiver_id = $this->receiver->email;

            $this->users = [];
        }
        
    }

    public function toggleFavorite($messageId)
    {
        $message = Message::findOrFail($messageId);
        $userId = Auth::id();

        if ($message->isFavoriteForUser($userId)) {
            $message->unmarkAsFavorite($userId);
        } else {
            $message->markAsFavorite($userId);
        }

    }

    #[Layout("components.layouts.app")]
    public function render()
    {
        $this->outils = new Outils();
        if ($this->receiver_id && !$this->receiver) {
             // Rechercher des utilisateurs en fonction du nom ou de l'email
            $this->users = $this->outils->searchUser($this->receiver_id);
        }else{
            $this->users = [];
            $this->receiver = null;
        }
        return view('livewire.message.messages', [
            "sentMessages" => Message::where('sender_id', Auth::user()->id)->orderBy("id", "DESC")->paginate(10),
            "receivedMessages" => Message::where('receiver_id', Auth::user()->id)->orderBy("id", "DESC")->paginate(10),
            "importantMessages" => Message::where(function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('sender_id', Auth::id())
                             ->where('is_favorite_sender', true);
                })
                ->orWhere(function ($subQuery) {
                    $subQuery->where('receiver_id', Auth::id())
                             ->where('is_favorite_receiver', true);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
        ]);
    }
}
