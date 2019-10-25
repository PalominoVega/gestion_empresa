<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ruc','11');
            $table->string('nombre','150');
            $table->string('email','100');
            $table->string('direccion', '100')->nullable();
            $table->string('telefono','15')->nullable();
            $table->string('logo','15')->nullable();
            $table->enum('tipo',['Activo','Inactivo'])->default('Activo');
            $table->string('codigo','10');
            $table->string('serie_boleta',4)->nullable();
            $table->string('serie_factura',4)->nullable();
            $table->string('url_facturacion',100)->nullable();
            $table->string('token_facturacion',160)->nullable();
        });

        DB::table('empresa')->insert([
            'nombre'=>'Demo',
            'direccion'=>'Calle 123456',
            'telefono'=>999999999,
            'email'=>'diego@gmail.com',
            'ruc'=>'12345678912',
            'logo'=>'demo.png',
            'tipo'=>'Activo',
            'codigo'=>'codigo'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresa');
    }
}
