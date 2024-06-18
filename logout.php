<?php
// Inicia a sessão para manipulação das variáveis de sessão
session_start();

// Destroi todas as informações registradas na sessão
session_destroy();

// Redireciona o usuário para a página de login
header("Location: login.php");

// Garante que o script seja encerrado após o redirecionamento
exit();
?>
