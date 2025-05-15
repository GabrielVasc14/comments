<?php
require_once(__DIR__ . '/../configs/init_crud.php'); // Inclui o arquivo de configuração e inicialização
// include_once('connections/mysql_crud.php'); // Inclui o arquivo de conexão com o banco de dados
// $link_crud = link_db_crud(); // Chama a função de conexão com o banco de dados

$conn = DB_crud::connect(); // Conecta ao banco de dados usando a classe DB (se estiver usando PDO)

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'read':
        // $data = [];
        // $query = "SELECT * FROM trabalhadores ORDER BY nome ASC";
        // $result = mysqli_query($link_crud, $query);

        // if (!$result) {
        //     die('Erro na consulta SQL: ' . mysqli_error($link_crud));
        // }

        // while ($row = mysqli_fetch_assoc($result)) {
        //     $data[] = [
        //         'Id' => $row['id'],
        //         'Nome' => $row['nome'],
        //         'Idade' => $row['idade'],
        //         'Email' => $row['email'],
        //         'Telefone' => $row['telefone'],
        //         'N_identificacao' => $row['n_identificacao']
        //     ];
        // }
        // echo json_encode($data);

        // mysqli_free_result($result);
        // break;

        try {
            $sql = "SELECT * FROM trabalhadores ORDER BY nome ASC";
            $stmt = $conn->prepare($sql);

            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(['error' => 'Nenhum trabalhador encontrado.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erro na conexao com o banco de dados: ' . $e->getMessage()]);
        }
        break;

    case 'create':
        if (empty($_POST['nome']) || empty($_POST['idade']) || empty($_POST['telefone']) || empty($_POST['email']) || empty($_POST['n_identificacao'])) {
            echo json_encode(['error' => 'Todos os campos devem ser inseridos']);
            exit;
        }

        // $nome = mysqli_real_escape_string($link_crud, $_POST['nome']);
        // $idade = mysqli_real_escape_string($link_crud, $_POST['idade']);
        // $telefone = mysqli_real_escape_string($link_crud, $_POST['telefone']);
        // $email = mysqli_real_escape_string($link_crud, $_POST['email']);
        // $n_identificacao = mysqli_real_escape_string($link_crud, $_POST['n_identificacao']);

        // $insert_query = "INSERT INTO trabalhadores (nome, idade, telefone, email, n_identificacao) VALUES ('$nome', '$idade', '$telefone', '$email', '$n_identificacao')";
        // $insert_result = mysqli_query($link_crud, $insert_query);

        // if (!$insert_result) {
        //     echo json_encode(['error' => 'Erro ao inserir os dados: ' . mysqli_error($link_crud)]);
        // } else {
        //     echo json_encode(['success' => true, 'message' => 'Trabalhador adicionado']);
        // }
        // break;

        try {
            $sql = "INSERT INTO trabalhadores (nome, idade, telefone, email, n_identificacao) VALUES (:nome, :idade, :telefone, :email, :n_identificacao)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':nome' => $_POST['nome'],
                ':idade' => $_POST['idade'],
                ':telefone' => $_POST['telefone'],
                ':email' => $_POST['email'],
                ':n_identificacao' => $_POST['n_identificacao']
            ]);

            echo json_encode(['success' => true, 'message' => 'Trabalhador adicionado']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    case 'delete':
        // $id = mysqli_real_escape_string($link_crud, $_POST['id']);

        // $select_query = "SELECT * FROM trabalhadores WHERE id = $id";
        // $select_result = mysqli_query($link_crud, $select_query);

        // if (!$select_result || mysqli_num_rows($select_result) == 0) {
        //     echo json_encode(['error' => 'Id nao encontrado']);
        //     exit;
        // }


        // $delete_function_query = "DELETE FROM funcoes WHERE id_trabalhadores = $id";
        // $delete_function_result = mysqli_query($link_crud, $delete_function_query);

        // if (!$delete_function_result) {
        //     echo json_encode(['error' => 'Erro ao eliminar as funcoes associadas ao trabalhador']);
        //     exit;
        // }

        // $delete_worker_query = "DELETE FROM trabalhadores WHERE id = $id";
        // $delete_worker_result = mysqli_query($link_crud, $delete_worker_query);

        // if (!$delete_worker_result) {
        //     echo json_encode(['error' => 'Erro ao eliminar o trabalhador.']);
        // } else {
        //     echo json_encode(['success' => true, 'message' => 'Trabalhador e suas funcoes associadas foram eliminados']);
        // }

        // break;

        try {
            $sql = "SELECT * FROM trabalhadores WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':id' => $_POST['id']]);
            $worker = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$worker) {
                echo json_encode(['success' => false, 'error' => 'Id nao encontrado']);
                exit;
            }

            // Delete associated functions first
            $sql_functions = "DELETE FROM funcoes WHERE id_trabalhadores = :id";
            $stmt_functions = $conn->prepare($sql_functions);
            $stmt_functions->execute([':id' => $_POST['id']]);

            // Then delete the worker
            $sql_worker = "DELETE FROM trabalhadores WHERE id = :id";
            $stmt = $conn->prepare($sql_worker);
            $stmt->execute([':id' => $_POST['id']]);

            echo json_encode(['success' => true, 'message' => 'Trabalhador e suas funcoes eliminados']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    case 'get':
        // $id = mysqli_real_escape_string($link_crud, $_POST['id']);

        // $select_query = "SELECT * FROM trabalhadores WHERE id = $id";
        // $select_result = mysqli_query($link_crud, $select_query);

        // if (!$select_result || mysqli_num_rows($select_result) == 0) {
        //     echo json_encode(['error' => 'Id nao encontrado']);
        //     exit;
        // }

        // $row = mysqli_fetch_assoc($select_result);

        // echo json_encode([
        //     'Id' => $row['id'],
        //     'Nome' => $row['nome'],
        //     'Idade' => $row['idade'],
        //     'Email' => $row['email'],
        //     'Telefone' => $row['telefone'],
        //     'N_identificacao' => $row['n_identificacao']
        // ]);
        // break;
        $id = $_POST['id'] ?? null;

        if ($id) {
            try {
                $sql = "SELECT * FROM trabalhadores WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->execute([':id' => $_POST['id']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    echo json_encode($result);
                    exit;
                } else {
                    echo json_encode(['success' => false, 'error' => 'Trabalhador nao recuperado.']);
                }
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['error' => 'ID invalido']);
        }
        break;

    case 'update':
        // $id = mysqli_real_escape_string($link_crud, $_POST['id']);
        // $nome = mysqli_real_escape_string($link_crud, $_POST['nome']);
        // $idade = mysqli_real_escape_string($link_crud, $_POST['idade']);
        // $telefone = mysqli_real_escape_string($link_crud, $_POST['telefone']);
        // $email = mysqli_real_escape_string($link_crud, $_POST['email']);
        // $n_identificacao = mysqli_real_escape_string($link_crud, $_POST['n_identificacao']);

        // $update_query = "UPDATE trabalhadores SET nome = '$nome', idade = '$idade', telefone = '$telefone', email = '$email', n_identificacao = '$n_identificacao' WHERE id = $id";
        // $update_result = mysqli_query($link_crud, $update_query);

        // if (!$update_result) {
        //     echo json_encode(['error' => 'Erro ao atualizar os dados: ' . mysqli_error($link_crud)]);
        // } else {
        //     echo json_encode(['success' => true, 'message' => 'Trabalhador atualizado']);
        // }
        // break;

        try {
            $sql = "UPDATE trabalhadores SET nome = :nome, idade = :idade, telefone = :telefone, email = :email, n_identificacao = :n_identificacao WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':id' => $_POST['id'],
                ':nome' => $_POST['nome'],
                ':idade' => $_POST['idade'],
                ':telefone' => $_POST['telefone'],
                ':email' => $_POST['email'],
                ':n_identificacao' => $_POST['n_identificacao']
            ]);

            echo json_encode(['success' => true, 'message' => 'Trabalhador atualizado']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    case 'getWorkers':
        // $data = [];
        // $query = "SELECT * FROM trabalhadores ORDER BY nome ASC";
        // $result = mysqli_query($link_crud, $query);

        // if (!$result) {
        //     echo json_encode(['error' => 'Erro ao recuperar trabalhadores.']);
        //     exit;
        // }

        // while ($row = mysqli_fetch_assoc($result)) {
        //     $data[] = [
        //         'Id' => $row['id'],
        //         'Nome' => $row['nome'],
        //     ];
        // }
        // echo json_encode($data);
        // break;

        try {
            $sql = "SELECT * FROM trabalhadores ORDER BY nome ASC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(['success' => false, 'error' => 'Nenhum trabalhador encontrado.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    case 'createFunctions':
        if (empty($_POST['funcao']) || empty($_POST['data_entrega']) || empty($_POST['trabalhador'])) {
            echo json_encode(['error' => 'Todos os campos devem ser inseridos']);
            exit;
        }

        // $id_trabalhador = mysqli_real_escape_string($link_crud, $_POST['trabalhador']);
        // $funcao = mysqli_real_escape_string($link_crud, $_POST['funcao']);
        // $data_entrega = mysqli_real_escape_string($link_crud, $_POST['data_entrega']);

        // $insert_query = "INSERT INTO funcoes (id_trabalhadores, funcao, data_entrega) VALUES ('$id_trabalhador', '$funcao', '$data_entrega')";
        // $insert_result = mysqli_query($link_crud, $insert_query);

        // if (!$insert_result) {
        //     echo json_encode(['error' => 'Erro ao inserir os dados: ' . mysqli_error($link_crud)]);
        // } else {
        //     echo json_encode(['success' => true, 'message' => 'Funcao adicionada']);
        // }
        // break;

        try {
            $sql = "INSERT INTO funcoes (id_trabalhadores, funcao, data_entrega) VALUES (:trabalhador, :funcao, :data_entrega)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':trabalhador' => $_POST['trabalhador'],
                ':funcao' => $_POST['funcao'],
                ':data_entrega' => $_POST['data_entrega']
            ]);

            echo json_encode(['success' => true, 'message' => 'funcao adicionada']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    case 'getFunctions':
        // $query = "SELECT funcoes.id, funcoes.funcao, funcoes.data_entrega, trabalhadores.nome AS trabalhador_nome
        //             FROM funcoes 
        //             INNER JOIN trabalhadores ON funcoes.id_trabalhadores = trabalhadores.id
        //             ORDER BY funcoes.data_entrega ASC";

        // $result = mysqli_query($link_crud, $query);

        // if (!$result) {
        //     echo json_encode(['error' => 'Erro ao recuperar funcoes.']);
        //     exit;
        // }

        // $data = [];
        // while ($row = mysqli_fetch_assoc($result)) {
        //     $data[] = [
        //         'Id' => $row['id'],
        //         'Funcao' => $row['funcao'],
        //         'Data_entrega' => $row['data_entrega'],
        //         'WorkerName' => $row['trabalhador_nome']
        //     ];
        // }

        // echo json_encode($data);
        // break;

        try {
            $sql = "SELECT funcoes.id, funcoes.funcao, funcoes.data_entrega, trabalhadores.nome AS trabalhador  
                    FROM funcoes
                    INNER JOIN trabalhadores ON funcoes.id_trabalhadores = trabalhadores.id
                    ORDER BY funcoes.data_entrega ASC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(['success' => false, 'error' => 'Nenhum trabalhador encontrado.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    case 'deleteFunctions':
        // $idFunctions = mysqli_real_escape_string($link_crud, $_POST['id']);

        // $existFunctions = "SELECT * FROM funcoes WHERE id = $idFunctions";
        // $result = mysqli_query($link_crud, $existFunctions);

        // if (!$result || mysqli_num_rows($result) == 0) {
        //     echo json_encode(['error' => 'Id nao encontrado']);
        //     exit;
        // }

        // $delete_functions = "DELETE FROM funcoes WHERE id = $idFunctions";
        // $delete_result = mysqli_query($link_crud, $delete_functions);

        // if (!$delete_result) {
        //     echo json_encode(['error' => 'Erro ao eliminar a funcao.']);
        // } else {
        //     echo json_encode(['success' => true, 'message' => 'Funcao eliminada']);
        // }
        // break;

        try {
            $sql = "SELECT * FROM funcoes WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':id' => $_POST['id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                echo json_encode(['success' => false, 'error' => 'Id nao encontrado']);
                exit;
            }

            // Delete associated functions first
            $sql_functions = "DELETE FROM funcoes WHERE id = :id";
            $stmt_functions = $conn->prepare($sql_functions);
            $stmt_functions->execute([':id' => $_POST['id']]);

            echo json_encode(['success' => true, 'message' => 'Funcao eliminada']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    case 'getFuncao':
        // $idFunction = mysqli_real_escape_string($link_crud, $_POST['id']);

        // if ($idFunction != null) {
        //     $selectFunction = "SELECT * FROM funcoes WHERE id = $idFunction";
        //     $result = mysqli_query($link_crud, $selectFunction);

        //     if (!$result || mysqli_num_rows($result) == 0) {
        //         echo json_encode(['error' => 'Id nao encontrado']);
        //         exit;
        //     }

        //     //Obter dados das funcoes
        //     $funcoes = mysqli_fetch_assoc($result);

        //     //Verifica se o trabalhador existe
        //     if ($funcoes) {
        //         //Retorna se os dados forem encontrados
        //         echo json_encode($funcoes);
        //         exit;
        //     } else {
        //         echo json_encode(['error' => 'Erro ao recuperar dados das funcoes']);
        //         exit;
        //     }
        // } else {
        //     echo json_encode(['error' => 'Id invalido']);
        //     exit;
        // }

        $id = $_POST['id'] ?? null;

        if ($id) {
            try {
                $sql = "SELECT * FROM funcoes WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->execute([':id' => $_POST['id']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    echo json_encode($result);
                    exit;
                } else {
                    echo json_encode(['success' => false, 'error' => 'Funcao nao recuperada.']);
                }
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['error' => 'ID invalido']);
        }
        break;

    case 'updateFunctions':
        // //Recupera dados do POST
        // $funcao = mysqli_real_escape_string($link_crud, $_POST['funcao']);
        // $data_entrega = mysqli_real_escape_string($link_crud, $_POST['data_entrega']);

        // //Verifica se o ID foi fornecido
        // if (!isset($_POST['id']) || empty($_POST['id'])) {
        //     echo json_encode(['error' => 'ID da funcao nao fornecido']);
        //     exit;
        // }
        // $id = mysqli_real_escape_string($link_crud, $_POST['id']);

        // //Query de atualização
        // $update_query = "UPDATE funcoes SET funcao = '$funcao', data_entrega = '$data_entrega' WHERE id = $id";
        // $update_result = mysqli_query($link_crud, $update_query);

        // //Verifica o resulta da query
        // if (!$update_result) {
        //     echo json_encode(['error' => 'Erro ao atualizar a funcao: ' . mysqli_error($link_crud)]);
        //     exit;
        // } else {
        //     echo json_encode(['success' => true, 'message' => 'Funcao atualizada']);
        // }
        // break;

        try {
            $sql = "UPDATE funcoes SET funcao = :funcao, data_entrega = :data_entrega WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':id' => $_POST['id'],
                ':funcao' => $_POST['funcao'],
                ':data_entrega' => $_POST['data_entrega']
            ]);

            echo json_encode(['success' => true, 'message' => 'Funcao atualizada']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['error' => 'Ação inválida']);
        break;
}
