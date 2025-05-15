<?php
require_once('configs/init.php'); // Inclui o arquivo de configuração e inicialização

if (isset($_SESSION['user_nickname'])) { // Verifica se o usuário está logado
    $_SESSION == array(); // Limpa todos os dados da sessão
    session_destroy(); // Destroi a sessão atual
}

header('Location: logins/login.php'); // Redireciona para a página de login