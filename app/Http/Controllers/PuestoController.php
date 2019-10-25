<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Puesto;

class PuestoController extends Controller
{
    public function index()
    {
        $Puestos=Puesto::where('empresa_id',session('empresa_id'))->get();
        return response()->json($Puestos);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $Puesto=new Puesto();
            $Puesto->nombre=$request->nombre;
            $Puesto->empresa_id=session('empresa_id');
            $Puesto->save();
            DB::commit();
            return response()->json([
                "status"    =>  "OK",
                "data"      =>  $Puesto
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
        $Puesto= Puesto::where('id',$id)->first();
        return response()->json($Puesto);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $Puesto=Puesto::where('id',$id)->first();
            $Puesto->nombre=$request->nombre;
            $Puesto->save();
            DB::commit();
            return response()->json([
                "status"    =>  "OK",
                "data"      =>  $Puesto
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
