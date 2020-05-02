<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'cep', 'address', 'address_number', 'district', 'city', 'uf'
    ];

    public function ProductCategories()
    {
        return $this->hasMany('App\Models\ProductCategories', 'store_id', 'id');
    }

    public function Product()
    {
        return $this->hasManyThrough('App\Models\Product', 'App\Models\ProductCategories', 'id', 'product_category', 'id');
    }
}
