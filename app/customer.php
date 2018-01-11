<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'id_customer';
    public  $timestamps = false;
}
