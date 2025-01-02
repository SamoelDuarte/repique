@extends('admin.layout.app')
@section('css')
    <link href="{{ asset('/assets/admin/css/usuario/index.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container">
        <h1>Resumo do Cálculo</h1>

        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
            <table class="user-table table-bordered">
                <thead>
                    <tr>
                        <th>Campo</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total Gorjeta</td>
                        <td>{{ number_format($calculoResumo->total_gorjeta, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Desconto</td>
                        <td>{{ number_format($calculoResumo->desconto, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Restante</td>
                        <td>{{ number_format($calculoResumo->restante, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Total Salão</td>
                        <td>{{ number_format($calculoResumo->total_salao, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Valor por Ponto Salão</td>
                        <td>{{ number_format($calculoResumo->cada_ponto_salao, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Total Retaguarda</td>
                        <td>{{ number_format($calculoResumo->total_retaguarda, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Valor por Ponto Retaguarda</td>
                        <td>{{ number_format($calculoResumo->cada_ponto_retaguarda, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Data</td>
                        <td>{{ $calculoResumo->data }}</td>
                    </tr>
                </tbody>
            </table>
        </div>


        <h2>Detalhes dos Funcionários</h2>
        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Área</th>
                        <th>Pontuação</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($calculoResumo->calculos as $calculo)
                        <tr>
                            <td>{{ $calculo->user->name }}</td>
                            <td>{{ $calculo->user->area->nome }}</td>
                            <td>{{ $calculo->user->pontuacao }}</td>
                            <td>{{ number_format($calculo->valor, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
