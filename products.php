<?php
// Inicia a sessão para verificar se o usuário está logado
session_start();

// Verifica se o usuário não está logado; redireciona para a página de login se não estiver
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
include 'db.php';

// Obtém o ID do usuário da sessão
$user_id = $_SESSION['user_id'];

// Consulta para selecionar todos os produtos da tabela 'products'
$products = $conn->query("SELECT * FROM products");

// Consulta para obter o nome de usuário do usuário logado
$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id); // Associa o parâmetro ao statement
$stmt->execute(); // Executa o statement
$stmt->bind_result($username); // Associa o resultado da consulta à variável
$stmt->fetch(); // Obtém o resultado
$stmt->close(); // Fecha o statement
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <!-- Inclusão do CSS do Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #0d47a1; /* Cor de fundo azul marinho */
            color: red; /* Cor do texto */
            font-family: Arial, sans-serif; /* Fonte para o texto */
        }

        .card-text {
            color: green; /* Cor do texto do preço verde */
        }

        .header-banner {
            background-color: #000; /* Fundo preto para o banner */
            color: #0d47a1; /* Cor do texto azul marinho */
            text-align: center;
            padding: 20px;
            margin-bottom: 20px;
        }

        .advertisement {
            background-color: #ff9800; /* Fundo laranja para o bloco de propaganda */
            color: #fff; /* Cor do texto branco */
            padding: 20px;
            text-align: center;
        }

        /* Estilos adicionais */
        .sovai {
            color: red; /* Cor do texto vermelho */
        }

        .mano {
            color: white; /* Cor do texto branco */
        }
    </style>
</head>
<body>
<div class="container">
        
    <div class="container">
        <!-- Banner de boas-vindas -->
        <div class="header-banner">
            <h2 class="mano">Bem-vindo à nossa loja Rações.com, aqui estão nossos produtos</h2>
        </div>
        <!-- Banner de boas-vindas com nome de usuário -->
        <div class="header-banner">
            <h2 class="sovai">Olá, <?php echo $username; ?>!</h2>
            <p class="sovai">Escolha seus produtos</p>
        </div>
        <div class="row">
            <div class="col-md-9">
                <!-- Botões de navegação -->
                <div class="text-right mb-3">
                    <a href="order_history.php" class="btn btn-info">Histórico de Compras</a>
                    <a href="cart.php" class="btn btn-primary">Ir para o Carrinho</a>
                    <a href="logout.php" class="btn btn-secondary">Logout</a>
                </div>
                <!-- Grid de produtos -->
                <div class="row">
                 <!--Loop para iterar sobre os resultados da consulta SQL -->
                    <?php while ($row = $products->fetch_assoc()): ?>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <img src="https://img.freepik.com/vetores-gratis/comida-de-cao-flutuante-cereal-desenhos-animados-vector-icon-ilustracao-conceito-de-icone-de-comida-animal-isolado-premium_138676-4763.jpg?size=338&ext=jpg&ga=GA1.1.1141335507.1718496000&semt=sph" class="card-img-top" alt="Imagem do Produto">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $row['name']; ?></h5>
                                    <p class="card-text">Preço: R$ <?php echo number_format($row['price'], 2, ',', '.'); ?></p>
                                    <!-- Formulário para adicionar ao carrinho -->
                                    <form method="POST" action="cart.php">
                                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                        <div class="form-group">
                                            <label for="quantity">Quantidade:</label>
                                            <input type="number" class="form-control" name="quantity" min="1" max="99" value="1" required>
                                        </div>
                                        <button type="submit" name="add_to_cart" class="btn btn-success">Adicionar ao Carrinho</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <!-- Coluna lateral para propaganda -->
            <div class="col-md-3">
                <div class="advertisement">
                    <h4>Homens solteiros a 80km de você</h4>
                    <p>Conheça agora!</p>
                    <!-- Imagem de propaganda -->
                    <img src="joaoreis.png" alt="Propaganda" class="img-fluid">
                    <p>Homem solteiro com uma paixão imensa</p>
                    <a href="#" class="btn btn-warning">Saiba mais</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
