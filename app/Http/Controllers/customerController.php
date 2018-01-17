<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Address;
use Carbon\Carbon;
use DB;
class customerController extends Controller
{
    public function registerCustomer(Request $request)
    {
       $eCustomer = $request;
       $mCustomer = new Customer();
       $mCustomer->id_gender = 0;
       $mCustomer->company = $eCustomer["company"];
       $mCustomer->firstname = $eCustomer["firstname"];
       $mCustomer->lastname = $eCustomer["lastname"];
       $mCustomer->email = $eCustomer["email"];
       $mCustomer->passwd = $eCustomer["passwd"];
       $mCustomer->date_add=Carbon::now();
       $mCustomer->date_upd=Carbon::now();
       $mCustomer->save();

       $oAddress = $eCustomer['address'];
       $this->grabarAddres($oAddress,$mCustomer->id_customer);


    }
    public function grabarAddres($oAddress,$id_customer)
    {
       $mAddress = new Address();
       $mAddress->id_country = 171;
       $mAddress->id_zone = 6;
       $mAddress->id_customer = $id_customer;
       $mAddress->alias='direccion'.$id_customer;
       $mAddress->company=$oAddress['company'];
       $mAddress->lastname=$oAddress['lastname'];
       $mAddress->firstname=$oAddress['firstname'];
       $mAddress->address1=$oAddress['address1'];
       $mAddress->city=$oAddress['city'];
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
                        ->where('email','=',$eLoginCustomer['email'])
                        ->where('passwd','=',$eLoginCustomer['passwd'])
                        ->where('active','=',1)
                        ->select('email','id_customer','firstname','lastname',DB::raw('1 as is_logged'))
                        ->first();
                        
        return response()->json($ologinCustomer,200);
    }
}
