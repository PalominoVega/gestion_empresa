<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKardexTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kardex', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cantidad');            //  es lo que se a movido sea ingreso o egreso
            $table->integer('stock');               //  cantidad actual
            $table->double('precio_promedio',8,2);  //  precio promedio
            $table->double('precio_lote',8,2);  //  precio Lote
            $table->unsignedInteger('producto_id')->nullable();
            $table->unsignedInteger('empresa_id')->nullable();
            $table->unsignedInteger('lote_id')->nullable();
            $table->enum('concepto',['Compra','Venta','Venta Anulada']);
            $table->unsignedInteger('referencia_id')->nullable();
            $table->enum('tipo',['Entrada','Salida']);
            $table->date('fecha');                       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kardex');
    }
}
