<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('calculo_resumo', function (Blueprint $table) {
        $table->bigInteger('id')->unsigned()->change(); // Altera o tipo da coluna id para BIGINT
    });
    
    Schema::table('calculo', function (Blueprint $table) {
        // Altera o tipo de dados para INT(11)
        $table->unsignedBigInteger('calculoresumo_id')->nullable()->after('id');
        // Adiciona a restrição de chave estrangeira
        $table->foreign('calculoresumo_id')->references('id')->on('calculo_resumo');
    });

    // Passo 2: Percorrer todas as linhas da tabela calculo
    $calculos = DB::table('calculo')->get();
    foreach ($calculos as $calculo) {
        // Encontrar o ID correspondente na tabela calculo_resumo com a mesma data
        $calculoresumoId = DB::table('calculo_resumo')->where('data', $calculo->data)->value('id');

        // Passo 3: Atualizar a coluna calculoresumo_id na tabela calculo com o ID encontrado
        if ($calculoresumoId) {
            DB::table('calculo')
                ->where('id', $calculo->id)
                ->update(['calculoresumo_id' => $calculoresumoId]);
        }
    }

    Schema::table('calculo', function (Blueprint $table) {
        // Remove as colunas 'data' e 'email'
        $table->dropColumn('data');
        $table->dropColumn('email');
    });
}


    public function down(): void
    {
        Schema::table('calculo', function (Blueprint $table) {
            $table->dropForeign(['calculoresumo_id']);
            $table->dropColumn('calculoresumo_id');
            // Adicione novamente a coluna 'data' com a definição original
            $table->date('data')->nullable();
        });
    }
};
