@extends('admin.layout.app')


@section('css')
    <link href="{{ asset('/assets/admin/css/device.css') }}" rel="stylesheet">
@endsection
<style>
/* Estilos para o modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content-chat {
    background-color: #fefefe;
    margin: 10% auto;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    width: 60%;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
}

.modal-body {
    padding: 20px;
}

.modal-footer-without-border {
    display: flex;
    justify-content: flex-end;
    padding-top: 20px;
}

/* Estilos para o card dentro do modal */
.card {
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}

.card-body {
    margin-bottom: 20px;
}

.card-body label {
    font-weight: bold;
    margin-bottom: 5px;
}

/* Estilos para o botão de adicionar opção */
.add-option-btn {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    border-radius: 5px;
    margin-top: 10px;
    transition: background-color 0.2s;
}

.add-option-btn:hover {
    background-color: #0056b3;
}

/* Estilos para as opções */
.option {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.option input {
    margin-right: 10px;
}

/* Estilos para o botão de deletar opção */
.delete-option-btn {
    background-color: red;
    color: #fff;
    border: none;
    padding: 5px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.2s;
}

.delete-option-btn:hover {
    background-color: #b30000;
}

/* Estilos para os botões de salvar e cancelar */
.save-btn, .cancel-btn {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    border-radius: 5px;
    margin-left: 10px;
    transition: background-color 0.2s;
}

.save-btn:hover, .cancel-btn:hover {
    background-color: #0056b3;
}

</style>

@section('content')
    <section id="device">
        <!-- Page Heading -->
        <div class="page-header-content py-3">

            <div class="d-sm-flex align-items-center justify-content-between">
                <h1 class="h3 mb-0 text-gray-800">Chat Bots</h1>
                <a href="#" id="openModalBtn" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus text-white-50"></i>Novo Chat-Bot
                </a>
            </div>

            <ol class="breadcrumb mb-0 mt-4">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chat Bots</li>
            </ol>

        </div>
        <!-- Content Row -->
        <div class="row">
            <!-- Content Column -->
            <div class="col-lg-12 mb-4">
                <!-- Project Card Example -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-device">
                            <table class="table table-bordered" id="table-chat-bots">

                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Título</th>
                                        <th scope="col">Opções</th>
                                        <th scope="col">Ações</th>
                                    </tr>
                                </thead>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Modal -->
    <div class="modal " id="myModal">
        <div class="modal-content-chat">
            <div class="modal-header">
                <h2>Título do Modal</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <form id="myForm">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                              <label for="">Titulo do Menu</label>
                              <input type="text" name="" id="" class="form-control" placeholder="" aria-describedby="helpId">

                              <label for="">Opcões</label>
                              <input type="text"  class="form-control" placeholder="Opções">
                              <div class="options-container">
                                  <!-- Inputs de Opções serão adicionados aqui dinamicamente -->
                              </div>
                            </div>
                         
                           
                            <button type="button" class="add-option-btn"><i class="fas fa-plus"></i> Adicionar
                                Opção</button>
                        </div>
                        <div class="modal-footer-without-border">
                       
                            <button type="button" class="btn btn-danger">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </div>
                  
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        <!-- Importação da biblioteca Font Awesome 
        -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">



    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const openModalBtn = document.getElementById("openModalBtn");
            const modal = document.getElementById("myModal");
            const closeModalBtn = document.querySelector(".close");
            const addOptionBtn = document.querySelector(".add-option-btn");
            const form = document.getElementById("myForm");
            const optionsContainer = document.querySelector(".options-container");

            // Função para abrir o modal
            openModalBtn.addEventListener("click", function() {
                modal.style.display = "block";
            });

            // Função para fechar o modal
            closeModalBtn.addEventListener("click", function() {
                modal.style.display = "none";
            });

            // Função para adicionar um novo input de opção
            addOptionBtn.addEventListener("click", function() {
                const optionDiv = document.createElement("div");
                optionDiv.classList.add("option");
                optionDiv.innerHTML = `
                <input type="text" class="form-control" placeholder="Nova Opção" required>
                <button type="button" class="delete-option-btn"><i class="fas fa-trash"></i></button>
            `;
                optionsContainer.appendChild(optionDiv);

                // Evento para remover a opção
                const deleteOptionBtn = optionDiv.querySelector(".delete-option-btn");
                deleteOptionBtn.addEventListener("click", function() {
                    optionDiv.remove();
                });
            });

            // Evento para salvar os dados do formulário (submeter)
            form.addEventListener("submit", function(event) {
                event.preventDefault();
                // Aqui você pode adicionar a lógica para salvar os dados do formulário
                // e fechar o modal, por exemplo:
                modal.style.display = "none";
            });

            // Evento para cancelar e fechar o modal sem salvar
            const cancelBtn = document.querySelector(".cancel-btn");
            cancelBtn.addEventListener("click", function() {
                modal.style.display = "none";
            });
        });
    </script>
    <script src="{{ asset('/assets/admin/js/device/index.js') }}"></script>
@endsection
