<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Model\Notificacion;
use App\Model\Producto;
use Carbon\Carbon;

class ReordenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:reorden';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifica cada hora si encuentra productos con stock bajo , vuelve a recordarlo despues de 7 dias.';

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
        $fecha=Carbon::now()->subDays(7)->format('Y-m-d');
        $producto=Producto::join(DB::raw('( SELECT producto_id, SUM(lote.cantidad - lote.consumo) AS stock FROM lote GROUP BY producto_id ) as l'),
                            'l.producto_id','=','producto.id')
                    ->where('stock','<=',DB::raw('punto_reorden'))
                    ->where('estado','0')
                    ->select('producto.id','nombre','stock','punto_reorden','empresa_id')
                    ->get();
        foreach ($producto as $key => $value) {
            $notificacion=Notificacion::where('referencia_id',$value->id)
                ->where(DB::raw('DATE(created_at)'),'>',$fecha)
                ->first();
            if ($notificacion==null) {
                $empresa_id=$value->empresa_id;
                $apiKey="AIzaSyCUpg8GjUxs56rQyDCO_yEu46Vmbxfpapg";
                $fields=array('to'=>'/topics/sistema-general-'.$empresa_id,
                'notification'=>array(
                    'title'=>"GESTION DE EMPRESA",
                    'body'=>"Stock Agotandose ".$value->nombre,
                    'icon'=>'http://127.0.0.1:8000/img/logo.png',
                    'click_action'=>"https://www.conoceatuconductor.com/vencidos"
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
                $notificacion->tipo="Stock";
                $notificacion->descripcion="Stock Agotandose ".$value->nombre;
                $notificacion->estado="0";
                $notificacion->referencia_id=$value->id;
                $notificacion->empresa_id=$empresa_id;
                $notificacion->save();
            }
        }
    }
}
