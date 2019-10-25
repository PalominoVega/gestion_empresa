<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Producto;
use App\Http\Requests\ProductoValidation;

class ProductoController extends Controller
{
    /**
     * Listar Productos
     */
    public function index(Request $request)
    {
        if ($request->all==true) {
            $producto=Producto::where('empresa_id',session('empresa_id'))->get();
        }else {
            $producto=Producto::where('empresa_id',session('empresa_id'))->paginate(5);
        }
        return response()->json($producto);
    }

    /**
     * Guardar Producto
     */
    public function store(ProductoValidation $request)
    {
        try {
            /**
             * Siguiente Código
             */
            $siguiente=Producto::select(DB::raw('count(id)+1 as siguiente'))->where('empresa_id',session('empresa_id'))
                ->first()->siguiente;
            /**
             * Operación de Registro
             */
            $producto=new Producto();
            $producto->codigo=str_pad($siguiente,5, "0", STR_PAD_LEFT);
            $producto->nombre=$request->nombre;
            $producto->precio=$request->precio;
            $producto->punto_reorden=$request->punto_reorden;
            $producto->estado='0';
            $producto->empresa_id=session('empresa_id');
            $producto->save();

            return response()->json([
                "status"    =>  "OK",
                "data"      =>  ($request->data==true) ? $producto : "Producto Guardado"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status"    =>  "DANGER",
                "data"      =>  $e->getMessage()
            ]);
        }
    }

    /**
     * Consultar un Producto
     */
    public function show($id)
    {
        $producto=Producto::where('id',$id)->first();
        return response()->json($producto);
    }

    /**
     * Actualizar Producto
     */
    public function update(Request $request, $id)
    {
        try {
            $producto=Producto::where('id',$id)->first();
            $producto->nombre=($request->has('nombre')) ? $request->nombre: $producto->nombre;
            $producto->precio=($request->has('precio')) ? $request->precio: $producto->precio;
            $producto->punto_reorden=($request->has('punto_reorden')) ? $request->punto_reorden: $producto->punto_reorden;
            if ($request->has('estado')&&!$request->has('nombre')) {
                $producto->estado=($producto->estado=='0' ) ? '1' : '0';
            }
            $producto->save();
            return response()->json([
                    "status"    =>  "OK",
                    "data"      =>  ($request->d==true) ? $producto : "Producto Actualizado"
                ]);
        } catch (\Exception $e) {
            return response()->json([
                    "status"    =>  "DANGER",
                    "data"      =>  $e->getMessage()
                ]);
        }
    }
}
