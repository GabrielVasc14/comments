<!DOCTYPE html>
<html lang="en">

<?php
session_start();
if (!isset($_SESSION['user_nickname'])) {
    header('Location: ../welcome.php');
    exit();
}
?>

<head>
    <meta charset="UTF-8">
    <title>Exemplo DataTables Estilizado com Ações</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="comment.css">
    <!-- Estilos personalizados -->
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- SweetAlert e FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <!-- Bootstrap 5 JS (opcional para interatividade) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
    <h1>Sistema de ADMIN dos comentarios</h1>
    <a href="../logout.php" class="btn btn-logout">Logout</a>

    <!-- Botao para add usuarios caso necessario -->
    <button class="btn btn-add" onclick="showAddForm()">Adicionar User</button>

    <table id="dataTable" class="table">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nickname</th>
                <th scope="col">Email</th>
                <th scope="col">Password</th>
                <th socpe="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- User aqui -->
        </tbody>
    </table>

    <!-- Criacao de modals -->
    <!-- Modal para add user -->
    <div id="addModal" class="modal fade" tabindex="-1" style="display: none;">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content col-12">
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()"></button>
                </div>
                <form id="addForm" onsubmit="newUser(event)">
                    <div class="mb-4">
                        <label for="nickname" class="form-label">Nickname:</label>
                        <input type="text" class="form-control" id="nickname" name="nickname" placeholder="Insira o Nickname">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Insira o Email">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Insira a Password">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal()">Fechar</button>
                        <button type="submit" class="btn btn-primary">Adicionar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para editar user -->
    <div id="editModal" class="modal fade" tabindex="-1" style="display: none;">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content col-12">
                <div class="modal-header">
                    <h5 class="modal-title">Editar User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()"></button>
                </div>
                <form id="editForm" onsubmit="editUser(event)">
                    <input type="hidden" id="idEdit" name="id"><br><br>
                    <div class="mb-3">
                        <label for="nickname" class="form-label">Nickname:</label>
                        <input type="text" class="form-control" id="editNickname" name="nickname" placeholder="Insira o Nickname">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="editEmail" name="email" placeholder="Insira o Email">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="editPassword" name="password" placeholder="Insira a Password">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal()">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para edicao de comentarios -->
    <div id="editModalComments" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content col-12">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Comentarios</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()"></button>
                </div>
                <form id="editCommentsForm" onsubmit="editComments(event)">
                    <input type="hidden" id="idCommentsEdit" name="id"><br><br>
                    <div class="mb">
                        <label for="comments" class="form-label">Comentarios:</label>
                        <input type="text" class="form-control" id="commentEdit" name="comment" placeholder="Insira o Comentario">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal()">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <table id="commentsTable" class="table">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Usuarios</th>
                <th scope="col">Comentario</th>
                <th scope="col">Data</th>
                <th socpe="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Os dados serão preenchidos pelo DataTables -->
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            loadData();
            loadComments(); //Carrega os comentarios para tabela

            //Toastr options
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-bottom-right",
                "timeOut": "4000",
            };

            $('#dataTable').dataTable();
        });

        function loadData() {
            $.ajax({
                url: 'comment_data.php',
                type: 'POST',
                data: {
                    action: 'read'
                },
                dataType: 'json',
                success: function(response) {
                    const table = $('#dataTable').DataTable();
                    table.clear();

                    if (Array.isArray(response)) {
                        response.forEach(item => {
                            console.log(item);
                            table.row.add([
                                item.id,
                                item.nickname,
                                item.email,
                                item.password,
                                `<button class="btn btn-edit" onclick="editData(${item.id})">Editar</button>
                                 <button class="btn btn-delete" onclick="deleteData(${item.id})">Excluir</button>`
                            ]);
                        });
                    } else {
                        toastr.error('Ocorreu um erro ao adicionar os dados.');
                    }
                    table.draw();
                },
                errror: function(xhr, status, error) {
                    console.error('Erro ao carregar dados.', error);
                }
            });
        }

        function newUser(event) {
            event.preventDefault(); //Previne envio padrao

            if (!validateForm()) {
                return;
            }
            console.log('Formulario Enviado!');

            //Usando campos do form
            var formData = {
                nickname: $('#nickname').val(),
                email: $('#email').val(),
                password: $('#password').val()
            };

            $.ajax({
                url: 'comment_data.php',
                type: 'POST',
                data: {
                    action: 'add',
                    nickname: formData.nickname,
                    email: formData.email,
                    password: formData.password
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        loadData();
                        closeModal();
                        toastr.success('Usuario adicionado com sucesso!');
                    } else {
                        toastr.error('Erro ao adicionar usuario: ' + response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao adicionar usuario: ', error);
                }
            });

            //Limpa campos
            $('#nickname').val('');
            $('#email').val('');
            $('#password').val('');
        }

        function deleteData(id) {
            console.log('Exclusao do Id: ' + id);

            swal({
                title: "Excluir usuario?",
                text: "Você realmente deseja excluir este usuario?",
                icon: "warning",
                button: true,
                dangerMode: true,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: 'comment_data.php',
                        type: 'POST',
                        data: {
                            action: 'delete',
                            id: id
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                loadData();
                                toastr.success('Usuario excluido!');
                            } else {
                                toastr.error('Erro ao excluir usuario: ' + response.error);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('Erro ao excluir usuario: ', error);
                        }
                    });
                }
            });
        }

        function showAddForm() {
            $('#addModal').modal('show');
        }

        function closeModal() {
            document.getElementById('addModal').style.display = 'none';
            document.getElementById('editModal').style.display = 'none';
            document.getElementById('editModalComments').style.display = 'none';
            // document.getElementById('addModalComments').style.display = 'none';
        }

        function editData(id) {
            $('#editModal').modal('show');

            console.log(id);

            $.ajax({
                url: 'comment_data.php',
                type: 'POST',
                data: {
                    action: 'get',
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Reposta recebida', response);

                    if (response) {
                        $('#idEdit').val(response.id);
                        $('#editNickname').val(response.nickname);
                        $('#editEmail').val(response.email);
                        $('#editPassword').val(response.password);
                    } else {
                        toastr.error('Erro ao carregar os dados do usuario');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Erro do AJAX: ', xhr, status, error);
                    toastr.error('Erro ao carregar os dados do trabalhador: ' + error);
                }
            });
        }

        function editUser(event) {
            event.preventDefault(); //Previne envio padrao

            var formData = {
                id: $('#idEdit').val(),
                nickname: $('#editNickname').val(),
                email: $('#editEmail').val(),
                password: $('#editPassword').val(),
            };

            $.ajax({
                url: 'comment_data.php',
                type: 'POST',
                data: {
                    action: 'update',
                    id: formData.id,
                    nickname: formData.nickname,
                    email: formData.email,
                    password: formData.password
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        loadData();
                        closeModal();
                        toastr.success('Usuario atualizado!');
                    } else {
                        toastr.error('Erro ao atualizar usuario: ' + response.error)
                    }
                },
                error: function(xhr, status, message) {
                    toastr.error('Erro ao atualizar trabalhador: ' + error);
                }
            });
        }

        function loadComments() {
            $.ajax({
                url: 'comment_data.php',
                type: 'POST',
                data: {
                    action: 'getComments'
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    const commentsTable = $('#commentsTable').DataTable();
                    commentsTable.clear();

                    if (Array.isArray(response)) {
                        response.forEach(comments => {
                            commentsTable.row.add([
                                comments.id,
                                comments.usuario,
                                comments.comment,
                                comments.date_comment,
                                `<button class="btn btn-edit" onclick="editCommentsmodal(${comments.id})">Editar</button>
                                 <button class="btn btn-delete" onclick="deleteComments(${comments.id})">Excluir</button>`
                            ]);
                        });
                    } else {
                        toastr.error('Ocorreu um erro ao carregar comentarios.');
                    }

                    commentsTable.draw();
                },
                error: function(xhr, status, error) {
                    toastr.error('Erro ao carregar comentarios: ' + error);
                }
            });
        }

        function deleteComments(id) {
            console.log('Exclusao do Id: ' + id);

            $.ajax({
                url: 'comment_data.php',
                type: 'POST',
                data: {
                    action: 'deleteComments',
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        loadComments();
                        closeModal();
                        toastr.success('Comentario excluido!');
                    } else {
                        toastr.error('Erro ao excluir comentario: ', response.error);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Erro ao excluir comentario: ' + error);
                }
            });

            loadComments();
            loadData();

        }

        function editCommentsmodal(id) {
            // Mostra painel
            $('#editModalComments').modal('show');

            console.log('Edicao dop comentario Id: ' + id);

            $.ajax({
                url: 'comment_data.php',
                type: 'POST',
                data: {
                    action: 'getComentario',
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response && !response.error) {
                        //Atualiza campos do comentario
                        $('#idCommentsEdit').val(response.id);
                        $('#commentEdit').val(response.comment);

                        //Salva o id do comantario no modal para ser usado no envio
                        $('#idCommentsEdit').data('idCommentsEdit', id);
                    } else {
                        toastr.error('Erro ao carregar dados do comentario');
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Erro ao carregar os dados do comentario: ' + error)
                }
            });

            console.log("REQUEST ENVIADO");
        }

        function editComments(event, id) {
            event.preventDefault();

            const commentData = {
                id: $('#idCommentsEdit').val(),
                comment: $('#commentEdit').val()
            };

            console.log("Dados do comentario: ", commentData);

            $.ajax({
                url: 'comment_data.php',
                type: 'POST',
                data: {
                    action: 'updateComment',
                    id: commentData.id,
                    comment: commentData.comment
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        loadComments();
                        closeModal();
                        toastr.success('Comentario atualizado');
                    } else {
                        toastr.error('Erro ao atualizar comentario: ' + response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Erro no AJAX: ', xhr, status, error);
                    toastr.error('Erro ao atualizar comentario: ' + error);
                }
            });
        }

        function validateForm() {
            // Recupera os dados do formulario
            var nickname = $('#nickname').val();
            var email = $('#email').val();
            var password = $('#password').val();

            // Verifica algum campo vazio 
            if (nickname === '' || email === '' || password === '') {
                toastr.error('Por favor, preencha todos os campos.');
                return false;
            }

            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/; // Regex para validar o email
            if (!emailPattern.test(email)) {
                toastr.error('Por favor, insira um email valido!');
                return false;
            }

            return true; // Retorna verdadeiro se todos os campos estiverem preenchidos corretamente
        }
    </script>
</body>

</html>