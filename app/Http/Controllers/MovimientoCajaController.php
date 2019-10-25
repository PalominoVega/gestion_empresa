<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Model\MovimientoCaja;
use App\Model\Venta;

class MovimientoCajaController extends Controller
{

    public function index()
    {
        $movimientos=MovimientoCaja::with('caja')->with('user')
            ->where('empresa_id',session('empresa_id'))
            ->orderBy('id','DESC')
            ->get();
        return response()->json($movimientos);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $caja=new MovimientoCaja();
        $caja->caja_id=$request->caja_id;
        $caja->user_id=session('user_id');
        $caja->total=0;
        $caja->estado='0';
        $caja->empresa_id=session('empresa_id');
        $caja->save();
        return response()->json([
            "status"    =>  "OK",
            "data"      =>  $caja
        ]);
    }
    public function cierre(){
        $encontrado=MovimientoCaja::where('empresa_id',session('empresa_id'))
            ->where('estado','0')
            ->first();
        $ventas=Venta::where('movimiento_caja_id',$encontrado->id)
            ->where('estado','0')
            ->get();
        $total=0;
        foreach ($ventas as $key => $venta) {
            $total+=$venta->total;
        }
        $encontrado->total=$total;
        $encontrado->estado="1";
        $encontrado->save();
        return response()->json([
            "status"    =>  "OK",
            "data"      =>  $encontrado
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function actual(){
        $encontrado=MovimientoCaja::where('empresa_id',session('empresa_id'))
            ->where('estado','0')
            ->first();
        // if ($encontrado!=null) {
            
        // }
        return response()->json([
            "status" => ($encontrado!=null)? true : false
        ]);
        // dd($encontrado);
    } 
}
