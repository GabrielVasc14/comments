<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="login.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- SweetAlert e FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Bootstrap 5 JS (opcional para interatividade) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
    <h1>Login</h1>
    <div class="login-container">
        <h2>Login</h2>
        <form id="loginForm" onsubmit="login(event)">
            <label for="nickname">Nickname</label>
            <input type="text" id="nickname" name="nickname" class="form-control is-invalid" placeholder="Nickname">
            <div class="invalid-feedback">
                Campo obrigatorio!
            </div><br>

            <label for="password">Senha</label>
            <input type="password" id="password" name="password" class="form-control is-invalid" placeholder="Password">
            <div class="invalid-feedback">
                Campo obrigatorio!
            </div><br>

            <button type="submit" class="btn btn-primary" id="btnLogin">
                Entrar
            </button>
            <div id="spinner" class="spinner-border text-primary d-none" role="status"></div>
        </form>

        <p>Nao tem conta? <a href="../register/register.php" a class="register.link">Registre-se</a></p>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            document.getElementById('btnLogin').disable = true;
            document.getElementById('spinner').classList.remove('d-none');
        });

        function login(event) {
            event.preventDefault();

            console.log('Formulario enviado!');

            var formData = {
                nickname: $('#nickname').val(),
                password: $('#password').val(),
            };

            $.ajax({
                url: 'login_data.php',
                type: 'POST',
                data: {
                    nickname: formData.nickname,
                    password: formData.password
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.location.href = response.redirect;
                    } else {
                        toastr.error(response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao fazer login:', error);
                    toastr.error('Erro fazer login. tente novamente')
                }
            });

            //Limpa campos
            $('#nickname').val('');
            $('#password').val('');
        }
    </script>
</body>

</html>