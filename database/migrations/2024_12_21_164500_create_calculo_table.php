<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalculoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calculo', function (Blueprint $table) {
            $table->id();  // Chave primária auto-incrementável
            $table->unsignedBigInteger('calculoresumo_id');  // Relacionamento com a tabela de calculos_resumo
            $table->unsignedBigInteger('user_id');  // Relacionamento com a tabela de users
            $table->boolean('send')->default(false);  // Definindo se o cálculo foi enviado
            $table->decimal('valor', 10, 2);  // O valor calculado
            $table->timestamps();  // Criar as colunas created_at e updated_at (opcional)

            // Chave estrangeira para o relacionamento com calculo_resumo
            $table->foreign('calculoresumo_id')->references('id')->on('calculo_resumo')->onDelete('cascade');

            // Chave estrangeira para o relacionamento com users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calculo');
    }
}
