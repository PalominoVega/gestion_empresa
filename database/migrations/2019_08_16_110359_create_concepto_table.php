<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConceptoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concepto', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre','30');
            $table->enum('tipo',['Ingreso','Egreso'])->nullable();
            $table->enum('estado',['0','1'])->default('0'); //0 : activo 1: desactiva 
            $table->double('precio',4,2)->nullable();
            $table->unsignedInteger('empresa_id')->nullable();
        });

        DB::table('concepto')->insert([
            [
                'id'=>1,
                'nombre'=>'Transferencia',
                'tipo'=>null,
            ],
            [
                'id'=>2,
                'nombre'=>'Ventas',
                "tipo"=>'Ingreso'
            ],
            [
                'id'=>3,
                'nombre'=>'Compras',
                'tipo'=>'Egreso',
            ],
        ]);   
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('concepto');
    }
}
