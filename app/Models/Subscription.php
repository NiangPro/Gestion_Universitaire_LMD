<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $table = "subscriptions";

    protected $fillable = [
        'campus_id',
        'pack_id',
        'start_date',
        'end_date',
        'status',
        'payment_status',
        'amount_paid',
        'payment_method',
        'payment_reference'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function pack()
    {
        return $this->belongsTo(Pack::class);
    }

    public function isActive()
    {
        return $this->status === 'active' &&
            $this->end_date->isFuture() &&
            $this->payment_status === 'paid';
    }
}
