<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Culqi\Culqi;
use Culqi\Error;

class CulqiController extends Controller
{
    private $key_secret = "sk_test_iVOFJoIQxdxVutk1";
    //
    public function payout(Request $request){

        $oCulqi = $request["culqi"];
        $eProduct = $request["product"];
        $culqi = new Culqi(array('api_key' => $this->key_secret));
        //$culqi->setEnv("INTEG");
        $token = $oCulqi["id"];
        $email = $oCulqi["email"];
        try{
            // Creamos Cargo a una tarjeta
            $cargo = $culqi->Charges->create(
                array(
                    "amount" => $eProduct["price"],
                    "capture" => true,
                    "currency_code" => "PEN",
                    "description" => "Venta de ".$eProduct["productLang"]["name"],
                    "email" => $email,
                    "installments" => 0,
                    "source_id" => $token
                )
            );
            echo json_encode($cargo);
        }catch(Culqi\Error\UnhandledError $error){
            var_dump($error);
        }
        /*try{
            
            die();
            //$cargo = $this->validateCharge($oCulqi,$eProduct);
            //return response()->json($cargo, 200);
        }catch(Exception $e){
            var_dump($e->getMessage());
            //return response()->json($e,500);
        }*/
    }

    private function validateCharge($culqi_,$product){
        
        return $cargo;
    }
}
