<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('razon_social');
            $table->integer('usuariosPermitidos');
            $table->string('cuit')->unique();
            $table->string('direccion');
            $table->string('telefono');
            $table->string('responsable');
            $table->string('telefono_responsable');
            $table->string('clave');
            $table->string('correo');
            $table->string('clave_p12');
            $table->string('certificado');
            $table->string('condicion_fiscal');
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
        Schema::dropIfExists('empresas');
    }
}
