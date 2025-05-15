<?php
require_once('configs/init.php'); // Inclui o arquivo de configuração e inicialização
include_once('connections/mysql_conn.php'); // Inclui o arquivo de conexão com o banco de dados
$link = link_db(); // Estabelece a conexão com o banco de dados

$action = isset($_POST['action']) ? $_POST['action'] : ''; // Obtém a ação do POST

switch ($action) {

    case 'read':
        $data = [];
        $query = "SELECT comentarios.id, comentarios.comment, comentarios.date_comment, usuarios.nickname AS usuario_nome
                    FROM comentarios
                    INNER JOIN usuarios ON comentarios.id_nome = usuarios.id
                    ORDER BY comentarios.date_comment ASC";

        $result = mysqli_query($link, $query); // Executa a consulta SQL

        if (!$result) {
            echo json_encode(array('error' => 'Erro ao recuperar comentarios: ' . mysqli_error($link)));
            exit;
        }

        while ($row = mysqli_fetch_assoc($result)) {

            $data[] = [
                'Id' => $row['id'],
                'Comment' => $row['comment'],
                'Data_Comment' => $row['date_comment'],
                'UserNickname' => $row['usuario_nome']
            ];
        }

        echo json_encode($data); // Retorna os dados em formato JSON

        break;

    case 'add':
        if (empty($_POST['comment'])) {
            echo json_encode(array('error' => 'Comentário não pode ser vazio'));
            exit;
        }

        $comment = mysqli_real_escape_string($link, $_POST['comment']); // Escapa caracteres especiais para evitar SQL Injection
        $user_id = $_SESSION['user_id']; // Obtém o ID do usuário da sessão
        $date_comment = date('Y-m-d H:i:s'); // Obtém a data e hora atual

        $insert_query = "INSERT INTO comentarios (id_nome, comment, date_comment) VALUES ('$user_id', '$comment', '$date_comment')"; // Consulta de inserção
        $insert_result = mysqli_query($link, $insert_query); // Executa a consulta de inserção

        if (!$insert_result) {
            echo json_encode(['error' => 'Erro ao adicionar comentário: ' . mysqli_error($link)]);
            exit;
        } else {
            echo json_encode(['success' => true, 'message' => 'Comentário adicionado com sucesso!']);
        }
        break;

    case 'delete':

        $id = mysqli_real_escape_string($link, $_POST['id']);
        $nick = mysqli_real_escape_string($link, $_POST['nickname']);

        $select_query = "SELECT comentarios.id FROM comentarios INNER JOIN usuarios ON comentarios.id_nome = usuarios.id
                            WHERE comentarios.id = '$id' AND usuarios.nickname = '$nick'"; // Consulta para verificar se o comentário pertence ao usuário
        $select_result = mysqli_query($link, $select_query); // Executa a consulta de seleção

        if (mysqli_num_rows($select_result) == 0) {
            echo json_encode(['error' => 'Comentario nao pertence ao usuario: ' . mysqli_error($link)]);
            exit;
        }

        $delete_query = "DELETE comentarios FROM comentarios INNER JOIN usuarios ON comentarios.id_nome = usuarios.id
            WHERE comentarios.id = '$id' AND usuarios.nickname = '$nick'  "; // Consulta de exclusão

        $delete_result = mysqli_query($link, $delete_query); // Executa a consulta de exclusão

        if ($delete_result) {
            echo json_encode(['success' => true, 'message' => 'Comentário excluído com sucesso!']);
            exit;
        } else {
            echo json_encode(['error' => 'Erro ao excluir comentário: ' . mysqli_error($link)]);
            exit;
        }
        break;

    case 'get':
        $idComment = mysqli_real_escape_string($link, $_POST['id']); // Escapa o ID do comentário a ser recuperado
        $nick = mysqli_real_escape_string($link, $_POST['nickname']);

        if ($idComment != null) {
            $select_query = "SELECT * FROM comentarios INNER JOIN usuarios ON comentarios.id_nome = usuarios.id
                            WHERE comentarios.id = '$idComment' AND usuarios.nickname = '$nick'"; // Consulta para verificar se o comentário pertence ao usuário
            $select_result = mysqli_query($link, $select_query); // Executa a consulta de seleção

            if (mysqli_num_rows($select_result) == 0) {
                echo json_encode(['error' => 'Comentario nao pertence ao usuario: ' . mysqli_error($link)]);
                exit;
            } elseif (mysqli_num_rows($select_result) > 0) {
                $comentarios = mysqli_fetch_assoc($select_result); // Obtém o resultado da consulta

                if ($comentarios) {
                    echo json_encode($comentarios);
                    exit;
                }
            } else {
                echo json_encode(['error' => 'ID invalido']);
                exit;
            }
        }
        break;

    case 'edit':
        //Recupera os dados do POST
        $comment = mysqli_real_escape_string($link, $_POST['comment']); // Escapa o comentário para evitar SQL Injection

        // Verifica se o ID foi fornecido
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            echo json_encode(['error' => 'ID do comentário não fornecido']);
            exit;
        }


        $id = mysqli_real_escape_string($link, $_POST['id']); // Escapa o ID do comentário a ser editado

        $update_query = "UPDATE comentarios SET comment = '$comment' WHERE id = $id"; // Consulta de atualização
        $update_result = mysqli_query($link, $update_query); // Executa a consulta de atualização

        if (!$update_result) {
            echo json_encode(['error' => 'Erro ao editar comentário: ' . mysqli_error($link)]);
            exit;
        } else {
            echo json_encode(['success' => true, 'message' => 'Comentário editado com sucesso!']);
        }
        break;

    case 'like':
        $comment_id = $_POST['comment_id'];
        $user_id = $_SESSION['user_id'];

        $select_query = "SELECT * FROM likes WHERE comment_id = $comment_id AND user_id = $user_id";
        $result = mysqli_query($link, $select_query);

        if ($result && mysqli_num_rows($result) > 0) {
            // Se ja curtiu remove o like
            $remove_like_query = "DELETE FROM likes WHERE comment_id = $comment_id AND user_id = $user_id";
            $result_remove_like = mysqli_query($link, $remove_like_query);
        } else {
            // Se nao curtiu, insere dados
            $insert_query_like = "INSERT INTO likes (comment_id, user_id) VALUES ($comment_id, $user_id)";
            $insert_result_like = mysqli_query($link, $insert_query_like);
        }

        // Retorna nova contagem
        $select_count_like = "SELECT COUNT(*) as total FROM likes WHERE comment_id = $comment_id";
        $result_count_like = mysqli_query($link, $select_count_like);
        $row = mysqli_fetch_assoc($result_count_like);
        $likes = $row['total'];

        echo json_encode(['likes' => $likes]);
        break;

    case 'get_likes':
        $select_query = "SELECT comment_id, COUNT(*) as total FROM likes GROUP BY comment_id";
        $select_result = mysqli_query($link, $select_query);

        $likes = [];
        while ($row = mysqli_fetch_assoc($select_result)) {
            $likes[] = $row;
        }

        echo json_encode($likes);
        break;

    default:
        echo json_encode(['error' => 'Ação inválida']);
        break;
}
