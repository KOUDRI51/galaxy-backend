<?php

use Illuminate\Database\Eloquent\Model;

class InvestmentPriority extends Model
{
    protected $table = 'investment_priorities';
    
    protected $fillable = [
        'user_id', 'priority'
    ];

    // Define the relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

