<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalculoResumoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calculo_resumo', function (Blueprint $table) {
            $table->id();  // Chave primária auto-incrementável
            $table->decimal('total_gorjeta', 10, 2);
            $table->decimal('desconto', 10, 2);
            $table->decimal('restante', 10, 2);
            $table->decimal('total_salao', 10, 2);
            $table->decimal('total_ponto_salao', 10, 2);
            $table->decimal('cada_ponto_salao', 10, 2);
            $table->decimal('total_retaguarda', 10, 2);
            $table->decimal('total_ponto_retaguarda', 10, 2);
            $table->decimal('cada_ponto_retaguarda', 10, 2);
            $table->date('data');  // Data do cálculo
            $table->timestamps();  // Criar as colunas created_at e updated_at (opcional)
            
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calculo_resumo');
    }
}
