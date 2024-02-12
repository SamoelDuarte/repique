<?php

namespace App\Http\Controllers;

use App\Models\CalculoResumo;
use Illuminate\Http\Request;

class AppController extends Controller
{
   public function index(){

   }

   public function ultimosCalculos(Request $request)
   {

    // dd($request->all());
    //    // Obter os últimos 5 cálculos resumo
    //    $ultimosCalculos = CalculoResumo::orderBy('id', 'desc')->limit(5)->get();

    $array[] = array('total' => '10.00','data' => '11-02-1990');
      
       // Retornar os resultados como uma resposta JSON
       return response()->json($request->all());
   }
}
