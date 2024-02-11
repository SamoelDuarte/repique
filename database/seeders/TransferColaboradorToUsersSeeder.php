<?php
namespace Database\Seeders;

use App\Http\Controllers\Utils;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TransferColaboradorToUsersSeeder extends Seeder
{
    public function run()
    {
        $colaboradores = DB::table('colaborador')->where('email',"paraiso@noite.com")->get();

        foreach ($colaboradores as $colaborador) {
            // Encontra o ID da área baseado no nome da área
            $areaId = DB::table('areas')->where('nome', $colaborador->area)->value('id');

            // Insere na tabela users
            DB::table('users')->insert([
                'name' => $colaborador->nome,
                'area_id' => $areaId,
                'email' => "paraiso@".$colaborador->nome.".com",
                'pontuacao' => $colaborador->pontuacao,
                'password' => Hash::make('senhaPadrao'), // Adiciona uma senha padrão
            ]);
        }
        
            $user1 = User::where('email',"paraiso@noite.com")->first();
            $user1->salt = Utils::createPasswordSalt();
            $user1->password = Utils::createPasswordHash('password', $user1->salt);
            $user1->role = 'admin';
            $user1->update();

            $user2 = User::where('email',"admin@admin.com")->first();
            $user2->salt = Utils::createPasswordSalt();
            $user2->password = Utils::createPasswordHash('admin', $user2->salt);
            $user2->role = 'admin';
            $user2->update();
    }
}
