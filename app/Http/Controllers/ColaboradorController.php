<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Model\Colaborador;

class ColaboradorController extends Controller
{
    public function index()
    {
        $Colaboradors=Colaborador::where('empresa_id',session('empresa_id'))->get();
        return response()->json($Colaboradors);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $Colaborador=new Colaborador();
            $Colaborador->dni=$request->dni;
            $Colaborador->nombre=$request->nombre;
            $Colaborador->apellido=$request->apellido;
            $Colaborador->celular=$request->celular;
            $Colaborador->salario=$request->salario;
            $Colaborador->dia_pago=$request->dia_pago;
            $Colaborador->puesto_id=$request->puesto_id;
            $Colaborador->empresa_id=session('empresa_id');
            $Colaborador->save();
            DB::commit();
            return response()->json([
                "status"    =>  "OK",
                "data"      =>  $Colaborador
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "status"    =>  "DANGER",
                "data"      =>  $e->getMessage()
            ]);
        }    
    }
    public function show($id){
        $Colaborador= Colaborador::where('id',$id)->first();
        return response()->json($Colaborador);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $Colaborador=Colaborador::where('id',$id)->first();
            $Colaborador->dni=$request->dni;
            $Colaborador->nombre=$request->nombre;
            $Colaborador->apellido=$request->apellido;
            $Colaborador->celular=$request->celular;
            $Colaborador->salario=$request->salario;
            $Colaborador->dia_pago=$request->dia_pago;
            $Colaborador->puesto_id=$request->puesto_id;
            $Colaborador->save();
            DB::commit();
            return response()->json([
                "status"    =>  "OK",
                "data"      =>  $Colaborador
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "status"    =>  "DANGER",
                "data"      =>  $e->getMessage()
            ]);
        }
    }
}
