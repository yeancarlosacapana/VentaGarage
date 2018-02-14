<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Order;

class OrderController extends Controller
{
    //
    public function save($oOrder,$id_product){
        $mOrder = new Order();
        $mOrder->id_product = $id_product;
        $mOrder->method_payout = $oOrder["method_payout"];
        $mOrder->pasarella = $oOrder["pasarella"];
        $mOrder->total = $oOrder["total"];
        $mOrder->active = $oOrder["active"];
        $mOrder->save();
    }
}
