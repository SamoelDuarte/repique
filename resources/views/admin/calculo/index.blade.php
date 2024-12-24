@extends('admin.layout.app')

@section('title', 'Cálculos')

@section('css')
    <link href="{{ asset('/assets/admin/css/usuario/index.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="user-container">
        @section('breadcrumb', 'Cálculos')

        <!-- Botão para adicionar cálculo -->
        <a href="{{ route('calculo.insert') }}" class="btn btn-success mb-3">Adicionar Cálculo</a>

        <table class="user-table" id="table-users">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Valor</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($calculos as $calculo)
                    <tr>
                        <td>{{ $calculo->data }}</td>
                        <td>{{ $calculo->total_gorjeta }}</td>
                        <td>
                            <div class="">
                                <a href="{{ route('calculo.show', $calculo->id) }}" class="btn btn-sm btn-warning">Ver</a>
                                <form action="{{ route('calculo.destroy', $calculo->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Deseja excluir este cálculo?')">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('/assets/admin/js/calculo/index.js') }}"></script>
@endsection
