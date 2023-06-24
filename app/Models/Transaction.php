<?php

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    
    protected $fillable = [
        'user_id', 'payment_method', 'amount'
    ];

    // Define the relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with payment
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Define the relationship with property
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}

