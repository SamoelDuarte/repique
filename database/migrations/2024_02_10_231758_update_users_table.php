<?php

use App\Http\Controllers\Utils;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Verifica a existência antes de remover
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('users', 'image')) {
                $table->dropColumn('image');
            }
            if (Schema::hasColumn('users', 'remember_token')) {
                $table->dropColumn('remember_token');
            }
        
            // Adicionar novas colunas, se não existirem
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
        
            // Adicionando area_id e parent_id
            if (!Schema::hasColumn('users', 'area_id')) {
                $table->unsignedBigInteger('area_id')->nullable()->after('name');
                $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('id');
                $table->foreign('parent_id')->references('id')->on('users')->onDelete('set null');
            }
        });
        
        User::create([
            'name' => 'Admin', // Adicionando o campo obrigatório 'name'
            'email' => 'admin@admin',
            'password' => Hash::make('123'),
            'role' => 'admin',
            'active' => true,
        ]);

       
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
