<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lote', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo_lote',20)->nullable(); 
            $table->double('precio',8,2);           //  precio de ingreso
            $table->integer('cantidad');            //  es lo que se a movido sea ingreso
            $table->integer('consumo')->nullable(); //  consumo
            $table->unsignedInteger('producto_id')->nullable();              
            $table->unsignedInteger('compra_id')->nullable();              
            $table->date('fecha_vencimiento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lote');
    }
}
