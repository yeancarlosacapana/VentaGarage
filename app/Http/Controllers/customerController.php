<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\customer;
use App\address;
use Carbon\Carbon;
use DB;
class CustomerController extends Controller
{
    public function registerCustomer(Request $request)
    {
        
       $eCustomer = $request;
       $mCustomer = new Customer();
       $mCustomer->id_gender = 0;
       $mCustomer->active = 1;
       $mCustomer->company = $eCustomer["company"];
       $mCustomer->firstname = $eCustomer["firstname"];
       $mCustomer->lastname = $eCustomer["lastname"];
       $mCustomer->email = $eCustomer["email"];
       $mCustomer->passwd = md5($eCustomer["passwd"]);
       $mCustomer->date_add=Carbon::now();
       $mCustomer->date_upd=Carbon::now();
       $mCustomer->save();

       $oAddress = $eCustomer['address'];
       $this->grabarAddres($oAddress,$mCustomer->id_customer);
       return response()->json($mCustomer,200);

    }
    public function grabarAddres($oAddress,$id_customer)
    {
       $mAddress = new Address();
       $mAddress->id_country = 171;
       $mAddress->id_state=$oAddress['state'];
       $mAddress->id_distrito=$oAddress['distrito'];
       $mAddress->id_provincia=$oAddress['provincia'];
       $mAddress->id_customer = $id_customer; 
       $mAddress->alias='direccion'.$id_customer;
       $mAddress->company=$oAddress['company'];
       $mAddress->lastname=$oAddress['lastname'];
       $mAddress->firstname=$oAddress['firstname'];
       $mAddress->address1=$oAddress['address1'];
       $mAddress->city=$oAddress['provincia'];
       $mAddress->phone=$oAddress['phone'];
       $mAddress->phone_mobile=$oAddress['phone_mobile'];
       $mAddress->dni=$oAddress['dni'];
       $mAddress->company=$oAddress['company'];
       $mAddress->date_add=Carbon::now();
       $mAddress->date_upd=Carbon::now();
       $mAddress->save();

    }
    public function loginCustomer(Request $request)
    {
        $eLoginCustomer = $request;
        $ologinCustomer = DB::table('customer')
                            ->where('email','=',$eLoginCustomer['email']);
                            if(isset($eLoginCustomer["login_media"]) && $eLoginCustomer["login_media"] == "form")
                                $ologinCustomer->where('passwd','=',md5($eLoginCustomer['passwd']));
                            $ologinCustomer->where('active','=',1);
                            $ologinCustomer->select(
                                    'id_customer',
                                    'email',
                                    'id_customer',
                                    'firstname',
                                    'lastname',
                                    DB::raw('1 as is_logged'));
        $customerLogin =    $ologinCustomer->first();
        return response()->json($customerLogin,200);
    }
    public function loginSocial(Request $request)
    {
        $eloginSocialCustomer = $request;
        $ologinSocialCustomer = DB::table('customer')
                                ->where('email','=',$eloginSocialCustomer['email'])
                                ->count();
                                //->get();
            if ($ologinSocialCustomer > 0){
                return response()->json(array('resp'=>true),200);    
            }else{
                return response()->json(array('resp'=> false),200);
            }

            
    }
    
}
