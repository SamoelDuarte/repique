<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('calculo_resumo', function (Blueprint $table) {
        // Adiciona a coluna user_id que referencia o id na tabela users
        $table->unsignedBigInteger('user_id')->nullable()->after('id');
        $table->foreign('user_id')->references('id')->on('users');
    });

    // Associa cada entrada em calculo_resumo com um usuário em users
    $resumos = DB::table('calculo_resumo')->get();
    foreach ($resumos as $resumo) {
        $userId = DB::table('users')->where('email', $resumo->email)->value('id');
        if ($userId) {
            DB::table('calculo_resumo')
                ->where('id', $resumo->id)
                ->update(['user_id' => $userId]);
        }
    }

     // Após associar os IDs dos usuários, exclui a coluna email
     Schema::table('calculo_resumo', function (Blueprint $table) {
        $table->dropColumn('email');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('calculo_resumo', function (Blueprint $table) {
        // Remove a chave estrangeira antes de remover a coluna para evitar erros
        $table->dropForeign(['user_id']);
        $table->dropColumn('user_id');
    });
}

};
