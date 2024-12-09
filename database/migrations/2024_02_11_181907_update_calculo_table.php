<?php

use App\Models\CalculoResumo;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // public function up(): void
    // {
    //     // Adicionar uma nova coluna temporária sem autoincremento
    //     Schema::table('calculo_resumo', function (Blueprint $table) {
    //         $table->bigInteger('id_temp')->unsigned()->nullable();
    //     });

    //     // Transferir os valores da coluna id para a coluna id_temp
    //     DB::statement('UPDATE calculo_resumo SET id_temp = id');

    //     // Remover a coluna original id
    //     Schema::table('calculo_resumo', function (Blueprint $table) {
    //         $table->dropColumn('id');
    //     });

    //     // Adicionar uma nova coluna id sem auto incremento
    //     Schema::table('calculo_resumo', function (Blueprint $table) {
    //         $table->bigInteger('id')->unsigned()->nullable();
    //     });
    //     // Transferir os valores da coluna id_temp para a coluna id
    //     DB::statement('UPDATE calculo_resumo SET id = id_temp');

    //     // Remover a coluna temporária id_temp
    //     Schema::table('calculo_resumo', function (Blueprint $table) {
    //         $table->dropColumn('id_temp');
    //     });

    //     // Alterar a coluna id para auto incremento e tipo INT(20)
    //     Schema::table('calculo_resumo', function (Blueprint $table) {
    //         $table->bigIncrements('id')->change();
    //     });


    //     Schema::table('calculo', function (Blueprint $table) {
    //         // Altera o tipo de dados para INT(11)
    //         $table->unsignedBigInteger('calculoresumo_id')->nullable()->after('id');
    //         // Adiciona a restrição de chave estrangeira
    //         $table->foreign('calculoresumo_id')->references('id')->on('calculo_resumo');
    //     });
    //     Schema::table('calculo', function (Blueprint $table) {
    //         // Adiciona a coluna user_id que referencia o id na tabela users
    //         $table->unsignedBigInteger('colaborador_id')->nullable()->after('id');
    //         $table->foreign('colaborador_id')->references('id')->on('users');
    //     });


    //     // Passo 2: Percorrer todas as linhas da tabela calculo
    //     $calculos = DB::table('calculo')->get();
    //     foreach ($calculos as $calculo) {
    //         // Encontrar o ID correspondente na tabela calculo_resumo com a mesma data
    //         $calculoresumoId = CalculoResumo::where('data', $calculo->data)->first();
    //         $user = User::where('id', $calculo->id_colaborador)->first();
        
    //         // Passo 3: Atualizar a coluna calculoresumo_id na tabela calculo com o ID encontrado
    //         if ($calculoresumoId !== null && $user !== null) {
    //             DB::table('calculo')
    //                 ->where('id', $calculo->id)
    //                 ->update(['calculoresumo_id' => $calculoresumoId->id, 'colaborador_id' => $user->id]);
    //         }else {
    //             // Excluir as linhas onde o usuário ou o cálculo resumo estejam vazios
    //             DB::table('calculo')->where('id', $calculo->id)->delete();
    //         }
    //     }

        
    //     Schema::table('calculo', function (Blueprint $table) {
    //         $table->dropColumn('id_colaborador');
    //     });
    //     Schema::table('calculo', function (Blueprint $table) {
    //         // Remove as colunas 'data' e 'email'
    //         $table->dropColumn('data');
    //         $table->dropColumn('email');
    //     });
    // }


    // public function down(): void
    // {
    //     Schema::table('calculo', function (Blueprint $table) {
    //         $table->dropForeign(['calculoresumo_id']);
    //         $table->dropColumn('calculoresumo_id');
    //         // Adicione novamente a coluna 'data' com a definição original
    //         $table->date('data')->nullable();
    //     });
    // }
};
