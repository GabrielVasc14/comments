<?php

require_once('../configs/init.php');
include('../connections/mysql_conn.php'); // Inclui o arquivo de conexão com o banco de dados
$link = link_db(); // Chama a função de conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Captura dados do usuario
    $nickname = trim(mysqli_real_escape_string($link, $_POST['nickname']));
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $password = mysqli_real_escape_string($link, $_POST['password']);
    $confirmPassword = mysqli_real_escape_string($link, $_POST['confirm_password']);

    //Validacao simples
    if ($password != $confirmPassword) {
        echo json_encode(['error' => 'As senhas nao coincidem.']);
        exit;
    }
    if (empty($nickname) || empty($email) || empty($password)) {
        echo json_encode(['error' => 'Preencha todos os campos.']);
        exit;
    }

    //Verifica se o email já existe
    $query = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($link, $query);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(['error' => 'Email já cadastrado.']);
        exit;
    } else {
        //Hash da senha
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        //Cria um novo usuario
        $insert_query = "INSERT INTO usuarios (nickname, email, password) VALUES ('$nickname', '$email', '$password_hash')";

        // $to = $email;
        // $subject = "Cadastro realizado com sucesso, Bem vindo ao nosso site!";
        // $message = "Seu cadastro foi realizado com sucesso.";

        // if (mail($to, $subject, $message)) {
        //     echo json_encode(['success' => 'Email enviado com sucesso!']);
        // } else {
        //     echo json_encode(['error' => 'Erro ao enviar email.']);
        // }

        if (mysqli_query($link, $insert_query)) {
            // Redireciona para a página de login após o cadastro
            echo json_encode(['success' => true, 'redirect' => '../logins/login.php']);
            exit;
        } else {
            echo json_encode(['error' => 'Erro ao cadastrar usuario: ' . mysqli_error($link)]);
            exit;
        }
    }
}
