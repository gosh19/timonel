<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturaCompra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturacompra', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('usuario');
            $table->integer('sucursalusuario');
            $table->string('tipocomprobante');
            $table->string('razon_social');
            $table->string('cuit');
            $table->date('fechadecarga');
            $table->integer('pv');
            $table->integer('numero');
            $table->date('vencimiento');
            $table->string('estado');
            $table->string('metodopago');
            $table->float('total_bruto');
            $table->text('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facturacompra');
    }
}
