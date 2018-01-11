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
    private $iPrecioMin = 0;
    private $iPrecioMax = 1000;
    private $sAll = "all";
    private $id_lang = 1;
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
        $itemCategory = DB::table('hogaryspacios_product')
                        ->leftJoin('hogaryspacios_product_lang', 'hogaryspacios_product.id_product', '=' , 'hogaryspacios_product_lang.id_product')
                        ->leftJoin('hogaryspacios_category_product', 'hogaryspacios_category_product.id_product', '=', 'hogaryspacios_product.id_product')
                        ->leftJoin('hogaryspacios_category', 'hogaryspacios_category.id_category', '=', 'hogaryspacios_category_product.id_category')
                        ->leftJoin('hogaryspacios_category_lang', 'hogaryspacios_category_lang.id_category', '=' , 'hogaryspacios_category.id_category')
                        ->where('hogaryspacios_category_product.id_category', '=' , $id)
                        //
                        ->select('hogaryspacios_category_lang.name as category',
                                'hogaryspacios_category_product.id_category',
                                'hogaryspacios_category_product.id_product',
                                'hogaryspacios_product_lang.name as producto',
                                'hogaryspacios_product.price',
                                'hogaryspacios_product.condition'
                                )
                        ->Paginate(6);
        return response()->json($itemCategory,200);
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
        $fecha1                 =   Carbon::now();
        $fecha2                 =   Carbon::now();
        $categoriaId            =   (int)$request['id_category'];
        $productName            =   $request['name'];
        $itemCategory           =   [];
        $sortBy                 =   $request['_sort'];
        $order                  =   $request['_order'];
    
        switch ($request->fecha) {
            case "hoy":
               $fecha1 = $fecha1->toDateString();
               $fecha2 = $fecha2->toDateString();
                break;
            case "semana":
                $fecha1 = Carbon::now()->addDays(-7)->toDateString();
                $fecha2 = $fecha2->toDateString();
                break;
            case "mes":
                $fecha1 = Carbon::now()->addMonths(-1)->toDateString();
                $fecha2 = $fecha2->toDateString();
               break;
           case "all":
              break;    
       }
       if($request->typeFilter == "cat"){
            $itemCategory = DB::table('hogaryspacios_product')
                            ->leftJoin('hogaryspacios_product_lang', 'hogaryspacios_product.id_product', '=' , 'hogaryspacios_product_lang.id_product')
                            ->leftJoin('hogaryspacios_category_product', 'hogaryspacios_product.id_product', '=' , 'hogaryspacios_category_product.id_product');
                            // ->where('hogaryspacios_category_lang.id_lang', '=' , $this->id_lang);
            if ($request->fecha != "all")
                $itemCategory->whereBetween('hogaryspacios_product.date_add',[$fecha1, $fecha2]);
            $itemCategory->where('hogaryspacios_category_product.id_category', '=', $categoriaId);
            if(isset($request['_sort']) && $request['_sort'] != "" && $request['_sort'] != "popularity" && $request['_sort'] != "new")
                $itemCategory->orderBy(explode('|',$request['_sort'])[0],explode('|',$request['_sort'])[1]);
            $itemCategory->select('hogaryspacios_category_product.id_category',
                            'hogaryspacios_category_product.id_product',
                            'hogaryspacios_product_lang.name as producto',
                            'hogaryspacios_product.price',
                            'hogaryspacios_product.condition');
        }
        if($request->typeFilter == "name"){
            $itemCategory = DB::table('hogaryspacios_product')
                            ->leftJoin('hogaryspacios_product_lang', 'hogaryspacios_product.id_product', '=' , 'hogaryspacios_product_lang.id_product');
            if ($request->fecha != "all")
                $itemCategory->whereBetween('hogaryspacios_product.date_add',[$fecha1, $fecha2]);
            $itemCategory->where('hogaryspacios_product_lang.name', 'like', '%'.$productName.'%');
            if(isset($request['_sort']) && $request['_sort'] != "" && $request['_sort'] != "popularity" && $request['_sort'] != "new")
                $itemCategory->orderBy(explode('|',$request['_sort'])[0],explode('|',$request['_sort'])[1]);
            $itemCategory->select('hogaryspacios_product_lang.name as producto',
                                'hogaryspacios_product.price',
                                'hogaryspacios_product.condition');
        }
        $itemCategory =  $itemCategory->paginate(6);
        return response()->json($itemCategory, 200);
        
    }
    public function filterByPriceFromCategory(Request $request)
    {
         $precioMin = (int) $request['precioMin'];
         $precioMax = (int) $request['precioMax'];
         $categoriaId =(int) $request['categoriaId'];


        $itemCategory = DB::table('hogaryspacios_product')
                            ->leftJoin('hogaryspacios_product_lang', 'hogaryspacios_product.id_product', '=' , 'hogaryspacios_product_lang.id_product')
                            ->leftJoin('hogaryspacios_category_product', 'hogaryspacios_product.id_product', '=' , 'hogaryspacios_category_product.id_product')
                        ->where('hogaryspacios_product.price', '>=', $precioMin)
                        ->where('hogaryspacios_product.price', '<=', $precioMax)
                        ->where('hogaryspacios_category_product.id_category', '=', $categoriaId);
                        //->where('hogaryspacios_category_lang.id_lang', '=' , $this->id_lang);
        
        if(isset($request['_sort']) && $request['_sort'] != "" && $request['_sort'] != "popularity" && $request['_sort'] != "new")
            $itemCategory->orderBy(explode('|',$request['_sort'])[0],explode('|',$request['_sort'])[1]);

        $result = $itemCategory->select('hogaryspacios_category_product.id_category',
            'hogaryspacios_product.id_product',
            'hogaryspacios_product_lang.name as producto',
            'hogaryspacios_product.price',
            'hogaryspacios_product.condition')->paginate(6);

        return response()->json($result,200);
    }
    public function filterByPriceFromName(Request $request)
    {
        $precioMin = (int) $request['precioMin'];
        $precioMax = (int) $request['precioMax'];
        $productName = $request['name'];
        $itemCategory = DB::table('hogaryspacios_product')
                            ->leftJoin('hogaryspacios_product_lang', 'hogaryspacios_product.id_product', '=' , 'hogaryspacios_product_lang.id_product')
                        ->where('hogaryspacios_product.price', '>=', $precioMin)
                        ->where('hogaryspacios_product.price', '<=', $precioMax)
                        ->where('hogaryspacios_product_lang.name', 'like', '%'.$productName.'%');
                        //->where('hogaryspacios_category_lang.id_lang', '=' , $this->id_lang);
        if(isset($request['_sort']) && $request['_sort'] != "" && $request['_sort'] != "popularity" && $request['_sort'] != "new")
            $itemCategory->orderBy(explode('|',$request['_sort'])[0],explode('|',$request['_sort'])[1]);

        $result = $itemCategory->select('hogaryspacios_product.id_product',
                                'hogaryspacios_product_lang.name as producto',
                                'hogaryspacios_product.price',
                                'hogaryspacios_product.condition')->paginate(6);
        return response()->json($result,200);
    }

    public function byName($name)
    {
        $itemCategory = DB::table('hogaryspacios_product')
        ->leftJoin('hogaryspacios_product_lang', 'hogaryspacios_product.id_product', '=', 'hogaryspacios_product_lang.id_product')
        ->leftJoin('hogaryspacios_category', 'hogaryspacios_category.id_category', '=' , 'hogaryspacios_product.id_category_default')
        ->leftJoin('hogaryspacios_category_lang', 'hogaryspacios_category.id_category', '=' , 'hogaryspacios_category_lang.id_category')
        ->where('hogaryspacios_product_lang.name', 'like' , '%'.$name.'%')
        ->select('hogaryspacios_category_lang.name as category',
                'hogaryspacios_category.id_category',
                'hogaryspacios_product.id_product',
                'hogaryspacios_product_lang.name as producto',
                'hogaryspacios_product.price',
                'hogaryspacios_product.condition'
                )
        ->Paginate(6);
        
        return response()->json($itemCategory,200);
    }
    public function filterglobal(Request $request)
    {
        $precioMin          =   (int) $request['precioMin'];
        $precioMax          =   (int) $request['precioMax'];
        $productName        =   $request['name'];
        $categoriaId        =   (int) $request['categoriaId'];
        $fecha1             =   Carbon::now();
        $fecha2             =   Carbon::now();
        $productName        =   $request['name'];
        $itemCategory       =   [];
        $sortBy             =   $request['_sort'];
        $order              =   $request['_order'];

        $itemCategory= DB::table('hogaryspacios_product')
            ->leftJoin('hogaryspacios_product_lang', 'hogaryspacios_product.id_product', '=', 'hogaryspacios_product_lang.id_product')
            ->where('hogaryspacios_product.price', '>=', $precioMin)
            ->where('hogaryspacios_product.price', '<=', $precioMax);
        if(isset($productName)&& $productName != "")
            $itemCategory->where('hogaryspacios_product_lang.name', 'like', '%'.$productName.'%');
        if(isset($categoriaId) &&  $categoriaId != "")
            $itemCategory->where('hogaryspacios_category_product.id_category', '=', $categoriaId);
        if(isset($sortBy) && $sortBy != "")
            $itemCategory->orderBy($sortBy,$order);
        if(isset($fecha1,$fecha2)&& $fecha1 != null || $fecha2 != null)
            $itemCategory->whereBetween($fecha1,$fecha2);
        
        $itemCategory = $itemCategory->paginate(6);

    }
}
