<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    
    protected $fillable = [
        'store_id', 'payment_method', 'value', 'amount_paid'
    ];
}
