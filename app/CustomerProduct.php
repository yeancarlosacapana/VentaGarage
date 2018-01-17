<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerProduct extends Model
{
    protected $table = 'customer_product';
    protected $primaryKey = 'id_customer_product';
    public  $timestamps = false;
}
