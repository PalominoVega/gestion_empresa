<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Model\Lote;
use App\Model\Producto;
use App\Model\Kardex;
use Carbon\Carbon;
use App\Logica\KardexOperaciones;

class KardexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $fecha_anterior=Carbon::parse($request->fecha)->subMonth()->endOfMonth()->format('Y-m-d');
        $fecha=explode('-',$request->fecha);
        $anio=$fecha[0];
        $mes=$fecha[1];
        $kardex=Producto::with([
                'kardex' => function($query) use ($mes,$anio){
                    $query->where(DB::raw('MONTH(fecha)'),$mes)
                        ->where(DB::raw('YEAR(fecha)'),$anio);
                        // ->where('concepto','<>','Venta Anulada'); 
                }
            ])->where('empresa_id',session('empresa_id'))
            ->get();
        foreach ($kardex as $key => $item) {
            $item->anterior=Kardex::where('fecha','<=',$fecha_anterior)
                ->where('producto_id',$item->id)
                ->where('concepto','<>','Venta Anulada')
                ->orderBy('fecha','DESC')
                ->orderBy('id','DESC')
                ->first();
        }
        

        return response()->json($kardex);
    }

    /**
     * 
     */
    public function stock(Request $request)
    {
        $dia=($request->dia!=null) ? $request->dia : Carbon::now()->format('Y-m-d');
        $kardex=Producto::where('empresa_id',session('empresa_id'))
                ->get();
        foreach ($kardex as $key => $item) {
            $stock=Kardex::where('producto_id',$item->id)
                ->where('fecha','<=',$dia)
                ->where('concepto','<>','Venta Anulada')
                ->orderBy('id','DESC')
                ->first();
            $item->stock=($stock==null) ? 0:$stock->stock;
            $item->precio_promedio=($stock==null) ? 0:$stock->precio_promedio;
        }
        return response()->json($kardex);
    }

    public function lote(Request $request)
    {
        $productos=Producto::join('lote','producto.id','=','lote.producto_id')
                    ->where('empresa_id',session('empresa_id'))
                    ->where(DB::raw('cantidad - consumo'),'<>',0)
                    ->get();
        return response()->json($productos);
    }
    public function reorden(){
        $productos=Producto::join(DB::raw('( SELECT producto_id, SUM(lote.cantidad - lote.consumo) AS stock FROM lote GROUP BY producto_id ) as l'),
                            'l.producto_id','=','producto.id')
                    ->where('stock','<=',DB::raw('punto_reorden'))
                    ->where('empresa_id',session('empresa_id'))                    
                    ->select('producto.id','producto.codigo','nombre','stock','punto_reorden','empresa_id')
                    ->get();
        return response()->json($productos);
    }
    public function salidas(){
        $productos=Producto::join('kardex','kardex.producto_id','=','producto.id')
            ->where('tipo','Salida')
            ->groupBy('kardex.producto_id','producto.nombre',DB::raw('MONTH(kardex.fecha)'))
            ->orderBy('kardex.producto_id','ASC')
            ->select('kardex.producto_id','producto.nombre',DB::raw('MONTH(kardex.fecha) as mes'),DB::raw('SUM(cantidad) as cantidad'))
            ->get();
        /**
         * Variables axiliares
         */
        $producto_id=0;
        $list_productos=[];
        $salidas=[];
        foreach ($productos as $key => $producto) {
            if ($producto_id!=$producto->producto_id) {
                array_push($list_productos,[
                    "nombre"=> $producto->nombre,
                    "producto_id"=> $producto->producto_id
                ]);
                $producto_id=$producto->producto_id;
                $salidas=[
                    ["mes"=> 1,"cantidad"=> 0],
                    ["mes"=> 2,"cantidad"=> 0],
                    ["mes"=> 3,"cantidad"=> 0],
                    ["mes"=> 4,"cantidad"=> 0],
                    ["mes"=> 5,"cantidad"=> 0],
                    ["mes"=> 6,"cantidad"=> 0],
                    ["mes"=> 7,"cantidad"=> 0],
                    ["mes"=> 8,"cantidad"=> 0],
                    ["mes"=> 9,"cantidad"=> 0],
                    ["mes"=> 10,"cantidad"=> 0],
                    ["mes"=> 11,"cantidad"=> 0],
                    ["mes"=> 12,"cantidad"=> 0],
                ];
            }
            $salidas[$producto->mes-1]["cantidad"]= (int)$producto->cantidad;
            $list_productos[\count($list_productos)-1]["salidas"]=$salidas;
        }
        return response()->json($list_productos);
        // dd(json_encode($list_productos));
        // SELECT kardex.producto_id, producto.nombre, MONTH(kardex.fecha), SUM(cantidad) FROM `kardex` INNER JOIN producto ON kardex.producto_id=producto.id WHERE tipo = "Salida" GROUP BY kardex.producto_id, MONTH(kardex.fecha),producto.nombre
    }
}
