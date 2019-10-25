<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Model\Venta;
use App\Model\Compra;

class ResumenController extends Controller
{
    public function index(Request $request){
        $fecha=($request->mes==null) ? Carbon::now()->format('Y-m') : Carbon::parse($request->mes)->format('Y-m');
        $fecha_string=explode('-',$fecha);
        $anio=$fecha_string[0];
        $mes=$fecha_string[1];
        
        $ventas_totales=0;
        $ventas=Venta::select(DB::raw('empresa_id,SUM(total) as total'))
                ->where('empresa_id',session('empresa_id'))
                ->where(DB::raw('MONTH(created_at)'),$mes)
                ->where(DB::raw('YEAR(created_at)'),$anio)
                ->groupBy('empresa_id')
                ->first();
        
        if ($ventas!=null) {
            $ventas_totales=$ventas->total;
        }
        $compras_totales=0;
        $compras=Compra::select(DB::raw('empresa_id,SUM(total) as total'))
                ->where('empresa_id',session('empresa_id'))
                ->where(DB::raw('MONTH(created_at)'),$mes)
                ->where(DB::raw('YEAR(created_at)'),$anio)
                ->groupBy('empresa_id')
                ->first();
        if ($compras!=null) {
            $compras_totales=$compras->total;
        }
        
        return response()->json([
            "fecha"=>$fecha,
            "ventas"=>$ventas_totales,
            "compras"=>$compras_totales
        ]);
    }
}
