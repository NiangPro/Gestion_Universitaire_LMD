<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = "messages";

    protected $fillable = ['sender_id', 'receiver_id', 'content', 'titre', 'image', 'is_read', 
    'is_favorite_sender', 'is_favorite_receiver'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function markAsFavorite($userId)
    {
        if ($this->sender_id == $userId) {
            $this->update(['is_favorite_sender' => true]);
        } elseif ($this->receiver_id == $userId) {
            $this->update(['is_favorite_receiver' => true]);
        }
    }

    public function unmarkAsFavorite($userId)
    {
        if ($this->sender_id == $userId) {
            $this->update(['is_favorite_sender' => false]);
        } elseif ($this->receiver_id == $userId) {
            $this->update(['is_favorite_receiver' => false]);
        }
    }

    public function isFavoriteForUser($userId)
    {
        if ($this->sender_id == $userId) {
            return $this->is_favorite_sender;
        } elseif ($this->receiver_id == $userId) {
            return $this->is_favorite_receiver;
        }
        return false;
    }
}
