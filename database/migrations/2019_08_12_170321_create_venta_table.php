<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venta', function (Blueprint $table) {
            $table->increments('id');
            $table->double('total',10,3);
            $table->double('igv',9,3);
            $table->string('serie',4)->nullable();
            $table->integer('correlativo')->nullable();
            $table->string('tipo_documento',2)->nullable(); //01: Factura, 03: boleta
            $table->string('hash',50)->nullable();
            $table->unsignedInteger('cliente_id')->nullable();              
            $table->unsignedInteger('empresa_id')->nullable();              
            $table->unsignedInteger('user_id')->nullable();              
            $table->unsignedInteger('movimiento_caja_id')->nullable();
            $table->enum('estado',['0','1'])->default('0');

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
        Schema::dropIfExists('venta');
    }
}