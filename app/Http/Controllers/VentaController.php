<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Model\Venta;
use App\Model\DetalleVenta;
use App\Model\Lote;
use App\Model\Cliente;
use App\Model\Producto;
use App\Model\Kardex;
use App\Model\MovimientoCaja;
use App\Logica\Facturacion;

class VentaController extends Controller
{
    /**
     */
    public function index(Request $request)
    {
        $ventas=Venta::leftJoin('cliente','cliente.id','=','venta.cliente_id')
                ->with('user')
                ->where('venta.empresa_id',session('empresa_id'))
                ->where('nombre','LIKE','%'.$request->nombre_dni.'%')
                ->orWhere('documento','LIKE','%'.$request->nombre_dni.'%')
                ->select('venta.*','cliente.nombre','cliente.documento')
                ->orderBy('id','DESC')
                ->paginate(8);
        return response()->json($ventas);
    }
    /**
     * Guardar nueva Compra
     */
    public function store(Request $request)
    {
        /**
         * MANEJO DE ERRORES
         * Sin Items de compra
         */
        if($request->items==null) return response()->json(["status"=>"ERROR","data"=>"La compra no contiene Items"]);
        /**
         * OPERACIÓN
         */
        DB::beginTransaction();
        $cliente_id=null;
        if ($request->doc_identidad!=null) {
            $cliente=Cliente::where('documento',$request->doc_identidad)
                        ->where('empresa_id',session('empresa_id'))
                        ->first();
            if ($cliente==null) {
                $cliente=new Cliente();
                $cliente->documento=$request->doc_identidad;
                $cliente->nombre=$request->nombre;
                $cliente->tipo=(strlen($request->doc_identidad)==8) ? 'Persona': 'Empresa';
                $cliente->empresa_id=session('empresa_id');
                $cliente->save();
            }
            $cliente_id=$cliente->id;
        }

        
        /**
         * Siguiente Código
         * -doc : documento
         */
        
        $empresa=$request->empresa;
        switch ($request->tipo) {
            case 'Boleta':
                $serie=($empresa->serie_boleta==null) ? 'B': $empresa->serie_boleta;
                $tipo_doc="2";
                break;
            case 'Factura':
                $serie=($empresa->serie_factura==null) ? 'F' : $empresa->serie_factura;
                $tipo_doc="1";
                break;
            case 'Ticket':
                $serie='T';
                $tipo_doc="3";
                break;
            default:

                break;
        }
        
        $siguiente=Venta::select(DB::raw('count(id)+1 as siguiente'))
            ->where('empresa_id',session('empresa_id'))
            ->where('serie',$serie)
            ->first()
            ->siguiente;
        $venta=new Venta();
        $venta->serie=$serie;
        $venta->correlativo=$siguiente;
        $venta->total=$request->total;
        $venta->igv=round(($request->total*0.18/1.18),3);
        $venta->tipo_documento=$tipo_doc;
        $venta->cliente_id=$cliente_id;
        $venta->empresa_id=session('empresa_id');
        $venta->user_id=session('user_id');
        $encontrado=MovimientoCaja::where('empresa_id',session('empresa_id'))
            ->where('estado','0')
            ->first();
        $venta->movimiento_caja_id=($encontrado!=null) ? $encontrado->id : null;
        $venta->save();
        // dd($venta);
        try{
            foreach ($request->items as $key => $item) {
                /**
                 * Obtener Lote a Actualizar
                 */
                $cantidad=$item['cantidad'];
                $detalle=new DetalleVenta();
                $detalle->cantidad=$cantidad;
                $detalle->precio=$item['precio'];
                $detalle->producto_id=$item['producto_id'];
                $detalle->venta_id=$venta->id;
                $detalle->save();
                $producto=Producto::where('id',$item['producto_id'])->first();
                while ($cantidad > 0) {
                    $cantidad_registrar=0;
                    
                    $lote=Lote::where('producto_id',$item['producto_id'])
                        ->where(DB::raw('cantidad - consumo'),'<>',0)
                        ->first();
                    if($lote==null) return response()->json(["status"=>"ERROR","data"=>"No cuenta con Stock en el producto ".$producto->nombre]);
                    
                    if (($lote->cantidad - $lote->consumo)>=$cantidad) {
                        $cantidad_registrar=$cantidad;    
                    }else{
                        $cantidad_registrar=($lote->cantidad - $lote->consumo);    
                    }
                    $lote->consumo=$lote->consumo+$cantidad_registrar;
                    $lote->save();
                    /** 
                     * Obtener Kardex anterior y Registra uno nuevo
                     */
                    $kardex_anterior=Kardex::where('producto_id',$lote->producto_id)
                                        ->where('concepto','<>','Venta Anulada')
                                        ->orderBy('id','DESC')
                                        ->first();
                    $kardex=new Kardex();
                    $kardex->cantidad=$cantidad_registrar;
                    $kardex->stock=$kardex_anterior->stock - $cantidad_registrar;
                    $kardex->precio_promedio=$kardex_anterior->precio_promedio;
                    $kardex->precio_lote=$lote->precio;
                    $kardex->producto_id=$lote->producto_id;
                    $kardex->empresa_id=session('empresa_id');
                    $kardex->lote_id=$lote->id;
                    $kardex->tipo="Salida";
                    $kardex->concepto="Venta";
                    $kardex->referencia_id=$venta->id;
                    $kardex->fecha=Carbon::now();
                    $kardex->save();
                    /**
                     * Resta para seguir el bucle o terminarlo
                     */
                    $cantidad=$cantidad-$cantidad_registrar;
                }
            }

            /**
             *  Facturación  
             */
            if ($empresa->url_facturacion!=null&&$request->tipo!='Ticket') {
                $leer_respuesta=Facturacion::new($cliente,$venta,$request->empresa->url_facturacion,$request->empresa->token_facturacion,$request->email);
                if (isset($leer_respuesta['errors'])) {
                    DB::rollback();
                    return response()->json([
                        "status"    =>  "ERROR",
                        "data"      =>  $leer_respuesta['errors']
                    ]);
                }
            }            
            
            DB::commit();
            return response()->json([
                "status"    =>  "OK",
                "data"      =>  $venta
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
     */
    public function show($id)
    {
        $venta=Venta::with('detalles')->with('empresa')->with('cliente')->where('id',$id)->first();
        foreach ($venta->detalles as $key => $detalle) {
            $detalle->producto=Producto::where('id',$detalle->producto_id)->first();
        }
        return response()->json($venta);
    }

    public function facturacion(Request $request,$id){
        $venta=Venta::where('id',$id)->first();
        $consulta=Facturacion::consulta($venta->tipo_documento,$venta->serie,$venta->correlativo,$request->empresa->url_facturacion,$request->empresa->token_facturacion);
        return response()->json($consulta);
    }

    public function anular(Request $request,$id)
    {
        DB::beginTransaction();
        $fecha=Carbon::now()->format('Y-m-d');
        $venta=Venta::where('id',$id)->first();

        // if (Carbon::parse($venta->created_at)->format('Y-m-d')==$fecha) {
            $venta->estado='1';
            $venta->save();
            $detalles=DetalleVenta::where('venta_id',$venta->id)->get();
            foreach ($detalles as $key => $detalle) {

                $kardexs=Kardex::where('producto_id',$detalle->producto_id)
                    ->where('concepto','Venta')
                    ->where('referencia_id',$detalle->venta_id)
                    ->get();
                foreach ($kardexs as $key => $kardex) {
                    $kardex->concepto='Venta Anulada';
                    $kardex->save();
                    
                    $lote=Lote::where('id',$kardex->lote_id)->where('producto_id',$kardex->producto_id)->first();
                    $lote->consumo=$lote->consumo-$kardex->cantidad;
                    $lote->save();
                }    
            }

            /**
             * Anular Facturacion
             */
            if ($venta->hash!=null) {
                $consulta=Facturacion::anular($venta->tipo_documento,$venta->serie,$venta->correlativo,$request->empresa->url_facturacion,$request->empresa->token_facturacion);
                if (isset($consulta->aceptada_por_sunat)) {
                    /**
                     * Respuesta
                     */
                    return response()->json(["status"=>  "OK","data"=>"Venta anulada"]);
                }
                
                if (isset($consulta->errors)) {
                    /**
                     * Respuesta
                     */
                    DB::rollback();
                    return response()->json(["status"=>"ERROR","data"=>$consulta->errors]);
                }
            }
                DB::commit();
            return response()->json([
                "status"    =>  "OK",
                "data"      =>  "Venta anulada"
            ]);
        // }else{
        //     DB::rollback();
        //     return response()->json([
        //         "status"    => "ERROR",
        //         "data"      =>  "Venta no Anulada, esta fuera de Fecha."
        //     ]);
        // }
    }

    public function consulta_anulacion(Request $request,$id){
        $venta=Venta::where('id',$id)->first();
        $consulta=Facturacion::consultaAnulacion($venta->tipo_documento,$venta->serie,$venta->correlativo,$request->empresa->url_facturacion,$request->empresa->token_facturacion);
        return response()->json($consulta);
    }
}
