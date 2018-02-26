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
use App\Order;

use DB;
use Config;


class ProductController extends Controller
{
    private $id_lang = 2;
    private $id_shop = 2;
    private $registerMax = 3;
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
        $eProduct = $request;
        $oCustomerProduct = $eProduct['customerProduct'];
        $cuenta = DB::table('customer_product')
        ->where('id_customer','=',$oCustomerProduct['id_customer'])
        ->count('id_customer');
        if($cuenta <= $this->registerMax){
            $mProduct = new Product();
            $mProduct->id_tax_rules_group = 1;
            $mProduct->id_category_default = $eProduct["id_category_default"];
            $mProduct->id_shop_default = $this->id_shop;
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
            $oOrder = new OrderController();
            $oOrder->save($eProduct["orderGarage"],$mProduct->id_product);

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
        $mProductLang->id_lang = $this->id_lang;
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
                                'product.id_product')
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
        $id_product = $id;
        Image::where('id_product','=',$id_product)->delete();
        CategoryProduct::where('id_product','=',$id_product)->delete();
        CustomerProduct::where('id_product','=',$id_product)->delete();
        ProductLang::where('id_product','=',$id_product)->delete();
        Order::where('id_product','=',$id_product)->delete();
        Product::destroy($id_product);

        return response()->json(array("resp"=> true), 200);
    }

    public function getProductByCustomer(Request $request){
        
        $oProductCommand = DB::table('product')
                        ->join('product_lang','product.id_product','=','product_lang.id_product')
                        ->leftJoin('category','product.id_category_default','=','category.id_category')
                        ->leftJoin('category_lang','category.id_category','=','category_lang.id_category')
                        ->join('customer_product','product.id_product','=','customer_product.id_product')
                        ->leftJoin('order_garage','product.id_product','=','order_garage.id_product')
                        ->where('category_lang.id_lang','=',$this->id_lang)
                        ->where('product.id_shop_default','=',$this->id_shop)
                        ->where('customer_product.id_customer','=',$request["id_customer"]);
                        if(isset($request["id_product"]) && $request["id_product"] > 0)
                            $oProductCommand->where('product.id_product','=',$request["id_product"]);
        
        $oProductCommand =  $oProductCommand->select(
                            DB::raw(DB::getTablePrefix().'category_lang.name as category'),
                            'category.id_category',
                            'product.id_product',
                            'product.id_category_default',
                            'product.price',
                            'product_lang.name',
                            'product_lang.description',
                            'order_garage.method_payout',
                            'order_garage.pasarella',
                            'order_garage.total');

        $listProduct = $request["id_product"] == 0?$oProductCommand->get():$oProductCommand->first();
        if($request["id_product"] > 0){
            $listImage = Image::where('id_product','=',$request["id_product"])->get();
            $listProduct->image = $listImage;
        }
        
        return response()->json($listProduct, 200);
    }
}