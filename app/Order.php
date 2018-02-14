<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $table = 'order_garage';
    protected $primaryKey = 'id_order';
    public  $timestamps = false;
}
