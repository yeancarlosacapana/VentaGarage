<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Provincia;
use App\State;
use DB;
class ProvinciaController extends Controller
{
    public function index($id_state)
    {
        $provincia = DB::table('provincia')
        ->where('id_departamento','=',$id_state)
        ->get();
        return response()->json($provincia,200);        
    }
}
