$(document).ready(function () {
    // Definir o token CSRF globalmente para todas as requisições AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Função para carregar usuários via AJAX
    function loadUsers() {
        $.ajax({
            url: "/admin/usuario/getUser",  // A URL que retorna os usuários
            method: "GET",
            success: function (data) {
                const tbody = $("#table-users tbody");
                tbody.empty();  // Limpar a tabela antes de adicionar novos dados
                data.forEach(user => {
                    tbody.append(`
                        <tr>
                            <td>${user.name}</td>
                            <td>${user.area ? user.area.nome : 'Sem área'}</td> <!-- Exibindo área -->
                            <td>${user.pontuacao}</td> <!-- Exibindo ponto -->
                            <td>
                                <div class="actions">
                                    <button class="btn btn-sm btn-warning btn-edit" data-id="${user.id}">Editar</button>
                                    <button class="btn btn-sm btn-danger btn-delete" data-id="${user.id}">Excluir</button>
                                </div>
                            </td>
                        </tr>
                    `);
                });
            }
        });
    }

    loadUsers();  // Carregar os usuários ao inicializar

    // Abrir o modal para adicionar um novo usuário
    $("#btn-add-user").click(function () {
        $("#userForm")[0].reset();
        $("#user_id").val("");
        $("#userModalLabel").text("Adicionar Usuário");
        $("#userModal").modal("show");
    });

    // Salvar o usuário (adicionar ou editar)
    $("#btn-save-user").click(function () {
        const id = $("#user_id").val();
        const url = id ? `/admin/usuario/${id}` : "/admin/usuario";
        const method = id ? "PUT" : "POST";

        $.ajax({
            url: url,
            method: method,
            data: $("#userForm").serialize(),
            success: function () {
                $("#userModal").modal("hide");
                loadUsers();  // Atualiza a tabela após salvar
            }
        });
    });

    // Abrir o modal de edição
    $(document).on("click", ".btn-edit", function () {
        const id = $(this).data("id");
        $.ajax({
            url: `/admin/usuario/${id}`,
            method: "GET",
            success: function (usuario) {
                let user = usuario[0];

                // Preencher os campos do formulário
                $("#user_id").val(user.id);
                $("#name").val(user.name);
                $("#email").val(user.email);
                $("#area_id").val(user.area_id); // Aqui está o campo "role" que será preenchido com o ID da área
                $("#pontuacao").val(user.pontuacao);
                $("#active").val(user.active ? "1" : "0");
                $("#phone").val(user.phone);
                $("#userModalLabel").text("Editar Usuário");

                // Garantir que a área está selecionada no campo <select>
                $("#role").val(user.area_id); // Assumindo que o campo 'user.area_id' contém o ID da área associada ao usuário.

                // Mostrar o modal
                $("#userModal").modal("show");
            }
        });
    });


    // Abrir o modal de exclusão
    $(document).on("click", ".btn-delete", function () {
        const id = $(this).data("id");
        if (confirm("Você tem certeza que deseja excluir este usuário?")) {
            $.ajax({
                url: `/admin/usuario/delete/${id}`,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                method: "GET",
                success: function (response) {

                    showToast(response.type ,response.msg);
                  
                    loadUsers();  // Atualiza a tabela após exclusão
                }
            });
        }
    });

});
