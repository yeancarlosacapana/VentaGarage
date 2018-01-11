<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'id_product';
    public  $timestamps = false;

    public function CategoryProduct()
    {
        return $this->hasOne('App\CategoryProduct','id_product','id_product');
    }
    public function Search()
    {
        return $this->hasOne('App\Search','id_product','id_product');
    }
}
