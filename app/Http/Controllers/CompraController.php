<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Model\Compra;
use App\Model\Lote;
use App\Model\Producto;
use App\Model\Kardex;

class CompraController extends Controller
{
    /**
     */
    public function index()
    {
        $compras=Compra::with('proveedor')
            ->where('empresa_id',session('empresa_id'))
            ->orderBy('id','DESC')
            ->get();
        return response()->json($compras);
    }
    /**
     * Guardar nueva Compra
     */
    public function store(Request $request)
    {
        /**¨
         * Sin Items de compra
         */
        if($request->proveedor_id==null){
            return response()->json(["status"=>"ERROR","data"=>"Selecciones un proveedor"]);
        }
        if($request->documento==null){
            return response()->json(["status"=>"ERROR","data"=>"EL documento es necesario"]);
        }
        if($request->items==null){
            return response()->json(["status"=>"ERROR","data"=>"La compra no contiene Items"]);
        }
        DB::beginTransaction();
        $compra=new Compra();
        $compra->documento=$request->documento;
        $compra->total=$request->total;
        $compra->proveedor_id=$request->proveedor_id;
        $compra->empresa_id=session('empresa_id');
        $compra->save();
        try{
            foreach ($request->items as $key => $item) {
                /**
                 * Comprobacion de Existencia de producto
                 */
                $producto=Producto::where('id',$item['producto_id'])
                    ->where('empresa_id',session('empresa_id'))
                    ->first();
                if ($producto==null) {
                    return response()->json([
                        "status"    =>  "ERROR",
                        "data"      =>  "Producto id ".$item['producto_id']." no pertenece a esta cuenta."
                    ]);
                }
                /**
                 * Creación de Lote
                 */
                $lote=new Lote();
                $lote->precio=$item['precio'];
                $lote->cantidad=$item['cantidad'];
                $lote->consumo=0;
                $lote->fecha_vencimiento=isset($item['fecha_vencimiento']) ? $item['fecha_vencimiento'] : null ;
                $lote->codigo_lote=isset($item['codigo_lote']) ? $item['codigo_lote'] : null ;
                $lote->compra_id=$compra->id;
                $lote->producto_id=$item['producto_id'];
                $lote->save();

                $kardex_anterior=Kardex::where('producto_id',$item['producto_id'])
                                    ->orderBy('id','DESC')
                                    ->first();
                $kardex=new Kardex();
                $kardex->cantidad=$item['cantidad'];
                $kardex->stock=($kardex_anterior==null) ? $item['cantidad'] : ($kardex_anterior->stock+$item['cantidad']);
                if ($kardex_anterior==null) {
                    $kardex->precio_promedio=$item['precio'];
                }else{
                    $kardex->precio_promedio=(($item['precio']*$item['cantidad'])+($kardex_anterior->precio_promedio*$kardex_anterior->stock))/($item['cantidad']+$kardex_anterior->stock);
                }
                $kardex->precio_lote=$item['precio'];
                $kardex->producto_id=$item['producto_id'];
                $kardex->lote_id=$lote->id;
                $kardex->empresa_id=session('empresa_id');
                $kardex->tipo="Entrada";
                $kardex->fecha=Carbon::now();
                $kardex->concepto="Compra";
                $kardex->referencia_id=$compra->id;
                $kardex->save();
            }
            DB::commit();
            return response()->json([
                "status"    =>  "OK",
                "data"      =>  "Compra registrada."
            ]);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                "status"    =>  "ERROR",
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
}
