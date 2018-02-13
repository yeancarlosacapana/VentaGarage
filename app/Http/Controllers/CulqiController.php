<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Culqi\Culqi;

class CulqiController extends Controller
{
    //
    private $key_secret;

    public function __construct(){
        $this->key_secret =  \Config::get('constants.secret_key_culqi');
    }
    
    public function payout(Request $request){

        $oCulqi = $request["culqi"];
        $eProduct = $request["product"];
        $culqi = new Culqi(array('api_key' => $this->key_secret));
        try{
            // Creamos Cargo a una tarjeta
            $charge = $culqi->Charges->create(
                [
                    "amount" => (int)$oCulqi["cost"] * 100,
                    "currency_code" => "PEN",
                    "email" => $oCulqi["email"],
                    "source_id" => $oCulqi["id"],
                    "antifraud_details" => [
                        "address" => "Calle Narciso de la molina",
                        "address_city" => "Lima",
                        "country_code" => "PE",
                        "first_name" => "Ruelas",
                        "last_name" => "Liz",
                        "phone_number" => 123456789
                    ],
                    "capture" => true,
                    "description" => "Venta de ".$eProduct["productLang"]["name"],
                    "installments" => 0,
                ]                   
            );
            return response()->json($charge, 200);
        }catch(\Exception $ex){
            return response()->json($ex->getMessage(),500);
        }
    }
}
