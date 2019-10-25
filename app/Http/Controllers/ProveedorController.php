<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\Proveedor;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $proveedores=Proveedor::where('empresa_id',session('empresa_id'))->get();
        return response()->json($proveedores);
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
        try {
            /**
             * Siguiente Código
             */
            // $siguiente=Producto::select(DB::raw('count(id)+1 as siguiente'))->where('empresa_id',session('empresa_id'))
            //     ->first()->siguiente;
            /**
             * Operación de Registro
             */
            $proveedor=new Proveedor();
            $proveedor->ruc=$request->ruc;
            $proveedor->nombre=$request->nombre;
            $proveedor->numero=$request->numero;
            $proveedor->email=$request->email;
            $proveedor->empresa_id=session('empresa_id');
            $proveedor->save();

            return response()->json([
                "status"    =>  "OK",
                "data"      =>  ($request->data==true) ? $proveedor : "Proveedor Guardado"
            ]);
        } catch (\Exception $e) {
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
}
