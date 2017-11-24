<?php

namespace App\Http\Controllers;


use App\CategoryLang;
use App\Category;
use App\CategoryProduct;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class CategoryProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
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
        $oCategoryLang = CategoryLang::get()->where('id_category','=',$id)->first();
        $name = $oCategoryLang->name;
        $tipoBusqueda = 'category';
        $link_rewrite = $oCategoryLang->link_rewrite ;
        $precioMin = 0;
        $precioMax = 1000;
        //$itemCategory = CategoryProduct::all();
        $itemCategory = DB::table('contihogar_category_product')
        ->leftJoin('contihogar_product', 'contihogar_category_product.id_product', '=', 'contihogar_product.id_product')
        ->leftJoin('contihogar_product_lang', 'contihogar_category_product.id_product', '=' , 'contihogar_product_lang.id_product')
        
        ->where('contihogar_category_product.id_category', '=' , $id)->Paginate(6);
        //var_dump($itemCategory);
        //$itemCategory['category'] = $oCategory;
        return view('templates.itemCategory', compact('itemCategory'))->with('name' ,$name)->with('link_rewrite',$link_rewrite)->with('tipoBusqueda',$tipoBusqueda)->with('precioMin',$precioMin)->with('precioMax',$precioMax);
        //return response()->json($itemCategory,200);
        //return response()->json($oCategoryLang,200);
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
    /**
     * Buscar por Fecha the specified resource  de la base de dato.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function busquedaPorFecha(Request $request)
    {
        
        $name = "hola";
        $link_rewrite = "mundo" ;

        $fecha1 = Carbon::now();
        $fecha2 = Carbon::now();
    
        $isFilter = true;
        var_dump($request->fecha);
        switch ($request->fecha) {
            case "hoy":
               $fecha1 = $fecha1->toDateString();
               $fecha2 = $fecha2->toDateString();
                break;
            case "semana":
                $fecha1 = $fecha1->toDateString();
                $fecha2 = Carbon::now()->addDays(-7);
                break;
            case "mes":
                echo "i es igual a 2";
               break;
           case "all":
               $isFilter = false;
              break;    
       }
       $itemCategory = DB::table('contihogar_product')
       ->leftJoin('contihogar_product_lang', 'contihogar_product.id_product', '=' , 'contihogar_product_lang.id_product')
       ->whereBetween('DATE(contihogar_product.date_add)',[$fecha1, $fecha2])
       ->paginate(3);
       //return view('templates.itemCategory', compact('itemCategory'))->with('name' ,$name)->with('link_rewrite',$link_rewrite);

        
        //->where('contihogar_product_lang.name', 'like', '%'.$name.'%')->Paginate(6);
        // $today = Carbon::today()->toDateString();
        // echo $today;  
        //var_dump(Carbon::now());
        
    }
    public function busquedaPorPrecio(Request $request)
    {
        $link_rewrite = "..." ;
        $itemCategory;

        $precioMin = (int) $request['precioMin'];
        $precioMax = (int) $request['precioMax'];
        $categoriaId =(int) $request['categoriaId'];
        $tipoBusqueda = $request['tipoBusqueda'];
        $productName = $request['name'];

        if($tipoBusqueda == "category"){
            $itemCategory = DB::table('contihogar_product')
            ->leftJoin('contihogar_product_lang', 'contihogar_product.id_product', '=' , 'contihogar_product_lang.id_product')
            ->leftJoin('contihogar_category_product', 'contihogar_product.id_product', '=' , 'contihogar_category_product.id_product')
            ->where('contihogar_product.price', '>=', $precioMin)
            ->where('contihogar_product.price', '<=', $precioMax)
            ->where('contihogar_category_product.id_category', '=', $categoriaId)
            ->paginate(6);
        }else{
            $itemCategory = DB::table('contihogar_product')
            ->leftJoin('contihogar_product_lang', 'contihogar_product.id_product', '=' , 'contihogar_product_lang.id_product')
            ->where('contihogar_product.price', '>=', $precioMin)
            ->where('contihogar_product.price', '<=', $precioMax)
            ->where('contihogar_product_lang.name', 'like', '%'.$productName.'%')->Paginate(6);
            //var_dump($itemCategory);
        }
        
        return view('templates.itemCategory', compact('itemCategory'))->with('name' ,$productName)->with('link_rewrite',$link_rewrite)->with('tipoBusqueda',$tipoBusqueda)->with('precioMin',$precioMin)->with('precioMax',$precioMax);
        //return response()->json($itemCategory,200);
    }
}
