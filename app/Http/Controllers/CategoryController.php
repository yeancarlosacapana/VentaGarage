<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CategoryLang;
use App\Category;
use DB;
use Config;
class CategoryController extends Controller
{
    private $id_lang = 1;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $oCategory = Category::with('CategoryLang')
                                ->where('level_depth','=','2')
                                ->where('active','=',1)
                                ->orderBy('level_depth', 'asc')->get();
                                foreach($oCategory as $key=>$item)
        {    
            $oCategory[$key]->url= Config::get('constants.hogaryspacios.url').'/img/c/'.$item->id_category.'.jpg';
        }
        return response()->json($oCategory,200);
       //  $itemSlider = DB::table('homeslider')
       //  ->leftJoin('homeslider_slides', 'homeslider.id_homeslider_slides', '=', 'homeslider_slides.id_homeslider_slides')
       //  ->leftJoin('homeslider_slides_lang', 'homeslider.id_homeslider_slides', '=', 'homeslider_slides_lang.id_homeslider_slides')
       //  ->where('homeslider_slides.active' , '=' , '1')
       //  ->where('homeslider_slides_lang.id_lang' , '>' , '1')
       //  ->get();
       //  foreach($itemSlider as $key=>$item){
            
       //      $itemSlider[$key]->url= Config::get('constants.hogaryspacios.url').'/modules/ps_imageslider/images/'.$item->image;
       //  }
       //  //return response()->json($oCategory,200);
       // return view('templates.category', ['category' => $oCategory->toArray()],['slider' => $itemSlider->toArray()]);

        
    }
    public function slider()
    {
        $itemSlider = DB::table('homeslider')
        ->leftJoin('homeslider_slides', 'homeslider.id_homeslider_slides', '=', 'homeslider_slides.id_homeslider_slides')
        ->leftJoin('homeslider_slides_lang', 'homeslider.id_homeslider_slides', '=', 'homeslider_slides_lang.id_homeslider_slides')
        ->where('homeslider_slides.active' , '=' , '1')
        ->where('homeslider_slides_lang.id_lang' , '>' , '1')
        ->get();
        foreach($itemSlider as $key=>$item)
        {    
            $itemSlider[$key]->url= Config::get('constants.hogaryspacios.url').'/modules/ps_imageslider/images/'.$item->image;
        }
        return response()->json($itemSlider,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
