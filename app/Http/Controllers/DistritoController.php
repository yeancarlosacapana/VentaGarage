<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Distrito;
use App\Provincia;
use DB;
class DistritoController extends Controller
{
    public function index($id_provincia)
    {
        $distrito = DB::table('distrito')
        ->where('id_provincia','=',$id_provincia)
        ->get();
        return response()->json($distrito, 200);
    }
}
