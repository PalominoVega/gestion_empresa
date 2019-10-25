<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Model\Notificacion;
use App\Model\Producto;
use Carbon\Carbon;

class VencimientoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:vencimiento';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifica a las 8 am si encuentra productos con fecha de vencimiento proxima a 1 mes con recordatorio cada 7 días';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $fecha_existencia=Carbon::now()->subDays(7)->format('Y-m-d');
        $fecha=Carbon::now()->addMonth()->format('Y-m-d');
        $productos=Producto::join('lote','producto.id','=','lote.producto_id')
                    ->where(DB::raw('cantidad - consumo'),'<>',0)
                    ->where('fecha_vencimiento','<',$fecha)
                    ->where('estado','0')
                    ->select('producto.id','empresa_id','nombre','fecha_vencimiento',DB::raw('lote.id as lote_id'))
                    ->get();
        // echo json_encode($productos);
        foreach ($productos as $key => $value) {
            $notificacion=Notificacion::where('referencia_id',$value->lote_id)
                ->where(DB::raw('DATE(created_at)'),'>',$fecha_existencia)
                ->first();
            // echo json_encode($notificacion);    
            if ($notificacion==null) {
                $empresa_id=$value->empresa_id;
                $apiKey="AIzaSyCUpg8GjUxs56rQyDCO_yEu46Vmbxfpapg";
                $fields=array('to'=>'/topics/sistema-general-'.$empresa_id,
                'notification'=>array(
                    'title'=>"GESTION DE EMPRESA",
                    'body'=>"Producto por vencer ".$value->nombre,
                    'icon'=>'http://127.0.0.1:8000/img/logo.png',
                    'click_action'=>"https://www.conoceatuconductor.com/lote"
                ));
                $url='https://fcm.googleapis.com/fcm/send';
                $headers=array('Authorization: key='.$apiKey,'Content-Type: application/json');
                $curl=curl_init();
                curl_setopt($curl,CURLOPT_URL,$url);
                curl_setopt($curl,CURLOPT_POST,true);
                curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
                curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
                curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
                curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($fields));
                echo curl_exec($curl);
                curl_close($curl);
                $notificacion=new Notificacion();
                $notificacion->tipo="Vencimiento";
                $notificacion->descripcion="Producto por vencer ".$value->nombre;
                $notificacion->estado="0";
                $notificacion->referencia_id=$value->lote_id;
                $notificacion->empresa_id=$empresa_id;
                $notificacion->save();
            }
        }
    }
}
