<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->morphs('notifiable'); // Cria as colunas notifiable_id e notifiable_type
            $table->string('type'); // Tipo da notificação (por exemplo, Nome da classe de notificação)
            $table->text('data'); // Dados da notificação, geralmente em formato JSON
            $table->timestamp('read_at')->nullable(); // Hora em que a notificação foi lida
            $table->timestamps(); // Colunas created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
