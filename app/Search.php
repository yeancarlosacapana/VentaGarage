<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Search extends Model
{
    protected $table = 'hogaryspacios_product';
     protected $primaryKey = 'id_product';
    public  $timestamps = false;
}
