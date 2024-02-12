<?php

namespace App\Http\Controllers;

use App\Models\CalculoResumo;
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
}
