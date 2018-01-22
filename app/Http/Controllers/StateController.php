<?php

namespace App\Http\Controllers;

use App\State;
use DB;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index()
    {
        $state = DB::table('state')
        ->where('active' , '>' , 0)
        ->get();
        
        return response()->json($state,200);
    }
}
