<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Config;
class SliderController extends Controller
{
    public function index()
    {
        $itemSlider = DB::table('contihogar_homeslider')
        ->leftJoin('contihogar_homeslider_slides', 'contihogar_homeslider.id_homeslider_slides', '=', 'contihogar_homeslider_slides.id_homeslider_slides')
        ->leftJoin('contihogar_homeslider_slides_lang', 'contihogar_homeslider.id_homeslider_slides', '=', 'contihogar_homeslider_slides_lang.id_homeslider_slides')
        ->where('contihogar_homeslider_slides.active' , '=' , '1')
        ->where('contihogar_homeslider_slides_lang.id_lang' , '>' , '1')
        ->get();
        foreach($itemSlider as $key=>$item){
            
            $itemSlider[$key]->url= Config::get('constants.hogaryspacios.url').$item->image;
        }
        //return response()->json($itemSlider,200);
        return view('templates.slider', ['slider' => $itemSlider->toArray()]);
    }
}
