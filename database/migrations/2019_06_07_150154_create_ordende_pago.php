<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdendePago extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('OrdenPago', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('FormaPago');
            $table->date('FechaPago');
            $table->string('estado');
            $table->float('Total');
            $table->text('ItemsFacturaPago');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
