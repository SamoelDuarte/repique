<?php

namespace App\Http\Controllers;

use App\Models\Calculo;
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

    public function insertCalculo(Request $request)
    {
        $dadosCalculo = json_decode($request->calculo);
        $dadosListaColaborador = json_decode($request->listaColaborador, true);


        // dd($dadosListaColaborador);
        $user = User::where('email', $request->email)->first();

        // Criar uma instância do modelo CalculoResumo
        $calculoResumo = new CalculoResumo([
            'user_id' => $user->id,
            'total_gorjeta' => $dadosCalculo->totalGorgeta,
            'desconto' => $dadosCalculo->desconto,
            'restante' => $dadosCalculo->totalRestante,
            'total_salao' => $dadosCalculo->totalSalao,
            'total_ponto_salao' => $dadosCalculo->totalPontoSalao,
            'cada_ponto_salao' => $dadosCalculo->cadaPontoSalao,
            'total_retaguarda' => $dadosCalculo->totalRetaguarda,
            'total_ponto_retaguarda' => $dadosCalculo->totalPontoRetaguarda,
            'cada_ponto_retaguarda' => $dadosCalculo->cadaPontoReaguarda,
            'data' => $dadosCalculo->data_calculo_envia,
        ]);

        // Salvar o cálculo resumo associado ao usuário
        $user->calculosResumo()->save($calculoResumo);
        // Iterar sobre os dados da lista de colaboradores
        foreach ($dadosListaColaborador as $dadosColaborador) {

            // dd($calculoResumo->id);
            // Criar uma nova instância do modelo Calculo para cada colaborador
            $calculo = new Calculo([
                'calculoresumo_id' => $calculoResumo->id,
                'colaborador_id' => $dadosColaborador['id'],
                'valor' => $dadosColaborador['valor']
            ]);

            // Salvar o modelo Calculo associado ao cálculo resumo
            $calculoResumo->calculos()->save($calculo);
        }
    }
}
