<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Model\Concepto;

class ConceptoController extends Controller
{
    /**
     * listar usuario
     */
    public function index()
    {
        $concepto=Concepto::where('empresa_id',session('empresa_id'))->get();
        return response()->json($concepto);
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
        DB::beginTransaction();

        try {
            $concepto= new Concepto();
            $concepto->nombre=$request->nombre;
            $concepto->tipo=$request->tipo;
            $concepto->empresa_id=session('empresa_id');
            $concepto->save();
            DB::commit();
            return response()->json([
                "status"    =>  "OK",
                "data"      =>  ($request->data==true) ? $concepto : "Concepto Registrado",
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "status"    =>  "DANGER",
                "data"      =>  $e->getMessage()
            ]);
        }
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
        DB::beginTransaction();

        try {
            $concepto= Concepto::where('id',$id)->first();
            $concepto->nombre=$request->nombre;
            // $concepto->tipo=$request->tipo;
            // $concepto->precio=$request->precio;
            $concepto->save();
            DB::commit();
            return response()->json([
                "status"    =>  "OK",
                "data"      =>  ($request->data==true) ? $concepto : "Concepto Actualizado",
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "status"    =>  "DANGER",
                "data"      =>  $e->getMessage()
            ]);
        }
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

    public function cambiar_Estado($id)
    {
        DB::beginTransaction();

        try {

            $concepto= Concepto::where('id',$id)->first();

            $movimiento=Movimiento::where('concepto_id',$id)->first();

            if(is_null($movimiento)){
                //eliminar
                $concepto->delete();
                $estado ='Concepto eliminado';
            }else{
                $estado = ($concepto->estado=='0') ? '1': '0'; //saber el estado actual y cambiarlo
                
                $concepto->estado=$estado;
                $concepto->save();
    
                $estado = ($concepto->estado=='0') ? 'Concepto desactivado': 'Concepto activado'; //saber el estado cambiado para mostrar el mensaje
            }

            DB::commit();
            return response()->json([
                "status"    =>  "OK",
                "data"      =>  ($request->data==true) ? $concepto : $estado,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "status"    =>  "DANGER",
                "data"      =>  $e->getMessage()
            ]);
        }
    }

    public function listar(Request $request)
    {
        if($request->tipo=='INGRESO'){
            $concepto=Concepto::where('tipo','Ingreso')->where('empresa_id',session('empresa_id'))->get();
        }else{
            $concepto=Concepto::where('tipo','Egreso')->where('empresa_id',session('empresa_id'))->get();
        }
        return response()->json($concepto);
    }
}
