<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Painel Admin - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/assets/admin/css/main.css">

    @yield('css')
</head>

<body>

    <button id="slowmo" class="d-none">Slow motion <span>mode</span></button>

    <div class="device">
        <div class="container">


            <!-- Adicionando uma div para isolar o menu em um card -->
            <div class="menu-card">
                @yield('breadcrumb')
                <button id="burger" class="open-main-nav">
                    <span class="burger"></span>
                    <span class="burger-text">Menu</span>
                </button>
                <nav class="main-nav" id="main-nav">
                    <ul>
                        <li>
                            <a href="{{ route('calculo.index') }}">Cálculos</a>
                        </li>
                        <li>
                            <a href="{{ route('usuario.index') }}">Usuarios</a>
                        </li>
                    </ul>
                </nav>
            </div>

            @yield('content')
        </div>
    </div>


    <!-- jQuery (se necessário) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery Mask Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <!-- Bootstrap JS (incluindo Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/utils.js"></script>
    <script>
        let burger = document.getElementById('burger'),
            nav = document.getElementById('main-nav'),
            slowmo = document.getElementById('slowmo');

        burger.addEventListener('click', function(e) {
            this.classList.toggle('is-open');
            nav.classList.toggle('is-open');
        });

        slowmo.addEventListener('click', function(e) {
            this.classList.toggle('is-slowmo');
        });

        /* Onload demo - dirty timeout */
        let clickEvent = new Event('click');

        // Fechar o modal quando o botão de fechar for clicado
        const modal = new bootstrap.Modal(document.getElementById('userModal'), {
            keyboard: false // Configuração para permitir que o modal seja fechado ao pressionar a tecla ESC
        });

        // Abrir o modal (caso precise de um exemplo)
        // modal.show();

        // Fechar o modal programaticamente
        document.querySelector('.close').addEventListener('click', function() {
            modal.hide();
        });
    </script>

    @yield('scripts')
    <!-- Adicione o SweetAlert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Função para exibir o toast
        function showToast(type, message) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type, // 'success', 'error', 'warning', 'info'
                title: message,
                showConfirmButton: false,
                timer: 3000, // Duração do toast
                timerProgressBar: true,
            });
        }

        @if (session('success'))
            <
            script >
                Swal.fire({
                    title: "{{ session('success') }}",
                    icon: "success",
                    toast: true,
                    position: "top-end",
                    timer: 3000,
                    showConfirmButton: false,
                });
    </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                title: "{{ session('error') }}",
                icon: "error",
                toast: true,
                position: "top-end",
                timer: 3000,
                showConfirmButton: false,
            });
        </script>
    @endif

    </script>


</body>

</html>
