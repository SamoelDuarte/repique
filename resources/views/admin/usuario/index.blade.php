@extends('admin.layout.app')

@section('title', 'Usuários')

@section('css')
    <link href="{{ asset('/assets/admin/css/usuario/index.css') }}" rel="stylesheet">
  
@endsection

@section('content')
    <div class="user-container">
    @section('breadcrumb', 'Usuários')

    <!-- Botão para adicionar usuário -->
    <button id="btn-add-user" class="btn btn-success mb-3">Adicionar Usuário</button>

    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <table class="user-table" id="table-users">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Área</th>
                    <th>Ponto</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Os dados dos usuários serão inseridos via AJAX -->
            </tbody>
        </table>
    </div>

</div>

<!-- Modal de Adicionar/Editar Usuário -->
<div class="modal" id="userModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Adicionar Usuário</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <input type="hidden" id="user_id">
                    <div class="form-group">
                        <label for="name">Nome</label>
                        <input type="text" class="form-control title-case" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="name">Senha</label>
                        <input type="text" class="form-control title-case" id="password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="role">Área</label>
                        <select class="form-control" id="area_id" name="area_id" required>
                            <option value="" disabled selected>Selecione a Área</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pontuacao">Ponto</label>
                        <input type="number" class="form-control" id="pontuacao" name="pontuacao" required>
                    </div>
                    <div class="form-group">
                        <label for="active">Ativo</label>
                        <select class="form-control" id="active" name="active" required>
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="phone">Telefone</label>
                        <input type="text" class="form-control telefone" id="phone" name="phone">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal"
                    aria-label="Close">Fechar</button>
                <button type="submit" class="btn btn-primary" id="btn-save-user">Salvar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('/assets/admin/js/usuario/index.js') }}"></script>
@endsection
