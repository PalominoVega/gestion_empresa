<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Notificacion;

class NotificacionController extends Controller
{
    public function index(){
        $notificaciones=Notificacion::where('empresa_id',session('empresa_id'))
            ->orderBy('id','DESC')
            ->get();
        return response()->json($notificaciones);
    }

    public function store(Request $request)
    {
        try {
            $notificaciones=Notificacion::where('empresa_id',session('empresa_id'))
                        ->where('estado','0')
                        ->get();
            foreach ($notificaciones as $key => $notificacion) {
                $notificacion->estado='1';
                $notificacion->save();
            }
            return response()->json([
                    "status"    =>  "OK",
                    "data"      =>  "Notificaciones Actualizadas"
                ]);
        } catch (\Exception $e) {
            return response()->json([
                    "status"    =>  "DANGER",
                    "data"      =>  $e->getMessage()
                ]);
        }
    }
}
