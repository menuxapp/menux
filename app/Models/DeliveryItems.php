<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryItems extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'delivery_id', 'product_id', 'quantity',
    ];

    public function Product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }
}
