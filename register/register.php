<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="register.css">
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
    <h1>Registro</h1>
    <div class="register-container">
        <h2>Registre-se</h2>
        <form id="registerForm" onsubmit="register(event)">
            <label for="nickname">Nickname</label>
            <input type="text" id="nickname" name="nickname" class="form-control is-invalid" placeholder="Digite seu nickname">
            <div class="invalid-feedback">
                Campo obrigatorio!
            </div><br>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control is-invalid" placeholder="Digite seu email">
            <div class="invalid-feedback">
                Campo obrigatorio!
            </div><br>

            <label for="password">Senha</label>
            <input type="password" id="password" name="password" class="form-control is-invalid" placeholder="Digite sua senha">
            <div class="invalid-feedback">
                Campo obrigatorio!
            </div><br>

            <label for="confirm_password">Confirmar Senha</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control is-invalid" placeholder="Confirme sua senha">
            <div class="invalid-feedback">
                Campo obrigatorio!
            </div><br>

            <button type="submit" id="btnRegister" class="btn btn-primary">
                Registrar
            </button>
            <div id="spinner" class="spinner-border text-primary d-none" role="status"></div>
        </form>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function() {
            document.getElementById('btnRegister').disable = true;
            document.getElementById('spinner').classList.remove('d-none');
        });

        function register(event) {
            event.preventDefault();

            console.log('Formulario enviado!');

            var formData = {
                nickname: $('#nickname').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                confirm_password: $('#confirm_password').val(),
            };

            $.ajax({
                url: 'register_data.php',
                type: 'POST',
                data: {
                    nickname: formData.nickname,
                    email: formData.email,
                    password: formData.password,
                    confirm_password: formData.confirm_password
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
                    console.error('Erro ao se registrar: ', error);
                    toastr.error('Erro ao se cadastrar. Tente novamente')
                }
            });

            //Limpa campos
            $('#nickname').val('');
            $('#email').val('');
            $('#password').val('');
            $('#confirm_password').val('');
        }
    </script>
</body>

</html>