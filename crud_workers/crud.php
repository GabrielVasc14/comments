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
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="crud.css">
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
    <h1>Pratica 4 - Com registo e login</h1>
    <a href="../logout.php" class="btn btn-logout">Logout</a>

    <!--Botao para adicionar trabalhoderes-->
    <button class="btn btn-add" onclick="showAddForm()">Adicionar Trabalhador</button>

    <table id="dataTable">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>Idade</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>N_identificacao</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <!-- Os dados serão preenchidos pelo DataTables -->
        </tbody>
    </table>

    <!--Criacao de modal-->
    <!-- Modal para adicionar trabalhador -->
    <div id="addModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="name-button">
                <h2>Adicionar Trabalhador</h2>
                <span class="close-btn" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </span>
            </div>
            <form id="addForm" onsubmit="createWorker(event)">
                <label for="nome">Nome do trabalhador:</label>
                <input type="text" id="nome" name="nome" required><br><br>

                <label for="idade">Idade:</label>
                <input type="number" id="idade" name="idade" required><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br><br>

                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" required><br><br>

                <label for="n_identificacao">N_identificacao:</label>
                <input type="number" id="n_identificacao" name="n_identificacao" required><br><br>

                <button type="submit" class="btn btn-add">Adicionar</button>
            </form>
        </div>
    </div>

    <!-- Modal para editar trabalhador -->
    <div id="editModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="name_button">
                <h2>Editar Trabalhador</h2>
                <span class="close-btn" onclick="closeModal()">
                    <i class="fas fas_times"></i>
                </span>
            </div>
            <form id="editForm" onsubmit="editWorker(event)">
                <input type="hidden" id="idEdit" name="id"><br><br>

                <label for="nome">Nome do trabalhador:</label>
                <input type="text" id="nomeEdit" name="nome" required><br><br>

                <label for="idade">Idade:</label>
                <input type="number" id="idadeEdit" name="idade" required><br><br>

                <label for="email">Email:</label>
                <input type="email" id="emailEdit" name="email" required><br><br>

                <label for="telefone">Telefone:</label>
                <input type="text" id="telefoneEdit" name="telefone" required><br><br>

                <label for="n_identificacao">N_identificacao:</label>
                <input type="number" id="n_identificacaoEdit" name="n_identificacao" required><br><br>

                <button type="submit" class="btn btn-add">Salvar</button>
            </form>
        </div>
    </div>

    <!-- Modal para edicao de funcoes -->
    <div id="editModalFunctions" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="name-button">
                <h2>Editar Funções</h2>
                <span class="close-btn" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </span>
            </div>
            <form id="editForm" onsubmit="editFunctions(event)">
                <input type="hidden" id="idFunctionsEdit" name="id"><br><br>

                <label for="funcao">Função:</label>
                <input type="text" id="funcaoEdit" name="funcao" required><br><br>

                <label for="data_entrega">Data de entrega:</label>
                <input type="date" id="data_entregaEdit" name="data_entrega" required><br><br>

                <button type="submit" class="btn btn-add">Salvar</button>
            </form>
        </div>
    </div>

    <table id="functionsTable" class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Trabalhador</th>
                <th>Função</th>
                <th>Data de entrega</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <!-- Os dados serão preenchidos pelo DataTables -->
        </tbody>
    </table>

    <button id="addFunctionsModal" class="btn btn-add" onclick="showAddFunctions()">Adicionar Funcoes</button>

    <!-- Modal para abrir funcoes -->
    <div id="addModalFunctions" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </span>
            <h2>Adicionar Funcoes</h2>
            <form id="addFunctionsForm" onsubmit="addFunctions(event)">
                <label for="trabalhador">Trabalhador</label>
                <select id="trabalhador" name="trabalhador" required>
                    <!-- Trabalhadores serao carregados aqui -->
                </select><br><br>

                <label for="funcao">Função:</label>
                <input type="text" id="funcao" name="funcao" required><br><br>

                <label for="data_entrega">Data de entrega:</label>
                <input type="date" id="data_entrega" name="data_entrega" required><br><br>

                <button type="submit" class="btn btn-add">Adicionar</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            loadData();
            loadWorkers(); // Carrega os trabalhadores para o select
            loadFunctions(); // Carrega as funcoes para a tabela

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
                url: 'crud_data.php',
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
                                item.nome,
                                item.idade,
                                item.email,
                                item.telefone,
                                item.n_identificacao,
                                `<button class="btn btn-edit" onclick="editData(${item.id})">Editar</button>
                                 <button class="btn btn-delete" onclick="deleteData(${item.id})">Excluir</button>`
                            ]);
                        });
                    } else {
                        toastr.error('Ocorreu um erro ao carregar os dados.');
                    }
                    table.draw();
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao carregar os dados:', error);
                }
            });
        }

        function createWorker(event) {
            event.preventDefault(); //Previne o envio padrao do formulario

            if (!validateForm()) {
                return;
            }
            console.log('Formulario enviado!');

            //Usando campos do forms
            var formData = {
                nome: $('#nome').val(),
                idade: $('#idade').val(),
                email: $('#email').val(),
                telefone: $('#telefone').val(),
                n_identificacao: $('#n_identificacao').val()
            };

            $.ajax({
                url: 'crud_data.php',
                type: 'POST',
                data: {
                    action: 'create',
                    nome: formData.nome,
                    idade: formData.idade,
                    email: formData.email,
                    telefone: formData.telefone,
                    n_identificacao: formData.n_identificacao
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        loadData();
                        closeModal();
                        toastr.success('Trabalhador adicionado com sucesso!');
                    } else {
                        toastr.error('Erro ao adicionar trabalhador: ' + response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao adicionar trabalhador:', error);
                }
            });

            // Limpa os campos
            $('#nome').val('');
            $('#idade').val('');
            $('#email').val('');
            $('#telefone').val('');
            $('#n_identificacao').val('');
            loadWorkers();
        }

        function deleteData(id) {
            console.log('Exclusao do Id: ' + id);

            swal({
                title: "Excluir trabalhador?",
                text: "Você realmente deseja excluir este trabalhador?",
                icon: "warning",
                button: true,
                dangerMode: true,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: 'crud_data.php',
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
                                loadFunctions();
                                closeModal();
                                toastr.success('Trabalhador excluído!');
                            } else {
                                toastr.error('Erro ao excluir trabalhador: ' + response.error);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('Erro ao excluir trabalhador:', error);
                        }
                    });
                }
            });
        }

        function loadWorkers() {
            $.ajax({
                url: 'crud_data.php',
                type: 'POST',
                data: {
                    action: 'getWorkers'
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    const workerSelect = $('#trabalhador');
                    workerSelect.empty(); // Limpa as opções existentes

                    if (Array.isArray(response)) {
                        response.forEach(worker => {
                            workerSelect.append(new Option(worker.nome, worker.id));
                        });
                    } else {
                        toastr.error('Ocorreu um erro ao carregar os trabalhadores.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao carregar os trabalhadores: ', error);
                }
            });
        }

        function showAddForm() {
            document.getElementById('addModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('addModal').style.display = 'none';
            document.getElementById('editModal').style.display = 'none';
            document.getElementById('editModalFunctions').style.display = 'none';
            document.getElementById('addModalFunctions').style.display = 'none';

        }

        function editData(id) {
            document.getElementById('editModal').style.display = 'flex';

            console.log(id);

            $.ajax({
                url: 'crud_data.php',
                type: 'POST',
                data: {
                    action: 'get',
                    id: id
                },
                dataType: 'json',
                success(response) {
                    console.log('Resposta recebida', response);

                    if (response) {
                        $('#idEdit').val(response.id);
                        $('#nomeEdit').val(response.nome);
                        $('#idadeEdit').val(response.idade);
                        $('#emailEdit').val(response.email);
                        $('#telefoneEdit').val(response.telefone);
                        $('#n_identificacaoEdit').val(response.n_identificacao);
                    } else {
                        toastr.error('Erro ao carregar os dados do trabalhador.');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Erro do AJAX:', xhr, status, error);
                    alert('Erro ao carregar os dados do trabalhador: ' + error);
                }
            });
        }

        function editWorker(event) {
            event.preventDefault(); //Previne o envio padrao do formulario

            var formData = {
                id: $('#idEdit').val(),
                nome: $('#nomeEdit').val(),
                idade: $('#idadeEdit').val(),
                email: $('#emailEdit').val(),
                telefone: $('#telefoneEdit').val(),
                n_identificacao: $('#n_identificacaoEdit').val()
            };

            $.ajax({
                url: 'crud_data.php',
                type: 'POST',
                data: {
                    action: 'update',
                    id: formData.id,
                    nome: formData.nome,
                    idade: formData.idade,
                    email: formData.email,
                    telefone: formData.telefone,
                    n_identificacao: formData.n_identificacao
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        loadData();
                        closeModal();
                        toastr.success('Trabalhador atualizado com sucesso!');
                    } else {
                        toastr.error('Erro ao atualizar trabalhador: ' + response.error);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Erro ao atualizar trabalhador: ' + error);
                }
            });
        }

        function loadFunctions() {
            $.ajax({
                url: 'crud_data.php',
                type: 'POST',
                data: {
                    action: 'getFunctions'
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    const functionsTable = $('#functionsTable').DataTable();
                    functionsTable.clear();

                    if (Array.isArray(response)) {
                        response.forEach(functions => {
                            functionsTable.row.add([
                                functions.id,
                                functions.trabalhador,
                                functions.funcao,
                                functions.data_entrega,
                                `<button class="btn btn-edit" onclick="editFunctionsmodal(${functions.id})">Editar</button>
                                 <button class="btn btn-delete" onclick="deleteFunctions(${functions.id})">Excluir</button>`
                            ]);
                        });
                    } else {
                        toastr.error('Ocorreu um erro ao carregar as funcoes.');
                    }

                    functionsTable.draw();
                },
                error: function(xhr, status, error) {
                    toastr.error('Erro ao carregar as funcoes: ' + error);
                }
            });
        }

        function addFunctions(event) {
            event.preventDefault(); //Previne o envio padrao do formulario

            var functionsData = {
                trabalhador: $('#trabalhador').val(),
                funcao: $('#funcao').val(),
                data_entrega: $('#data_entrega').val()
            };

            $.ajax({
                url: 'crud_data.php',
                type: 'POST',
                data: {
                    action: 'createFunctions',
                    trabalhador: functionsData.trabalhador,
                    funcao: functionsData.funcao,
                    data_entrega: functionsData.data_entrega
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        loadFunctions();
                        closeModal();
                        toastr.success('Função adicionada com sucesso!');
                    } else {
                        toastr.error('Erro ao adicionar função: ' + response.error);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Erro ao adicionar função: ' + error);
                }
            });

            //Limpa os campos
            $('#trabalhador').val('');
            $('#funcao').val('');
            $('#data_entrega').val('');
            loadWorkers();
        }

        function deleteFunctions(id) {
            console.log('Exclusao da funcao Id: ' + id);

            $.ajax({
                url: 'crud_data.php',
                type: 'POST',
                data: {
                    action: 'deleteFunctions',
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        loadFunctions();
                        closeModal();
                        toastr.success('Função excluída!');
                    } else {
                        toastr.error('Erro ao excluir função: ' + response.error);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Erro ao excluir função: ' + error);
                }
            });

            loadWorkers();
            loadFunctions();
            loadData();

        }

        function editFunctionsmodal(id) {
            //Mostra o painel
            document.getElementById('editModalFunctions').style.display = 'flex';

            console.log('Edicao da funcao Id:' + id);

            $.ajax({
                url: 'crud_data.php',
                type: 'POST',
                data: {
                    action: 'getFuncao',
                    id: id
                },
                dataType: 'json',
                success(response) {
                    console.log(response);
                    if (response && !response.error) {
                        //Atualiza com os campos da funcao
                        $('#idFunctionsEdit').val(response.id);
                        $('#funcaoEdit').val(response.funcao); //Preenche o campo funcao
                        $('#data_entregaEdit').val(response.data_entrega); //Preenche o campo data_entrega

                        //Salva o ID da funcao no modal para ser usado no envio
                        $('#idFunctionsEdit').data('idFunctionsEdit', id);
                    } else {
                        toastr.error('Erro ao carregar os dados das funcoes.');
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Erro ao carregar os dados das funcoes: ' + error);
                }
            });

            console.log("REQUEST ENVIADO");
        }

        function editFunctions(event, id) {
            event.preventDefault(); //Previne o envio padrao do formulario

            const functionData = {
                id: $('#idFunctionsEdit').val(),
                funcao: $('#funcaoEdit').val(),
                data_entrega: $('#data_entregaEdit').val()
            };

            console.log("Dados da funcao: ", functionData);

            $.ajax({
                url: 'crud_data.php',
                type: 'POST',
                data: {
                    action: 'updateFunctions',
                    id: functionData.id,
                    funcao: functionData.funcao,
                    data_entrega: functionData.data_entrega
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        loadFunctions();
                        closeModal();
                        toastr.success('Função atualizada com sucesso!');
                    } else {
                        toastr.error('Erro ao atualizar função: ' + response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Erro no AJAX: ', xhr, status, error);
                    toastr.error('Erro ao atualizar função: ' + error);
                }
            });
        }

        function showAddFunctions() {
            document.getElementById('addModalFunctions').style.display = 'flex';
            loadWorkers(); // Carrega os trabalhadores para o select
        }

        function validateForm() {
            // Recupera os dados do formulario
            var nome = $('#nome').val();
            var idade = $('#idade').val();
            var email = $('#email').val();
            var telefone = $('#telefone').val();
            var n_identificacao = $('#n_identificacao').val();

            // Verifica algum campo vazio 
            if (nome === '' || idade === '' || email === '' || telefone === '' || n_identificacao === '') {
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