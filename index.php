<!DOCTYPE html>
<html lang="en">

<?php
session_start();
$theme = 'dark';
if (isset($_COOKIE['theme'])) {
    $theme = $_COOKIE['theme'];
}

if (!isset($_SESSION['user_nickname'])) {
    header('Location: welcome.php');
    exit();
}

echo "Bem vindo, " . htmlspecialchars($_SESSION['user_nickname']) . "!";
?>

<head>
    <meta charset="UTF-8">
    <title>PHP: Comentarios</title>
    <!-- Estilos personalizados -->
    <!-- Link para o CSS do Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="styles.css">
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
    <!-- Link para o JS do Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

</head>

<body class="<?php echo htmlspecialchars($theme); ?>">
    <h1>Comentarios</h1>
    <a href="logout.php" class="btn btn-logout">Logout</a>
    <button id="theme-toggle" class="btn btn-theme">Alternar Tema</button>

    <!--Botao para adicionar comentarios-->
    <button class="btn btn-add" onclick="Open()">Adicionar Comentario</button>

    <div class="container mt-4">
        <h3>Comentarios Recentes</h3>
        <div id="comment-cards" class="row gy-3"></div>
    </div>

    <!-- <table id="commentsTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Comentario</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
             <-- Os comentários serão carregados aqui via AJAX --
        </tbody>
    </table> -->

    <!-- Criacao de modal -->
    <!--Modal para adicionar comentarios-->
    <div id="add-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="name-button">
                <h2>Adicionar comentario</h2>
                <span class="close-btn" onclick="Close()">
                    <i class="fas fa-times"></i>
                </span>
            </div>
            <form id="commentForm" onsubmit="addComments(event)">

                <label for="comment">Comentario:</label>
                <input type="text" id="comment" name="comment" required></input><br><br>

                <button type="submit" class="btn btn-add">Adicionar Comentario</button>
            </form>
        </div>
    </div>

    <!-- Modal para editar os comentários -->
    <div id="edit-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="name-button">
                <h2>Editar comentario</h2>
                <span class="close-btn" onclick="Close()">
                    <i class="fas fa-times"></i>
                </span>
            </div>
            <form id="editCommentForm">
                <input type="hidden" id="idEdit" name="id"><br><br>

                <label for="comment">Comentario:</label>
                <input type="text" id="commentEdit" name="comment" required></input><br><br>

                <button type="button" onclick="editComments()" class="btn btn-add">Salvar</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            loadData(); // Carrega os comentários ao iniciar a página
            setTimeout(() => {
                loadLike(); // Carrega os likes
            }, 500);

            // Curte ao clicar no botao
            $(document).on('click', '.btn-like', function() {
                const commentId = $(this).data('id');

                $.post('data.php', {
                    action: 'like',
                    comment_id: commentId
                }, function(data) {
                    $('#likes-' + commentId).text(data.likes);
                }, 'json');
            });

            //Toastr options
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-bottom-right",
                "timeOut": "4000",
            };

            $('#commentsTable').DataTable(); // Inicializa o DataTable
        });

        function loadData() {
            $.ajax({
                url: 'data.php',
                type: 'POST',
                data: {
                    action: 'read'
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    $('#comment-cards').empty();

                    if (Array.isArray(response)) {
                        response.forEach(comment => {
                            console.log(comment);
                            currentUserNickname = comment.UserNickname;
                            // Adiciona como card
                            const card =
                                `<div class="col-md-4">
                                    <div class="card mb-3 shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title"><i class="fas fa-user"></i> ${comment.UserNickname}</h5>
                                            <p class="card-text">${comment.Comment}</p>
                                            <p class="card-text"><small class="text-muted">${comment.Data_Comment}</small></p>

                                            <p class="card-text">
                                                <button class="btn btn-like" data-id="${comment.Id}">
                                                    <i class="fa-solid fa-heart text-danger : fa-regular fa-heart"></i>
                                                    <span class="like-count" id="likes-${comment.Id}">0</span>
                                                </button>
                                            </p>

                                            <button onclick="editData(${comment.Id}, '<?php echo ($_SESSION['user_nickname']); ?>')" class="btn btn-edit">Editar</button>
                                            <button onclick="deleteComment(${comment.Id}, '<?php echo ($_SESSION['user_nickname']); ?>')" class="btn btn-delete">Excluir</button>
                                        </div>
                                    </div>
                                </div> `;
                            $('#comment-cards').append(card);

                            loadLike();

                        });
                    } else {
                        toastr.error('Erro ao carregar os dados: ' + response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao carregar os dados:', error);
                    toastr.error('Erro ao carregar os dados. Tente novamente mais tarde.');
                }
            });
        }

        function loadLike() {
            $.ajax({
                url: 'data.php',
                type: 'POST',
                data: {
                    action: 'get_likes'
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    if (Array.isArray(data)) {
                        data.forEach(like => {
                            $('#likes-' + like.comment_id).text(like.total);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Erro ao carregar os likes:', error);
                }
            });
        }

        function addComments(event) {
            event.preventDefault(); // Previne o envio padrao do formulario

            console.log('Formulario eniviado!');

            // Usando campos do formulario
            var formData = {
                comment: $('#comment').val(),
            };

            $.ajax({
                url: 'data.php',
                type: 'POST',
                data: {
                    action: 'add',
                    comment: formData.comment
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        loadData(); // Recarrega os comentários após adicionar
                        Close(); // Fecha o modal
                        toastr.success('Comentario adicionado com sucesso!');
                    } else {
                        toastr.error('Erro ao adicionar comentario: ' + response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao adicionar comentario:', error);
                    toastr.error('Erro ao adicionar comentario. Tente novamente mais tarde.');
                }
            });

            //Limpa os campos
            $('#comment').val(''); // Limpa o campo de comentário
            loadData(); // Recarrega os comentários
        }

        function deleteComment(id, nickname) {
            console.log('Exlcusa do comentario de Id: ' + id);

            console.log('Nickname do usuario: ' + nickname);
            console.log('ID do usuario: ' + id);

            swal({
                title: "Excluir comentario?",
                text: "Uma vez excluido, voce nao podera recuperar o comentario!",
                icon: "warning",
                button: true,
                dangerMode: true,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: 'data.php',
                        type: 'POST',
                        data: {
                            action: 'delete',
                            id: id,
                            nickname: nickname
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                loadData(); // Recarrega os comentários após excluir
                                Close(); // Fecha o modal
                                toastr.success('Comentario excluido com sucesso!');
                            } else {
                                toastr.error('Erro ao excluir comentario: ' + response.error);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Erro ao excluir comentario:', error);
                            toastr.error('Erro ao excluir comentario. Tente novamente mais tarde.');
                        }
                    });
                }
            });
        }

        function Open() {
            document.getElementById('add-modal').style.display = 'flex'; // Abre o modal de adicionar comentario
        }

        function Close() {
            document.getElementById('add-modal').style.display = 'none'; // Fecha o modal de adicionar comentario
            document.getElementById('edit-modal').style.display = 'none'; // Fecha o modal de editar comentario
        }

        function editData(id, nickname) {
            console.log('ID do comentario a ser editado: ' + id);
            console.log('Nickname do usuario: ' + nickname);

            document.getElementById('edit-modal').style.display = 'flex'; // Abre o modal de editar comentario

            console.log(id);

            $.ajax({
                url: 'data.php',
                type: 'POST',
                data: {
                    action: 'get',
                    id: id,
                    nickname: nickname
                },
                dataType: 'json',
                success(response) {
                    console.log('Resposta:', response);

                    if (response && !response.error) {
                        $('#idEdit').val(id); // Preenche o campo oculto com o ID do comentario
                        $('#commentEdit').val(response.comment); // Preenche o campo de comentario com o valor atual

                        //Salva o ID do comentario no campo oculto
                        $('#idEdit').data('idEdit', id); // Preenche o campo oculto com o ID do comentario
                    } else {
                        toastr.error('Erro ao carregar os dados do comentario: ' + response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao carregar os dados do comentario:', error);
                    toastr.error('Erro ao carregar os dados do comentario. Tente novamente mais tarde.');
                }
            });

            console.log("REQUESTE ENVIADO!")
        }

        function editComments(event, id) {
            const commentData = {
                id: $('#idEdit').val(), // ID do comentario a ser editado
                comment: $('#commentEdit').val() // Novo comentario
            };

            console.log('Dados da edição:', commentData);

            $.ajax({
                url: 'data.php',
                type: 'POST',
                data: {
                    action: 'edit',
                    id: commentData.id,
                    comment: commentData.comment
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Resposta do servidor:', response);
                    console.log(response);
                    if (response.success) {
                        loadData(); // Recarrega os comentarios apos editar
                        Close(); // Fecha o modal
                        toastr.success('Comentario editado com sucesso!');
                    } else {
                        toastr.error('Erro ao editar comentario: ' + response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro no AJAX:', xhr, status, error);
                    toastr.error('Erro ao editar comentario. Tente novamente mais tarde.' + error);
                }
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            const button = document.getElementById("theme-toggle");

            button.addEventListener("click", () => {
                const body = document.body;
                const isDark = body.classList.contains("dark");
                const newTheme = isDark ? "light" : "dark";

                body.classList.remove("light", "dark");
                body.classList.add(newTheme);
                document.cookie = "theme=" + newTheme + "; path=/; max-age=" + (60 * 60 * 24 * 30);

                button.textContent = newTheme === "dark" ? "Light Mode" : "Dark Mode";
            });
        });
    </script>
</body>