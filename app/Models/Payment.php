<?php

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    
    protected $fillable = [
        'transaction_id', 'status'
    ];

    // Define the relationship with transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}

