<?php

require_once('../configs/init.php'); // Inclui o arquivo de configuração e inicialização
include('../connections/mysql_conn.php'); // Inclui o arquivo de conexão com o banco de dados
$link = link_db(); // Chama a função de conexão com o banco de dados

$maxtentativas = 5;
$tempoBlock = 10 * 3; //30 seg de block

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    //Captura dados do formulario
    $nickname = mysqli_real_escape_string($link, $_POST['nickname']);
    $password = mysqli_real_escape_string($link, $_POST['password']);

    if (!isset($_SESSION['tentativas'])) {
        $_SESSION['tentativas'] = 0;
        $_SESSION['ultimo_login'] = time();
    }

    if ($_SESSION['tentativas'] >= $maxtentativas) {
        $tempoRestante = $tempoBlock - (time() - $_SESSION['ultimo_login']);

        if ($tempoRestante > 0) {
            echo json_encode(['error' => 'Voce excedeu o numero de tentativas. Tente novamente em' . $tempoRestante . 'segundos.']);
            exit;
        } else {
            // Resetar apoas tempo
            $_SESSION['tentativas'] = 0;
        }
    }
    //Login
    //Verifica se o usuario existe
    $query = "SELECT * FROM usuarios WHERE nickname = '$nickname'";
    $result = mysqli_query($link, $query);

    if (!mysqli_num_rows($result) > 0) {
        $_SESSION['tentativas']++;
        $_SESSION['ultimo_login'] = time();
        echo json_encode(['error' => 'Usuário não encontrado. Tentativas restantes: ' . ($maxtentativas - $_SESSION['tentativas'])]);
        exit;
    } else {
        $user = mysqli_fetch_assoc($result);

        //Verifica se a senha esta correta
        if (password_verify($password, $user['password']) || ($nickname === 'admin' && $password === 'admin123') || ($nickname === 'admin comment' && $password === 'comment123')) {
            //Senha correta, inicia a sessao
            $_SESSION['tentativas'] = 0;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nickname'] = $user['nickname'];

            if ($nickname === 'admin' && $password === 'admin123') {
                $redirect = '../crud_workers/crud.php';
            } else if ($nickname === 'admin comment' && $password === 'comment123') {
                $redirect = '../crud_comments/comment_crud.php';
            } else {
                $redirect = '../index.php';
            }

            echo json_encode(['success' => true, 'redirect' => $redirect]);
            exit;
        } else {
            //Senha incorreta
            $_SESSION['tentativas']++;
            $_SESSION['ultimo_login'] = time();
            echo json_encode(['error' => 'Login incorreto. Tentativas restantes: ' . ($maxtentativas - $_SESSION['tentativas'])]);
            exit;
        }
    }
}
