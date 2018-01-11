<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'hogaryspacios_homeslider';
    protected $primaryKey = 'id_homeslider_slides';
    public  $timestamps = false;
}
