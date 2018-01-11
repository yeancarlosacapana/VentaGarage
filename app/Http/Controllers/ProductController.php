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
use DB;
class ProductController extends Controller
{
    private $id_lang = 2;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $oCategory = Category::with('CategoryLang')
                                ->where('level_depth','>','1')
                                ->orderBy('level_depth', 'asc')->get();
        return response()->json($oCategory,200);
    }

    public function addProduct(Request $request)
    {
        $eProduct = $request;
        $mProduct = new Product();
        $mProduct->id_tax_rules_group = 1;
        $mProduct->id_category_default = $eProduct["id_category_default"];
        $mProduct->price = $eProduct["price"];
        $mProduct->condition = $eProduct["condition"];
        $mProduct->date_add=Carbon::now();
        $mProduct->date_upd=Carbon::now();
        $mProduct->save();
        $oProductLang = $eProduct['productLang'];
        $oImages = $eProduct['imgData'];
    
        $this->addProductLang($oProductLang,$mProduct->id_product);
        $this->addCategoryProduct($mProduct->id_category_default,$mProduct->id_product);
        $this->addImages($oImages,$mProduct->id_product);

        return response()->json($mProduct, 200);
        
    }
    public function addProductLang($oProductLang,$id_product)
    {
        $mProductLang = new ProductLang();
        $mProductLang->id_product = $id_product;
        $mProductLang->id_lang=2;
       $mProductLang->description=$oProductLang['description'];
       $mProductLang->description_short=$oProductLang['description'];
       $mProductLang->link_rewrite=$oProductLang['name'];
       $mProductLang->name=$oProductLang['name'];
       $mProductLang->save();
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
        ->where('product.id_product','=',$id)
        ->where('product_lang.id_lang','=',$this->id_lang)
        ->get();
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
}
