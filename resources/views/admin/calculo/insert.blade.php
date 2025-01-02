@extends('admin.layout.app')

@section('title', 'Novo Cálculo')

@section('css')
@endsection

@section('content')
<div class="user-container">
    @section('breadcrumb', 'Novo Cálculo')

    <form action="{{ route('calculo.store') }}" method="POST" id="novoCalculoForm">
        @csrf
        <div class="form-group">
            <label for="dataCalculo">Data do Cálculo</label>
            <input type="date" name="dataCalculo" id="dataCalculo" class="form-control" required>
        </div>
        <!-- Campo para o total da gorjeta -->
        <div class="form-group">
            <label for="totalGorjeta">Total da Gorjeta</label>
            <input type="text" step="0.01" name="totalGorjeta" id="totalGorjeta" class="form-control money" required placeholder="Digite o total da gorjeta">
        </div>

        <!-- Lista de funcionários trabalhando -->
        <div class="form-group">
            <label>Funcionários Trabalhando</label>
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Área</th>
                        <th>Ponto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($funcionariosTrabalhando as $funcionario)
                    <tr>
                        <td>
                            <input type="checkbox" name="funcionarios[]" value="{{ $funcionario->id }}" id="funcionario_{{ $funcionario->id }}" class="form-check-input">
                        </td>
                        <td>
                            <label for="funcionario_{{ $funcionario->id }}">{{ $funcionario->name }}</label>
                        </td>
                        <td>{{ $funcionario->area->nome }}</td> <!-- Exemplo de campo "área" -->
                        <td>{{ $funcionario->pontuacao }}</td> <!-- Exemplo de campo "ponto" -->
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Cálculo</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Adicione scripts aqui, se necessário, para validar ou melhorar a usabilidade do formulário
</script>
@endsection
