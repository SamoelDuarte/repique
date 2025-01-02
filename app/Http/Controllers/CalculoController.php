<?php

namespace App\Http\Controllers;

use App\Models\Calculo;
use App\Models\CalculoResumo;
use App\Models\User;
use Illuminate\Http\Request;

class CalculoController extends Controller
{
    public function index()
    {
        $calculos = CalculoResumo::orderBy('id', 'desc')->get();




        return view('admin.calculo.index', compact('calculos'));
    }



    public function insert()
    {
        $funcionariosTrabalhando =  User::with('area')->where(['role' => 'user','deleted' => '0'])->get();
        return view('admin.calculo.insert', compact('funcionariosTrabalhando'));
    }
    public function store(Request $request)
    {


        // Obter o total da gorjeta do request e converter para float
        $totalGorjeta = str_replace(['.', ','], ['', '.'], $request->totalGorjeta);

        // Calcular 20% de desconto
        $totalDesconto = round($totalGorjeta * 0.20, 2);

        // Calcular o restante
        $restante = round($totalGorjeta - $totalDesconto, 2);

        // Dividir o restante em 60% (salão) e 40% (retaguarda)
        $valorSalao = round($restante * 0.60, 2);
        $valorRetaguarda = round($restante * 0.40, 2);

        // Obter os funcionários selecionados
        $funcionarios = User::whereIn('id', $request->funcionarios)->get();

        // Separar pontuações de salão e retaguarda
        $totalPontoSalao = $funcionarios->where('area_id', 1)->sum('pontuacao');
        $totalPontoRetaguarda = $funcionarios->where('area_id', 2)->sum('pontuacao');
        // dd($totalPontoSalao);
        // Calcular os valores por ponto
        $cadaPontoSalao = $totalPontoSalao > 0 ? round($valorSalao / $totalPontoSalao, 2) : 0;
        $cadaPontoRetaguarda = $totalPontoRetaguarda > 0 ? round($valorRetaguarda / $totalPontoRetaguarda, 2) : 0;

        // Criar uma nova instância de CalculoResumo
        $calculoResumo = CalculoResumo::create([
            'user_id' => auth()->id(), // Supondo que o usuário logado esteja sendo usado
            'total_gorjeta' => $totalGorjeta,
            'desconto' => $totalDesconto,
            'restante' => $restante,
            'total_salao' => $valorSalao,
            'total_ponto_salao' => $totalPontoSalao,
            'cada_ponto_salao' => $cadaPontoSalao,
            'total_retaguarda' => $valorRetaguarda,
            'total_ponto_retaguarda' => $totalPontoRetaguarda,
            'cada_ponto_retaguarda' => $cadaPontoRetaguarda,
            'data' => $request->dataCalculo, // Data enviada no formulário
        ]);

        // Inserir registros no modelo Calculo
        foreach ($funcionarios as $funcionario) {
            // Determinar qual valor de ponto usar com base na área
            $valorPorPonto = ($funcionario->area_id == 1) ? $cadaPontoSalao : $cadaPontoRetaguarda;

            // Calcular o valor total para o funcionário
            $valor = round($funcionario->pontuacao * $valorPorPonto, 2);

            // Criar registro no modelo Calculo
            Calculo::create([
                'calculoresumo_id' => $calculoResumo->id,
                'user_id' => $funcionario->id,
                'send' => false, // Definir como false por padrão, ajustar conforme necessário
                'valor' => $valor,
            ]);
        }

        // Redirecionar para a visualização do cálculo resumo
        return redirect()->route('calculo.show', $calculoResumo->id)
            ->with('success', 'Cálculo realizado com sucesso!');
    }

    public function destroy($id)
    {
        // Buscar o CalculoResumo pelo ID
        $calculoResumo = CalculoResumo::findOrFail($id);

        // Deletar todos os registros de Calculo associados ao CalculoResumo
        Calculo::where('calculoresumo_id', $calculoResumo->id)->delete();

        // Deletar o CalculoResumo
        $calculoResumo->delete();

        // Redirecionar para a lista de cálculos ou página desejada com mensagem de sucesso
        return redirect()->route('calculo.index')
            ->with('success', 'Cálculo e Resumo excluídos com sucesso!');
    }

    public function show($id)
    {
        // Buscar o cálculo resumo pelo ID
        $calculoResumo = CalculoResumo::with('calculos.user')->findOrFail($id);

        // Retornar a visualização com os dados
        return view('admin.calculo.show', compact('calculoResumo'));
    }
    
    public function separaTotalPonto($funcionarios)
    {
        $totalPontosPorArea = [];

        foreach ($funcionarios as $funcionario) {
            $areaNome = $funcionario->area->nome; // Obter o nome da área
            $pontos = $funcionario->pontuacao; // Obter a pontuação do funcionário

            // Se a área ainda não existir no array, inicializa com 0
            if (!isset($totalPontosPorArea[$areaNome])) {
                $totalPontosPorArea[$areaNome] = 0;
            }

            // Soma os pontos do funcionário à área correspondente
            $totalPontosPorArea[$areaNome] += $pontos;
        }

        return $totalPontosPorArea;
    }
}
