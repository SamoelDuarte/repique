<?php

namespace App\Http\Controllers;

use App\Models\CalculoResumo;
use App\Models\Colaborador;
use App\Models\User;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index()
    {
    }

    public function ultimosCalculos(Request $request)
    {


        // Obter os últimos 5 cálculos resumo filtrados pelo e-mail do usuário
        $ultimosCalculos = CalculoResumo::with('user')
            ->whereHas('user', function ($query) use ($request) {
                $query->where('email', $request->email);
            })
            ->select('total_gorjeta as total', 'data')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();


        // Retornar os resultados como uma resposta JSON
        return response()->json($ultimosCalculos);
    }

    public function getColaboradores(Request $request)
{
    $user = User::where('email', $request->email)->first();

    if ($user) {
        $usersWithSameParentId = User::where('parent_id', $user->id)->get();

        
        $colaboradores = [];
        foreach ($usersWithSameParentId as $userItem) {
            $colaborador = new Colaborador();
           
            $colaborador = array(
                "id" =>  $userItem->id,
                "nome" =>  $userItem->name,
                "area" =>  $userItem->area->nome,
                "pontuacao" => $userItem->pontuacao,
            );

            $colaboradores[] = $colaborador;
          
        }
        // dd($colaboradores);
        return response()->json($colaboradores);
    } else {
        return response()->json(['error' => 'Usuário não encontrado'], 404);
    }
}

}
