<?php

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $table = 'properties';
    
    protected $fillable = [
        'name', 'description', 'price'
    ];

    // Define the relationship with transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

