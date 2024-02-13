<?php

use App\Http\Controllers\Utils;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $colaboradores = DB::table('colaborador')->get();

        foreach ($colaboradores as $colaborador) {
            // Encontra o ID da área baseado no nome da área
            $areaId = DB::table('areas')->where('nome', $colaborador->area)->value('id');

            $user = User::where('email', $colaborador->email)->first();
            // Insere na tabela users
            if ($user) {
                DB::table('users')->insert([
                    'id' => $colaborador->id,
                    'name' => $colaborador->nome,
                    'area_id' => $areaId,
                    'parent_id' => $user->id,
                    'email' => "paraiso@" . strtolower($colaborador->nome) . ".com",
                    'pontuacao' => $colaborador->pontuacao,
                    'password' => Hash::make('senhaPadrao'), // Adiciona uma senha padrão
                ]);
            }
        }

        $user1 = User::where('email', "paraiso@noite.com")->first();
        $user1->salt = Utils::createPasswordSalt();
        $user1->password = Utils::createPasswordHash('Boninal', $user1->salt);
        $user1->role = 'admin';
        $user1->update();

        $user2 = User::where('email', "admin@admin.com")->first();
        $user2->salt = Utils::createPasswordSalt();
        $user2->password = Utils::createPasswordHash('admin', $user2->salt);
        $user2->role = 'admin';
        $user2->update();

        $user3 = User::where('email', "calculo@teste.com")->first();
        $user3->salt = Utils::createPasswordSalt();
        $user3->password = Utils::createPasswordHash('password', $user3->salt);
        $user3->role = 'admin';
        $user3->update();

      

        // Após todas as modificações na tabela 'users', apagar as tabelas 'colaborador' e 'usuarios'
        Schema::dropIfExists('colaborador');
        Schema::dropIfExists('usuario');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
