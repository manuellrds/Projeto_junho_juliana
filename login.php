<?php
// Inicia a sessão para gerenciar o estado de login do usuário
session_start();

// Inclui o arquivo de conexão com o banco de dados
include 'db.php';

// Verifica se o formulário foi submetido (método POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Verifica se o formulário de registro foi submetido
    if (isset($_POST['register'])) {
        // Obtém os valores dos campos do formulário de registro
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash da senha para segurança
        $email = $_POST['email'];

        // Prepara a inserção dos dados na tabela 'users'
        $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $email); // Associa os parâmetros ao statement
        $stmt->execute(); // Executa o statement
        $stmt->close(); // Fecha o statement

        echo "Registration successful!"; // Mensagem de sucesso para o usuário
    } 
    // Verifica se o formulário de login foi submetido
    elseif (isset($_POST['login'])) {
        // Obtém os valores dos campos do formulário de login
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Consulta o banco de dados para obter a senha hash correspondente ao nome de usuário
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username); // Associa o parâmetro ao statement
        $stmt->execute(); // Executa o statement
        $stmt->bind_result($id, $hashed_password); // Associa o resultado da consulta às variáveis
        $stmt->fetch(); // Obtém o resultado
        $stmt->close(); // Fecha o statement

        // Verifica se a senha digitada corresponde à senha hash no banco de dados
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id; // Define a variável de sessão para o ID do usuário logado
            header("Location: products.php"); // Redireciona para a página de produtos após o login
            exit();
        } else {
            echo "Invalid login credentials!"; // Mensagem de erro para credenciais inválidas
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Inclusão do CSS do Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #f8f9fa; /* Cor de fundo para evitar a transparência dos gifs */
        }
        .gif-container {
            position: fixed;
            width: 50%;
            height: 50%;
            z-index: -1; /* Coloca os gifs atrás do conteúdo */
        }
        .top-left {
            top: 0;
            left: 0;
            background: url('https://i.pinimg.com/originals/4b/81/9d/4b819dfef1673065f9bdbe8ce302aa0e.gif') no-repeat center center;
            background-size: cover;
        }
        .top-right {
            top: 0;
            right: 0;
            background: url('https://i.pinimg.com/originals/3a/cb/28/3acb286033825938cfed1a6cd8e8f0d7.gif') no-repeat center center;
            background-size: cover;
        }
        .bottom-left {
            bottom: 0;
            left: 0;
            background: url('https://i.pinimg.com/originals/79/a3/30/79a3304e135957fa236152ee5e0d9858.gif') no-repeat center center;
            background-size: cover;
        }
        .bottom-right {
            bottom: 0;
            right: 0;
            background: url('https://i.pinimg.com/originals/ef/2e/f0/ef2ef03e8ee0989a47e0b3095abc62ae.gif') no-repeat center center;
            background-size: cover;
        }
        .form-container {
            position: relative;
            z-index: 1; /* Coloca o conteúdo na frente dos gifs */
            background: rgba(255, 255, 255, 0.8); /* Fundo branco semi-transparente */
            padding: 30px; /* Aumenta o espaço interno do formulário */
            margin-top: 50px; /* Aumenta a margem superior para centralizar melhor */
            border-radius: 10px; /* Aumenta o raio da borda */
        }
        .form-container h2 {
            margin-bottom: 20px; /* Espaçamento abaixo do título */
        }
        .form-group {
            margin-bottom: 20px; /* Espaçamento entre os campos */
        }
        .btn-primary {
            padding: 12px 30px; /* Aumenta o padding do botão */
            font-size: 18px; /* Aumenta o tamanho da fonte do botão */
        }
    </style>
</head>
<body>
    <!-- Gifs de fundo -->
    <div class="gif-container top-left"></div>
    <div class="gif-container top-right"></div>
    <div class="gif-container bottom-left"></div>
    <div class="gif-container bottom-right"></div>

    <!-- Conteúdo do formulário -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <h2 class="text-center">Login</h2>
                    <!-- Formulário de login -->
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="username">Nome de Usuário</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Senha</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
                    </form>
                </div>

                <div class="form-container mt-4">
                    <h2 class="text-center">Registrar-se</h2>
                    <!-- Formulário de registro -->
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="username">Nome de Usuário</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Senha</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <button type="submit" name="register" class="btn btn-primary btn-block">Registrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Inclusão dos scripts JavaScript do Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
