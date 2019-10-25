<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Model\Caja;

class CajaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cajas=Caja::where('empresa_id',session('empresa_id'))->get();
        return response()->json($cajas);
    }

    public function store(Request $request)
    {
        $caja=new Caja();
        $caja->nombre=$request->nombre;
        $caja->empresa_id=session('empresa_id');
        $caja->save();
        return response()->json([
            "status"    =>  "OK",
            "data"      =>  $caja
        ]);
    }
    public function libre(){
        $cajas_ocupadas=DB::table('movimiento_caja')
                        ->where('estado','0')
                        ->get();
        $array_in=[];
        foreach ($cajas_ocupadas as $key => $value) {
            array_push($array_in,$value->caja_id);
        }
        $cajas=Caja::where('empresa_id',session('empresa_id'))
            ->whereNotIn('caja.id',$array_in)    
            ->get();
        return response()->json($cajas);
    }
}
