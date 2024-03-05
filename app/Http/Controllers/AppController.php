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

    public function ultimosCalculosRepique(Request $request)
    {

        // Obter os últimos 5 cálculos resumo filtrados pelo e-mail do usuário
        $ultimosCalculos = Calculo::with('user', 'calculoResumo')
            ->whereHas('user', function ($query) use ($request) {
                $query->where('email', $request->email);
            })
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        $resultoCalculos = [];
        foreach ($ultimosCalculos as $key => $ultimosCalculo) {
            // dd($ultimosCalculo->calculoResumo->data);
            $calculos = array(
                "total" => $ultimosCalculo->valor,
                "data" => $ultimosCalculo->calculoResumo->data
            );
            $resultoCalculos[] = $calculos;
        }



        // Retornar os resultados como uma resposta JSON
        return response()->json($resultoCalculos);
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
        // Encontrar o CalculoResumo existente com a mesma data e usuário
        $calculoresumoExistente = CalculoResumo::where('user_id', $user->id)
            ->where('data', $dadosCalculo->data_calculo_envia)
            ->first();


        // Se o CalculoResumo existir, exclua-o
        if ($calculoresumoExistente) {
            $calculoresumoExistente->calculos()->delete(); // Exclui todos os cálculos associados
            $calculoresumoExistente->delete(); // Em seguida, exclui o CalculoResumo
        }



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
                'valor' => $dadosColaborador['valor'],
                'send' => 1
            ]);

            // Salvar o modelo Calculo associado ao cálculo resumo
            $calculoResumo->calculos()->save($calculo);
        }
    }

    public function getCalculos(Request $request)
    {


        // Encontrar o usuário com base no e-mail fornecido na solicitação
        $user = User::where('email', $request->email)->first();

        // Se o usuário existir, encontrar o CalculoResumo com a data e o user_id fornecidos
        if ($user) {
            $calculoResumo = CalculoResumo::where('data', $request->data)
                ->where('user_id', $user->id)
                ->first();

            // Se o CalculoResumo existir, obter os cálculos associados a ele
            if ($calculoResumo) {
                $calculos = Calculo::whereHas('calculoResumo', function ($query) use ($request, $user) {
                    $query->where('data', $request->data)
                        ->where('user_id', $user->id);
                })->get();
                $jsonArray = [];

                // Iterar sobre os resultados retornados
                foreach ($calculos as $calculo) {
                    // Criar um array associativo para cada cálculo
                    $colaborador = [
                        'id' => $calculo->id,
                        'nome' => $calculo->user->name, // Você precisa acessar a relação user para obter o nome do usuário
                        'area' => $calculo->user->area->nome, // Supondo que o usuário tenha um atributo 'area'
                        'pontuacao' => $calculo->user->pontuacao, // Supondo que o usuário tenha um atributo 'pontuacao'
                        'valor' => $calculo->valor,
                        'data' => $request->data
                    ];

                    // Adicionar o array do colaborador ao array principal
                    $jsonArray[] = $colaborador;
                }
                // Faça algo com os cálculos encontrados, como retorná-los como resposta JSON
                return response()->json($jsonArray);
            } else {
                // Se o CalculoResumo não existir, retorne uma resposta indicando que não foram encontrados cálculos para a data e o usuário fornecidos
                return response()->json(['message' => 'Não foram encontrados cálculos para a data e o usuário fornecidos'], 404);
            }
        } else {
            // Se o usuário não existir, retorne uma resposta indicando que o usuário não foi encontrado
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
    }

    public function resumoData(Request $request)
    {


        // Encontrar o usuário com base no e-mail fornecido na solicitação
        $user = User::where('email', $request->email)->first();

        // Se o usuário existir, encontrar o CalculoResumo com a data e o user_id fornecidos
        if ($user) {
            $calculoResumo = CalculoResumo::where('data', $request->data)
                ->where('user_id', $user->id)
                ->first();

            // Se o CalculoResumo existir, obter os cálculos associados a ele
            if ($calculoResumo) {
                $calculos = Calculo::whereHas('calculoResumo', function ($query) use ($request, $user) {
                    $query->where('data', $request->data)
                        ->where('user_id', $user->id);
                })->get();
                $jsonArray = [];

                // Iterar sobre os resultados retornados
                foreach ($calculos as $calculo) {
                    // Criar um array associativo para cada cálculo
                    $colaborador = [
                        'id' => $calculo->id,
                        'nome' => $calculo->user->name, // Você precisa acessar a relação user para obter o nome do usuário
                        'area' => $calculo->user->area->nome, // Supondo que o usuário tenha um atributo 'area'
                        'pontuacao' => $calculo->user->pontuacao, // Supondo que o usuário tenha um atributo 'pontuacao'
                        'valor' => $calculo->valor,
                        'data' => $request->data
                    ];

                    // Adicionar o array do colaborador ao array principal
                    $jsonArray[] = $colaborador;
                }
                // Faça algo com os cálculos encontrados, como retorná-los como resposta JSON
                return response()->json($jsonArray);
            } else {
                // Se o CalculoResumo não existir, retorne uma resposta indicando que não foram encontrados cálculos para a data e o usuário fornecidos
                return response()->json(['message' => 'Não foram encontrados cálculos para a data e o usuário fornecidos'], 404);
            }
        } else {
            // Se o usuário não existir, retorne uma resposta indicando que o usuário não foi encontrado
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
    }

    

    public function dadosOnda(Request $request)
    {
        // Obter o primeiro dia do ano atual
        $primeiroDiaAno = now()->startOfYear();
        // Obter a data de hoje
        $hoje = now();
    
        // Inicializar o array com os valores de todos os meses do ano, com valor zero
        $meses = [
            utf8_encode(strftime('%b', mktime(0, 0, 0, 1, 1))) => 0, // JAN
            utf8_encode(strftime('%b', mktime(0, 0, 0, 2, 1))) => 0, // FEV
            utf8_encode(strftime('%b', mktime(0, 0, 0, 3, 1))) => 0, // MAR
            utf8_encode(strftime('%b', mktime(0, 0, 0, 4, 1))) => 0, // ABR
            utf8_encode(strftime('%b', mktime(0, 0, 0, 5, 1))) => 0, // MAI
            utf8_encode(strftime('%b', mktime(0, 0, 0, 6, 1))) => 0, // JUN
            utf8_encode(strftime('%b', mktime(0, 0, 0, 7, 1))) => 0, // JUL
            utf8_encode(strftime('%b', mktime(0, 0, 0, 8, 1))) => 0, // AGO
            utf8_encode(strftime('%b', mktime(0, 0, 0, 9, 1))) => 0, // SET
            utf8_encode(strftime('%b', mktime(0, 0, 0, 10, 1))) => 0, // OUT
            utf8_encode(strftime('%b', mktime(0, 0, 0, 11, 1))) => 0, // NOV
            utf8_encode(strftime('%b', mktime(0, 0, 0, 12, 1))) => 0, // DEZ
        ];
    
        // Obter os últimos cálculos resumo filtrados pelo e-mail do usuário e restritos ao período de tempo especificado
        $ultimosCalculos = Calculo::with('user', 'calculoResumo')
            ->whereHas('user', function ($query) use ($request) {
                $query->where('email', $request->email);
            })
            ->whereHas('calculoResumo', function ($query) use ($primeiroDiaAno, $hoje) {
                $query->whereBetween('data', [$primeiroDiaAno, $hoje]);
            })
            ->orderBy('id', 'desc')
            ->get();
    
        // Agrupar os cálculos resumo por mês e calcular o total para cada mês
        foreach ($ultimosCalculos as $calculo) {
            $mes = utf8_encode(strftime('%b', strtotime($calculo->calculoResumo->data))); // Obter o nome do mês abreviado
            $meses[$mes] += number_format($calculo->valor, 2); // Adicionar o total do cálculo ao mês correspondente
        }
    
        // Array com os nomes dos meses em português e abreviados
        $nomesMesesPortugues = [
            'Jan' => 'Jan',
            'Feb' => 'Fev',
            'Mar' => 'Mar',
            'Apr' => 'Abr',
            'May' => 'Mai',
            'Jun' => 'Jun',
            'Jul' => 'Jul',
            'Aug' => 'Ago',
            'Sep' => 'Set',
            'Oct' => 'Out',
            'Nov' => 'Nov',
            'Dec' => 'Dez',
        ];
    
        // Traduzir os nomes dos meses para português
        $mesesTraduzidos = [];
        foreach ($meses as $nomeIngles => $valor) {
            if (isset($nomesMesesPortugues[$nomeIngles])) {
                $mesesTraduzidos[$nomesMesesPortugues[$nomeIngles]] = $valor;
            }
        }
    
        // Filtrar apenas os meses com valor
    $mesesComValor = array_filter($mesesTraduzidos, function ($valor) {
        return $valor > 0;
    });

    return response()->json($mesesComValor);
    }
    
}
