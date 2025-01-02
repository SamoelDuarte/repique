<?php

use App\Http\Controllers\Utils;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80); // Nome do usuário
            $table->string('email', 80)->unique()->nullable(); // Email único
            $table->string('password', 100)->nullable(); // Senha do usuário
            $table->string('phone', 20)->nullable(); // Telefone opcional
            $table->integer('pontuacao')->nullable(); // Pontuação opcional
            $table->enum('role', ['admin', 'user'])->default('user'); // Função do usuário
            $table->boolean('active')->default(true); // Status ativo
            $table->boolean('deleted')->default(false); // Status de exclusão lógica

            $table->unsignedBigInteger('area_id')->nullable(); // Relacionamento com áreas
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null'); // Chave estrangeira

            $table->timestamps(); // Campos created_at e updated_at
        });

      User::create([
            'name' => 'Admin', // Adicionando o campo obrigatório 'name'
            'email' => 'admin@admin.com',
            'password' => Hash::make('Boninal'),
            'role' => 'admin',
            'active' => true,
        ]);

        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name' => 'user' . $i,
                'pontuacao' => $i % 2 == 0 ? 10 : 5, // Alterna entre 10 e 5
                'area_id' => $i % 2 == 0 ? 1 : 2,  // Alterna entre 1 e 2
            ]);
        }
        
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['area_id']);
        });

        Schema::dropIfExists('users');
    }
}
