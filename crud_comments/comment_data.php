<?php
require_once(__DIR__ . '/../configs/init_comments.php'); // Inclui o arquivo de configuração e inicialização
// include_once('connections/mysql_crud.php'); // Inclui o arquivo de conexão com o banco de dados
// $link_crud = link_db_crud(); // Chama a função de conexão com o banco de dados

$conn = DB::connect(); // Conecta ao banco de dados usando a classe DB (se estiver usando PDO)

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'read':
        try {
            $sql = "SELECT * FROM usuarios ORDER BY nickname ASC";
            $stmt = $conn->prepare($sql);

            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(['error' => 'Nenhum usuario encontrado']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erro na conexao com o banco de dados: ' . $e->getMessage()]);
        }
        break;

    case 'add':
        if (empty($_POST['nickname']) || empty($_POST['email']) || empty($_POST['password'])) {
            echo json_encode(['error' => 'Todos os campos devem ser inseridos']);
            exit;
        }

        try {
            $sql = "INSERT INTO usuarios (nickname, email, password) VALUES (:nickname, :email, :password)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':nickname' => $_POST['nickname'],
                ':email' => $_POST['email'],
                ':password' => $_POST['password']
            ]);

            echo json_encode(['success' => true, 'message' => 'Usuario adicionado']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'delete':
        try {
            $sql = "SELECT * FROM usuarios WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':id' => $_POST['id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                echo json_encode(['success' => false, 'error' => 'Id nao encontrado']);
                exit;
            }

            //Deleta comentarios associados
            $sql_comment = "DELETE FROM comentarios WHERE id_nome = :id";
            $stmt_comment = $conn->prepare($sql_comment);
            $stmt_comment->execute(['id' => $_POST['id']]);

            //Delete likes
            // $sql_likes = "DELETE FROM likes WHERE user_id = :id";
            // $stmt_likes = $conn->prepare($sql_likes);
            // $stmt_likes->execute(['id' => $_POST['id']]);


            //Delete usuario
            $sql_user = "DELETE FROM usuarios WHERE id = :id";
            $stmt_user = $conn->prepare($sql_user);
            $stmt_user->execute(['id' => $_POST['id']]);

            echo json_encode(['success' => true, 'message' => 'Usuario e seus comentarios eliminados!']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    case 'get':
        $id = $_POST['id'] ?? null;

        if ($id) {
            try {
                $sql = "SELECT * FROM usuarios WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['id' => $_POST['id']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    echo json_encode($result);
                    exit;
                } else {
                    echo json_encode(['success' => false, 'error' => 'Usuario nao recuperado.']);
                }
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['error' => 'ID Invalido']);
        }
        break;

    case 'update':
        try {
            $sql = "UPDATE usuarios SET nickname = :nickname, email = :email, password = :password WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':id' => $_POST['id'],
                ':nickname' => $_POST['nickname'],
                ':email' => $_POST['email'],
                ':password' => $_POST['password']
            ]);

            echo json_encode(['success' => true, 'message' => 'Usuario atualizado']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    case 'getComments':
        try {
            $sql = "SELECT comentarios.id, comentarios.comment, comentarios.date_comment, usuarios.nickname AS usuario
                    FROM comentarios
                    INNER JOIN usuarios ON comentarios.id_nome = usuarios.id
                    ORDER BY comentarios.date_comment ASC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(['success' => false, 'error' => 'Nenhum usuario enontrado.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    case 'deleteComments':
        try {
            $sql = "SELECT * FROM comentarios WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['id' => $_POST['id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                echo json_encode(['success' => false, 'error' => 'Id nao encontrado']);
                exit;
            }

            //Deleta comentarios associados
            $sql_comment = "DELETE FROM comentarios WHERE id = :id";
            $stmt_comment = $conn->prepare($sql_comment);
            $stmt_comment->execute(['id' => $_POST['id']]);

            //Delete likes
            // $sql_likes = "DELETE FROM likes WHERE comment_id = :id";
            // $stmt_likes = $conn->prepare($sql_likes);
            // $stmt_likes->execute(['id' => $_POST['id']]);

            echo json_encode(['success' => true, 'message' => 'Comentario eliminado']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    case 'getComentario':
        $id = $_POST['id'] ?? null;

        if ($id) {
            try {
                $sql = "SELECT * FROM comentarios WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['id' => $_POST['id']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    echo json_encode($result);
                    exit;
                } else {
                    echo json_encode(['success' => false, 'error' => 'Comentario nao recuperado.']);
                }
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['error' => 'ID invalido']);
        }
        break;

    case 'updateComment':
        try {
            $sql = "UPDATE comentarios SET comment = :comment WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'id' => $_POST['id'],
                'comment' => $_POST['comment']
            ]);

            echo json_encode(['success' => true, 'message' => 'Comentario atualizado']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['error' => 'Ação inválida']);
        break;
}
