<?php

use App\Http\Controllers\Utils;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Remover colunas não necessárias
            $table->dropColumn(['email_verified_at', 'usuario_id', 'status', 'image', 'remember_token']);

            // Adicionar novas colunas
            if (!Schema::hasColumn('users', 'salt')) {
                $table->string('salt', 255)->nullable();
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable();
            }
            if (!Schema::hasColumn('users', 'pontuacao')) {
                $table->integer('pontuacao')->nullable();
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'user'])->default('user');
            }
            if (!Schema::hasColumn('users', 'active')) {
                $table->boolean('active')->default(true);
            }
            
            // Verifica se a coluna area_id não existe antes de adicioná-la
            if (!Schema::hasColumn('users', 'area_id')) {
                // Adiciona a coluna area_id
                $table->unsignedBigInteger('area_id')->nullable()->after('name');
                
                // Define a chave estrangeira que referencia a tabela areas
                $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null');
            }

        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Remover as colunas adicionadas
            $table->dropForeign(['area_id']);
            $table->dropColumn(['salt', 'phone', 'pontuacao', 'role', 'active', 'area_id']);
            
            // Reverter a remoção de colunas, se necessário, adicionando-as novamente
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('usuario_id')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('image', 80)->nullable();
            $table->string('remember_token', 100)->nullable();
        });
    }
}
