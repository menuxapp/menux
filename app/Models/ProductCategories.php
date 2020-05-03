<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
    
    protected $fillable = [
        'name', 'description', 'information', 'store_id', 'image'
    ];

    function Products()
    {
        return $this->hasMany('App\Models\Product', 'product_category', 'id');
    }
}
