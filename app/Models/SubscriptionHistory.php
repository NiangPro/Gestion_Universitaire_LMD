<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionHistory extends Model
{
    protected $fillable = [
        'subscription_id',
        'action',
        'old_status',
        'new_status',
        'user_id',
        'details'
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
