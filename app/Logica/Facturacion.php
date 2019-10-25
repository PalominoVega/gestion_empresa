<?php
namespace App\Logica;

class Facturacion
{
    public static function new($cliente,$venta,$ruta,$token,$email){
        
        // RUTA para enviar documentos
        $ruta = $ruta;

        //TOKEN para enviar documentos
        $token = $token;
        
        $items=[];

        foreach ($venta->detalles as $key => $detalle) {
            $producto=$detalle->producto;
            $precio_unitario_sin_igv=round(($detalle->precio/1.18),3);
            // dd($detalle,$producto);
            array_push($items,array(
                    "unidad_de_medida"          => "NIU",
                    "codigo"                    => $producto->codigo,
                    "descripcion"               => $producto->nombre,
                    "cantidad"                  => $detalle->cantidad,
                    "valor_unitario"            => $precio_unitario_sin_igv,
                    "precio_unitario"           => $detalle->precio,
                    "descuento"                 => "",
                    "subtotal"                  => $precio_unitario_sin_igv*$detalle->cantidad,
                    "tipo_de_igv"               => "1",
                    "igv"                       => ($detalle->precio-$precio_unitario_sin_igv)*$detalle->cantidad,
                    "total"                     => $detalle->precio*$detalle->cantidad,
                    "anticipo_regularizacion"   => "false",
                    "anticipo_documento_serie"  => "",
                    "anticipo_documento_numero" => ""
            ));
        }
        // dd($items);    

        $data = array(
            "operacion"				        => "generar_comprobante", //No Editar
            "tipo_de_comprobante"           => $venta->tipo_documento,
            "serie"                         => $venta->serie,
            "numero"				        => $venta->correlativo,
            "sunat_transaction"			    => "1", //No Editar 
            "cliente_tipo_de_documento"		=> (($venta->tipo_documento=="1") ? 6 : 1),
            "cliente_numero_de_documento"	=> $cliente->documento,
            "cliente_denominacion"          => $cliente->nombre,
            "cliente_direccion"             => $cliente->direccion,
            "cliente_email"                 => $email,
            "cliente_email_1"               => "",
            "cliente_email_2"               => "",
            "fecha_de_emision"              => date('d-m-Y'),
            "fecha_de_vencimiento"          => "",
            "moneda"                        => "1",
            "tipo_de_cambio"                => "",
            "porcentaje_de_igv"             => "18.00",
            "descuento_global"              => "",
            "descuento_global"              => "",
            "total_descuento"               => "",
            "total_anticipo"                => "",
            "total_gravada"                 => $venta->total-$venta->igv,
            "total_inafecta"                => "",
            "total_exonerada"               => "",
            "total_igv"                     => $venta->igv,
            "total_gratuita"                => "",
            "total_otros_cargos"            => "",
            "total"                         => $venta->total,
            "percepcion_tipo"               => "",
            "percepcion_base_imponible"     => "",
            "total_percepcion"              => "",
            "total_incluido_percepcion"     => "",
            "detraccion"                    => "false",
            "observaciones"                 => "",
            "documento_que_se_modifica_tipo"=> "",
            "documento_que_se_modifica_serie"   => "",
            "documento_que_se_modifica_numero"  => "",
            "tipo_de_nota_de_credito"           => "",
            "tipo_de_nota_de_debito"            => "",
            "enviar_automaticamente_a_la_sunat" => "true",
            "enviar_automaticamente_al_cliente" => "true",
            "codigo_unico"                      => "",
            "condiciones_de_pago"               => "",
            "medio_de_pago"                     => "",
            "placa_vehiculo"                    => "",
            "orden_compra_servicio"             => "",
            "tabla_personalizada_codigo"        => "",
            "formato_de_pdf"                    => "",
            "items"                             => $items
        );
        // dd($data);
        $data_json = json_encode($data);
        // dd($data_json);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ruta);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Token token="'.$token.'"',
            'Content-Type: application/json',
            )
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta  = curl_exec($ch);
        curl_close($ch);
        
        $leer_respuesta = json_decode($respuesta, true);
        $venta->hash=$leer_respuesta['codigo_hash'];
        $venta->save();
        return $leer_respuesta;
        
    }
    public static function consulta($tipo_comprobante,$serie,$correlativo,$ruta,$token){
        $data = array(
            "operacion"             =>  "consultar_comprobante",
            "tipo_de_comprobante"   =>  $tipo_comprobante,
            "serie"                 =>  $serie,
            "numero"                =>  $correlativo,
        );
        //dd($data);

        $data_json = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ruta);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Token token="'.$token.'"',
            'Content-Type: application/json',
            )
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta  = curl_exec($ch);
        curl_close($ch);
        $leer_respuesta = json_decode($respuesta, true);
        return $leer_respuesta;
    }
    public static function anular($tipo_comprobante,$serie,$correlativo,$ruta,$token){
        $data = array(
            "operacion"             =>  "generar_anulacion",
            "tipo_de_comprobante"   =>  $tipo_comprobante,
            "serie"                 =>  $serie,
            "numero"                =>  $correlativo,
            "motivo"                =>  "ANULACIÓN GENERAL",
            "codigo_unico"          =>  ""
        );
        // dd($data);

        $data_json = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ruta);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Token token="'.$token.'"',
            'Content-Type: application/json',
            )
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta  = curl_exec($ch);
        curl_close($ch);
        $leer_respuesta = json_decode($respuesta, true);
        return $leer_respuesta;
    }

    public static function consultaAnulacion($tipo_comprobante,$serie,$correlativo,$ruta,$token){
        $data = array(
            "operacion"             =>  "consultar_anulacion",
            "tipo_de_comprobante"   =>  $tipo_comprobante,
            "serie"                 =>  $serie,
            "numero"                =>  $correlativo,
        );
        // dd($data);

        $data_json = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ruta);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Token token="'.$token.'"',
            'Content-Type: application/json',
            )
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta  = curl_exec($ch);
        curl_close($ch);
        $leer_respuesta = json_decode($respuesta, true);
        return $leer_respuesta;
    }
}
