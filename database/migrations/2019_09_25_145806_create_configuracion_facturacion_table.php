<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfiguracionFacturacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracion_facturacion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serie_boleta',4);
            $table->string('serie_factura',4);
            $table->string('url_facturacion',100);
            $table->string('token_facturacion',160);
            $table->unsignedInteger('empresa_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuracion_facturacion');
    }
}
