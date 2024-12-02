<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitars', function (Blueprint $table) {
            $table->id();
            $table->time('hora_inicial');
            $table->timestamp('hora_final')->nullable();
            $table->date('data_inicial');
            $table->date('data_final');
            $table->text('motivo')->default('Não explicado');
            $table->string('situacao')->default('Pendente');
            $table->mediumText('obs_user')->default('não há observações');

            $table->mediumText('motivo_recusado')->default('Sem explicação');
            $table->unsignedBigInteger('id_recusado')->nullable();
            $table->foreign('id_recusado')->references('id')->on('users');
            $table->time('hora_recusado')->default('00:00:00');

            $table->unsignedBigInteger('veiculo_id');
            $table->foreign('veiculo_id')->references('id')->on('veiculos');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            
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
        Schema::dropIfExists('solicitars');
    }
}
