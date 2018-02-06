<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CategoryLang;
use App\Category;
use App\CategoryProduct;
use App\Product;
use App\ProductLang;
use Carbon\Carbon;
use App\Image;
use App\CustomerProduct;
use DB;
use Config;

use Culqi\Culqi;
use Culqi\CulqiException;

class ProductController extends Controller
{
    private $id_lang = 2;
    private $registerMax = 3;
    private $api_secret_key = "pk_test_3KJlaO6Wq4F7YXMq";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $oCategory = Category::with('CategoryLang')
                                ->where('level_depth','>','1')
                                ->where('active','=',1)
                                ->orderBy('level_depth', 'asc')->get();
        return response()->json($oCategory,200);
    }
    public function addProduct(Request $request)
    {
        
        $oCulqi = $request["culqi"];
        $eProduct = $request["product"];
        try{
            $cargo = $this->createCharge($oCulqi,$eProduct);
            echo json_encode($cargo);
        }catch(Exception $ex){
           echo json_encode($ex);
        }
        die();

        $oCustomerProduct = $eProduct['customerProduct'];
        $cuenta = DB::table('customer_product')
        ->where('id_customer','=',$oCustomerProduct['id_customer'])
        ->count('id_customer');
        if($cuenta <= $this->registerMax){
            $mProduct = new Product();
            $mProduct->id_tax_rules_group = 1;
            $mProduct->id_category_default = $eProduct["id_category_default"];
            $mProduct->price = $eProduct["price"];
            $mProduct->condition = 'used';
            $mProduct->date_add=Carbon::now();
            $mProduct->date_upd=Carbon::now();
            $mProduct->save();
            $oProductLang = $eProduct['productLang'];
            $oImages = $eProduct['imgData'];
            
            
            $this->addProductLang($oProductLang,$mProduct->id_product);
            $this->addCategoryProduct($mProduct->id_category_default,$mProduct->id_product);
            $this->addImages($oImages,$mProduct->id_product);
            $this->addCustomerProduct($mProduct->id_product,$oCustomerProduct['id_customer']);

            return response()->json($mProduct, 200);
        }else{
            //return var_dump('a alcansado el limite de registro');
            return response()->json(array("resp"=>"a alcansado el limite de registro"), 200);
        }
    }
    public function addProductLang($oProductLang,$id_product)
    {
        $mProductLang = new ProductLang();
        $mProductLang->id_product = $id_product;
        $mProductLang->id_lang=2;
       $mProductLang->description=$oProductLang['description'];
       $mProductLang->inst_message='Producto no disponible en stock';
       $mProductLang->description_short=$oProductLang['description'];
       $mProductLang->link_rewrite=$oProductLang['name'];
       $mProductLang->name=$oProductLang['name'];
       $mProductLang->save();
    }
    public function addCustomerProduct($id_product,$id_customer)
    {
        $mCustomerProduct = new CustomerProduct();
        $mCustomerProduct->id_product = $id_product;
        $mCustomerProduct->id_customer = $id_customer; 
        $mCustomerProduct->save();
    }
    public function addImages($oImages,$id_product)
    {
        foreach($oImages as $image){
            $mImage = new Image();
            $mImage->id_product = $id_product;
            $mImage->save();

            $file_name = $mImage->id_image.'.jpg'; //generating unique file name; 
            @list($type, $image) = explode(';', $image);
            @list(, $image) = explode(',', $image); 
            if($image != ""){ // storing image in storage/app/public Folder 
                \Storage::disk('public')->put($file_name,base64_decode($image)); 
            }
        }
    }
    public function addCategoryProduct($id_category,$id_product)
    {
        $mCategoryProduct = new CategoryProduct();
        $mCategoryProduct->id_product=$id_product;
        $mCategoryProduct->id_category = $id_category;
        $mCategoryProduct->save();
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
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $productById = DB::table('product')
        ->leftJoin('product_lang', 'product.id_product', '=' , 'product_lang.id_product')
        ->leftJoin('image','product.id_product','=','image.id_product')
        ->where('product.id_product','=',$id)
        ->where('product_lang.id_lang','=',$this->id_lang)
        ->select('product_lang.name as producto',
                                'product.id_category_default',
                                'product.price',
                                'product_lang.description as descripcion',
                                'product.width',
                                'product.height',
                                'product.depth',
                                'product.condition',
                                'image.id_image',
                                'product.id_product'
                                )
        ->first();
        $images =  Image::where('id_product','=',$productById->id_product)->get();
        foreach($images as $key=>$image)
        {    
            $images[$key]->urlImage = Config::get('constants.images.url').$image->id_image.'.jpg';
        }
        $productById->image = $images;
        return response()->json($productById,200);
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

    private function createCharge($culqi_,$product){
        $culqi = new Culqi(array('api_key' => $this->api_secret_key));
        //$culqi->setEnv("INTEG");
        $token = $culqi_["token"]["id"];
        try{
            // Creamos Cargo a una tarjeta
            $cargo = $culqi->Charges->create(
                array(
                    "token"=> $token,
                    "moneda"=> "PEN",
                    "monto"=> 400,
                    "descripcion"=> 'Dale un aire de frescura a tu comunicación con un smartphone.',
                    "pedido"=> time(),
                    "codigo_pais"=> "PE",
                    "ciudad"=> "Lima",  
                    "usuario"=> "71701956",
                    "direccion"=> "Avenida Lima 1232",
                    "telefono"=> 12313123,
                    "nombres"=> "Stephan",
                    "apellidos"=> "Vargas",
                    "correo_electronico"=> "stephan.vargas@culqi.com"
                )
            );
            return $cargo;
        } catch(CulqiException $e){
            throw new CulqiException($e);
        }
    }
}