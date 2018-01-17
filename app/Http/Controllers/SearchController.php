<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Pagination\Paginator;
use App\Search;
use App\Product;
use DB;
class SearchController extends Controller
{
    public function index($name)
    {
        // $name = $name;
        // $link_rewrite = "..." ;
        // $tipoBusqueda = "name"; 
        // $precioMin = 0;
        // $precioMax = 1000; 
        $itemCategory = DB::table('product')
        ->leftJoin('product_lang', 'product.id_product', '=' , 'product_lang.id_product')
        ->where('product_lang.name', 'like', '%'.$name.'%')->get();
        return response()->json($itemCategory,200);
        //return view('templates.search',  compact('search'));

        // return view('templates.itemCategory', compact('itemCategory'))->with('name' ,$name)->with('link_rewrite',$link_rewrite)->with('tipoBusqueda',$tipoBusqueda)->with('precioMin',$precioMin)->with('precioMax',$precioMax);
    }
    // public function forRedirect(Request $request)
    // {   
    //     //var_dump($request['product_name']);
    //     $urlToRedirect = @"/search/".$request['product_name'];
    //     return redirect($urlToRedirect);
    // }
}
