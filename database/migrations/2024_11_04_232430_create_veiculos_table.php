<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVeiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('veiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa')->unique();
            $table->string('placa_confirmar')->nullable();
            $table->string('placa_confirmar2')->nullable();
            $table->string('velocimetro_inicio')->nullable();
            $table->string('velocimetro_final')->nullable();
            $table->string('chassi')->unique();
            $table->string('funcionamento')->default('indisponível');
            $table->integer('ano');
            $table->string('modelo');
            $table->string('marca');
            $table->string('cor');
            $table->integer('capacidade');
            $table->integer('km_atual')->default('000');
            $table->integer('km_revisao')->default('10000');
            $table->text('observacao')->nullable();
            $table->string('qr_code')->nullable();
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
        Schema::dropIfExists('veiculos');
    }
}
