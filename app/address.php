<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class address extends Model
{
    protected $table = 'address';
    protected $primaryKey = 'id_address';
    public  $timestamps = false;
}
