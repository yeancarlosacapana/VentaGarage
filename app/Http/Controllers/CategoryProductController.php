<?php

namespace App\Http\Controllers;


use App\CategoryLang;
use App\Category;
use App\CategoryProduct;
use App\Image;
use Carbon\Carbon;
use DB;
use Config;
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
        //refurbished
        //used
        $itemCategory = DB::table('product')
                        ->leftJoin('product_lang', 'product.id_product', '=' , 'product_lang.id_product')
                        ->leftJoin('category_product', 'category_product.id_product', '=', 'product.id_product')
                        ->leftJoin('category', 'category.id_category', '=', 'category_product.id_category')
                        ->leftJoin('category_lang', 'category_lang.id_category', '=' , 'category.id_category')
                        //->leftJoin('image','product.id_product','=','image.id_product')
                        ->where('product_lang.id_lang','=',2)
                        ->where('category_lang.id_lang','=',2)
                        ->where('category_product.id_category', '=' , $id)
                        ->whereIn('product.condition',array('used','refurbished'))
                        ->select('category_lang.name as category',
                                'category_product.id_category',
                                'category_product.id_product',
                                'product_lang.name as producto',
                                'product.price',
                                'product.condition',
                                DB::raw('(select id_image from '.DB::getTablePrefix().'image where id_product = '. DB::getTablePrefix().'product.id_product limit 1) as id_image')
                                )
                        ->get();
                        foreach($itemCategory as $key=>$item)
                        {    
                            //var_dump($item);
                            $itemCategory[$key]->url= Config::get('constants.images.url').$item->id_image.'.jpg';
                            //$itemCategory[$key]->url = config::get('constants.hogaryspacios.url').implode($item->id_product).'.jpg';
                        }
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
            $itemCategory = DB::table('product')
                            ->leftJoin('product_lang', 'product.id_product', '=' , 'product_lang.id_product')
                            ->leftJoin('category_product', 'product.id_product', '=' , 'category_product.id_product')
                            ->whereIn('product.condition',array('used','refurbished'));
            if ($request->fecha != "all")
                $itemCategory->whereBetween(DB::raw('DATE(contihogar_product.date_add)'),[$fecha1, $fecha2]);
            $itemCategory->where('category_product.id_category', '=', $categoriaId);
            if(isset($request['_sort']) && $request['_sort'] != "" && $request['_sort'] != "popularity" && $request['_sort'] != "new")
                $itemCategory->orderBy(explode('|',$request['_sort'])[0],explode('|',$request['_sort'])[1]);
            $itemCategory->select('category_product.id_category',
                            'category_product.id_product',
                            'product_lang.name as producto',
                            'product.price',
                            'product.condition',DB::raw('(select id_image from '.DB::getTablePrefix().'image where id_product = '. DB::getTablePrefix().'product.id_product limit 1) as id_image'));
        }
        if($request->typeFilter == "name"){
            $itemCategory = DB::table('product')
                            ->leftJoin('product_lang', 'product.id_product', '=' , 'product_lang.id_product')
                            ->whereIn('product.condition',array('used','refurbished'));
            if ($request->fecha != "all")
                $itemCategory->whereBetween(DB::raw('DATE(product.date_add)'),[$fecha1, $fecha2]);
            $itemCategory->where('product_lang.name', 'like', '%'.$productName.'%');
            if(isset($request['_sort']) && $request['_sort'] != "" && $request['_sort'] != "popularity" && $request['_sort'] != "new")
                $itemCategory->orderBy(explode('|',$request['_sort'])[0],explode('|',$request['_sort'])[1]);
            $itemCategory->select('product_lang.name as producto',
                                'product.price',
                                'product.condition',
                                DB::raw('(select id_image from '.DB::getTablePrefix().'image where id_product = '. DB::getTablePrefix().'product.id_product limit 1) as id_image'));
        }
        $itemCategory =  $itemCategory->get();
        foreach($itemCategory as $key=>$item)
                        {    
                            //var_dump($item);
                            $itemCategory[$key]->url= Config::get('constants.images.url').$item->id_image.'.jpg';
                            //$itemCategory[$key]->url = config::get('constants.hogaryspacios.url').implode($item->id_product).'.jpg';
                        }
        return response()->json($itemCategory, 200);
        
    }
    public function filterByPriceFromCategory(Request $request)
    {
         $precioMin = (int) $request['precioMin'];
         $precioMax = (int) $request['precioMax'];
         $categoriaId =(int) $request['categoriaId'];


        $itemCategory = DB::table('product')
                            ->leftJoin('product_lang', 'product.id_product', '=' , 'product_lang.id_product')
                            ->leftJoin('category_product', 'product.id_product', '=' , 'category_product.id_product')
                        ->where('product.price', '>=', $precioMin)
                        ->where('product.price', '<=', $precioMax)
                        ->whereIn('product.condition',array('used','refurbished'))
                        ->where('category_product.id_category', '=', $categoriaId);
                        
        
        if(isset($request['_sort']) && $request['_sort'] != "" && $request['_sort'] != "popularity" && $request['_sort'] != "new")
            $itemCategory->orderBy(explode('|',$request['_sort'])[0],explode('|',$request['_sort'])[1]);

        $result = $itemCategory->select('category_product.id_category',
            'product.id_product',
            'product_lang.name as producto',
            'product.price',
            'product.condition',
            DB::raw('(select id_image from '.DB::getTablePrefix().'image where id_product = '. DB::getTablePrefix().'product.id_product limit 1) as id_image'))
            ->get();
            foreach($result as $key=>$item)
            {    
                //var_dump($item);
                $result[$key]->url= Config::get('constants.images.url').$item->id_image.'.jpg';
                //$itemCategory[$key]->url = config::get('constants.hogaryspacios.url').implode($item->id_product).'.jpg';
            }
        return response()->json($result,200);
    }
    public function filterByPriceFromName(Request $request)
    {
        $precioMin = (int) $request['precioMin'];
        $precioMax = (int) $request['precioMax'];
        $productName = $request['name'];
        $itemCategory = DB::table('product')
                            ->leftJoin('product_lang', 'product.id_product', '=' , 'product_lang.id_product')
                        ->where('product.price', '>=', $precioMin)
                        ->where('product.price', '<=', $precioMax)
                        ->where('product_lang.id_lang','=',2)
                        ->whereIn('product.condition',array('used','refurbished'))
                        ->where('product_lang.name', 'like', '%'.$productName.'%');
                        //->where('category_lang.id_lang', '=' , $this->id_lang);
        if(isset($request['_sort']) && $request['_sort'] != "" && $request['_sort'] != "popularity" && $request['_sort'] != "new")
            $itemCategory->orderBy(explode('|',$request['_sort'])[0],explode('|',$request['_sort'])[1]);

        $result = $itemCategory->select('product.id_product',
                                'product_lang.name as producto',
                                'product.price',
                                'product.condition',
                                DB::raw('(select id_image from '.DB::getTablePrefix().'image where id_product = '. DB::getTablePrefix().'product.id_product limit 1) as id_image'))
                                ->get();
                                foreach($result as $key=>$item)
                        {    
                            //var_dump($item);
                            $result[$key]->url= Config::get('constants.images.url').$item->id_image.'.jpg';
                            //$itemCategory[$key]->url = config::get('constants.hogaryspacios.url').implode($item->id_product).'.jpg';
                        }
        return response()->json($result,200);
    }

    public function byName($name)
    {
        $itemCategory = DB::table('product')
        ->leftJoin('product_lang', 'product.id_product', '=', 'product_lang.id_product')
        ->leftJoin('category', 'category.id_category', '=' , 'product.id_category_default')
        ->leftJoin('category_lang', 'category.id_category', '=' , 'category_lang.id_category')
        ->where('product_lang.name', 'like' , '%'.$name.'%')
        ->where('product_lang.id_lang','=',2)
        ->where('category_lang.id_lang','=',2)
        ->whereIn('product.condition',array('used','refurbished'))

        ->select('category_lang.name as category',
                'category.id_category',
                'product.id_product',
                'product_lang.name as producto',
                'product.price',
                'product.condition',
                DB::raw('(select id_image from '.DB::getTablePrefix().'image where id_product = '. DB::getTablePrefix().'product.id_product limit 1) as id_image'))
        ->get();
        foreach($itemCategory as $key=>$item)
                        {    
                            //var_dump($item);
                            $itemCategory[$key]->url= Config::get('constants.images.url').$item->id_image.'.jpg';
                            //$itemCategory[$key]->url = config::get('constants.hogaryspacios.url').implode($item->id_product).'.jpg';
                        }
        return response()->json($itemCategory,200);
    }

    public function showCategoryByIdCategory($id_category)
    {
        $categoryLang = DB::table('category')
                            ->leftJoin('category_lang','category.id_category','=','category_lang.id_category')
                            ->where('category.id_parent','=',$id_category)
                            ->where('category_lang.id_lang','=',2)
                            ->select('category.id_category',
                                    'category_lang.name',
                                    'category.id_parent',
                                    'category.active')
                            ->get();
        
        return response()->json($categoryLang, 200);
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

        $itemCategory= DB::table('product')
            ->leftJoin('product_lang', 'product.id_product', '=', 'product_lang.id_product')
            ->whereIn('product.condition',array('used','refurbished'))
            ->where('product.price', '>=', $precioMin)
            ->where('product.price', '<=', $precioMax);
        if(isset($productName)&& $productName != "")
            $itemCategory->where('product_lang.name', 'like', '%'.$productName.'%');
        if(isset($categoriaId) &&  $categoriaId != "")
            $itemCategory->where('category_product.id_category', '=', $categoriaId);
        if(isset($sortBy) && $sortBy != "")
            $itemCategory->orderBy($sortBy,$order);
        if(isset($fecha1,$fecha2)&& $fecha1 != null || $fecha2 != null)
            $itemCategory->whereBetween($fecha1,$fecha2);
        
        $itemCategory = $itemCategory->get();

    }
}
